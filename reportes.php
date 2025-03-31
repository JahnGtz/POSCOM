<?php
require_once('tcpdf/tcpdf.php');
include('db/db.php');

// Obtener las fechas del filtro
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

// Crear la consulta con filtro por fechas
$query_ventas = "SELECT v.id, v.fecha, v.precio_total, u.nombre as vendedor, p.nombre as producto, d.cantidad
                 FROM ventas v
                 JOIN usuarios u ON v.vendedor_id = u.id
                 JOIN detalle_ventas d ON v.id = d.venta_id
                 JOIN productos p ON d.producto_id = p.id
                 WHERE 1";

// Si se han ingresado fechas, agregar el filtro a la consulta
if ($fecha_inicio && $fecha_fin) {
    $query_ventas .= " AND DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$query_ventas .= " ORDER BY v.fecha DESC";

$resultado_ventas = mysqli_query($conexion, $query_ventas);

// Verificar si la consulta fue exitosa
if (!$resultado_ventas) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Crear el objeto TCPDF
$pdf = new TCPDF();
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C');

// Tabla con los datos
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 10, 'Producto', 1, 0, 'C');
$pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(30, 10, 'Total', 1, 1, 'C');

while ($venta = mysqli_fetch_assoc($resultado_ventas)) {
    $pdf->Cell(30, 10, $venta['producto'], 1, 0, 'C');
    $pdf->Cell(30, 10, $venta['cantidad'], 1, 0, 'C');
    $pdf->Cell(30, 10, '$' . $venta['precio_total'], 1, 1, 'C');
}

// Cerrar y generar el PDF
$pdf->Output('reporte_ventas.pdf', 'I');
?>
