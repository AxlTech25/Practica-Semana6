<?php
/**
 * Controlador: ReporteController
 * Gestiona reportes, análisis y exportación de datos
 */

class ReporteController {
    private $reporteModel;
    
    public function __construct($conexion) {
        require_once MODELS_PATH . 'Reporte.php';
        $this->reporteModel = new Reporte($conexion);
    }
    
    /**
     * Mostrar dashboard de reportes
     */
    public function mostrarDashboard() {
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        $estadisticas = $this->reporteModel->estadisticasGenerales($fecha_inicio, $fecha_fin);
        $visitas_dia = $this->reporteModel->visitasPorDia($fecha_inicio, $fecha_fin);
        $visitas_despacho = $this->reporteModel->visitasPorDespacho($fecha_inicio, $fecha_fin);
        $tiempo_promedio = $this->reporteModel->tiempoPromedioPermanencia($fecha_inicio, $fecha_fin);
        $horas_pico = $this->reporteModel->horasPicoVisitas($fecha_inicio, $fecha_fin);
        
        require_once VIEWS_PATH . 'reportes_dashboard.php';
    }
    
    /**
     * Mostrar historial detallado de visitas
     */
    public function mostrarHistorial() {
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-90 days'));
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        require_once MODELS_PATH . 'Despacho.php';
        $despachoModel = new Despacho(global $conn);
        $despachos = $despachoModel->obtenerTodos();
        
        $despacho_filtro = $_GET['despacho'] ?? '';
        
        $historial = $this->reporteModel->historialVisitas(
            $fecha_inicio, 
            $fecha_fin, 
            $despacho_filtro ? (int)$despacho_filtro : null
        );
        
        require_once VIEWS_PATH . 'historial_visitas.php';
    }
    
    /**
     * Exportar a CSV
     */
    public function exportarCSV() {
        $tipo = $_GET['tipo'] ?? 'historial'; // historial, dia, despacho
        
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-90 days'));
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_' . $tipo . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if ($tipo === 'historial') {
            $historial = $this->reporteModel->historialVisitas($fecha_inicio, $fecha_fin);
            
            // Encabezados
            fputcsv($output, [
                'ID',
                'Visitante',
                'DNI',
                'Despacho',
                'Persona Visitada',
                'Fecha',
                'Hora Entrada',
                'Hora Salida',
                'Tiempo Permanencia',
                'Motivo',
                'Estado'
            ], ';');
            
            // Datos
            foreach ($historial as $registro) {
                fputcsv($output, [
                    $registro['id'],
                    $registro['nombre_completo'],
                    $registro['documento_identidad'],
                    $registro['despacho_nombre'],
                    $registro['persona_visitada'],
                    $registro['fecha_visita'],
                    $registro['hora_entrada'],
                    $registro['hora_salida'] ?? 'No registrada',
                    $registro['tiempo_permanencia'] ?? '-',
                    $registro['motivo_visita'],
                    $registro['estado']
                ], ';');
            }
        } 
        elseif ($tipo === 'dia') {
            $visitas_dia = $this->reporteModel->visitasPorDia($fecha_inicio, $fecha_fin);
            
            fputcsv($output, ['Fecha', 'Total Visitas', 'Visitas Finalizadas'], ';');
            
            foreach ($visitas_dia as $registro) {
                fputcsv($output, [
                    $registro['fecha_visita'],
                    $registro['total_visitas'],
                    $registro['visitas_finalizadas']
                ], ';');
            }
        }
        elseif ($tipo === 'despacho') {
            $visitas_despacho = $this->reporteModel->visitasPorDespacho($fecha_inicio, $fecha_fin);
            
            fputcsv($output, ['Despacho', 'Responsable', 'Total Visitas'], ';');
            
            foreach ($visitas_despacho as $registro) {
                fputcsv($output, [
                    $registro['nombre'],
                    $registro['responsable'],
                    $registro['total_visitas']
                ], ';');
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Exportar a Excel
     */
    public function exportarExcel() {
        $tipo = $_GET['tipo'] ?? 'historial';
        
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-90 days'));
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        if ($tipo === 'historial') {
            $historial = $this->reporteModel->historialVisitas($fecha_inicio, $fecha_fin);
            $this->generarExcelHistorial($historial);
        }
        elseif ($tipo === 'dia') {
            $visitas_dia = $this->reporteModel->visitasPorDia($fecha_inicio, $fecha_fin);
            $this->generarExcelPorDia($visitas_dia);
        }
        elseif ($tipo === 'despacho') {
            $visitas_despacho = $this->reporteModel->visitasPorDespacho($fecha_inicio, $fecha_fin);
            $this->generarExcelPorDespacho($visitas_despacho);
        }
    }
    
    /**
     * Generar Excel - Historial de visitas
     */
    private function generarExcelHistorial($datos) {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 10px; text-align: left; }
                th { background-color: #4CAF50; color: white; }
            </style>
        </head>
        <body>
            <h2>Historial de Visitas</h2>
            <p>Período: ' . $_GET['fecha_inicio'] . ' a ' . $_GET['fecha_fin'] . '</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Visitante</th>
                        <th>DNI</th>
                        <th>Despacho</th>
                        <th>Persona Visitada</th>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Tiempo Permanencia</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($datos as $registro) {
            $html .= '<tr>
                <td>' . $registro['id'] . '</td>
                <td>' . $registro['nombre_completo'] . '</td>
                <td>' . $registro['documento_identidad'] . '</td>
                <td>' . $registro['despacho_nombre'] . '</td>
                <td>' . $registro['persona_visitada'] . '</td>
                <td>' . $registro['fecha_visita'] . '</td>
                <td>' . $registro['hora_entrada'] . '</td>
                <td>' . ($registro['hora_salida'] ?? '-') . '</td>
                <td>' . ($registro['tiempo_permanencia'] ?? '-') . '</td>
                <td>' . $registro['motivo_visita'] . '</td>
                <td>' . $registro['estado'] . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table></body></html>';
        
        $filename = 'historial_visitas_' . date('Y-m-d') . '.xls';
        
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo $html;
        exit;
    }
    
    /**
     * Generar Excel - Visitas por día
     */
    private function generarExcelPorDia($datos) {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 10px; text-align: left; }
                th { background-color: #4CAF50; color: white; }
            </style>
        </head>
        <body>
            <h2>Visitas por Día</h2>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Total Visitas</th>
                        <th>Visitas Finalizadas</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($datos as $registro) {
            $html .= '<tr>
                <td>' . $registro['fecha_visita'] . '</td>
                <td>' . $registro['total_visitas'] . '</td>
                <td>' . $registro['visitas_finalizadas'] . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table></body></html>';
        
        $filename = 'visitas_por_dia_' . date('Y-m-d') . '.xls';
        
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo $html;
        exit;
    }
    
    /**
     * Generar Excel - Visitas por despacho
     */
    private function generarExcelPorDespacho($datos) {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 10px; text-align: left; }
                th { background-color: #4CAF50; color: white; }
            </style>
        </head>
        <body>
            <h2>Visitas por Despacho</h2>
            <table>
                <thead>
                    <tr>
                        <th>Despacho</th>
                        <th>Responsable</th>
                        <th>Total Visitas</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($datos as $registro) {
            $html .= '<tr>
                <td>' . $registro['nombre'] . '</td>
                <td>' . $registro['responsable'] . '</td>
                <td>' . $registro['total_visitas'] . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table></body></html>';
        
        $filename = 'visitas_por_despacho_' . date('Y-m-d') . '.xls';
        
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo $html;
        exit;
    }
}
?>
