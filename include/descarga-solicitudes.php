<?php
/**
 * File: mix-solicitud/include/descarga-solicitudes.php
 *
 * @package mix_sol
 */

defined( 'ABSPATH' ) || die();

// Agrega el action hook solo si accion=descarga_csv.
if ( isset( $_GET['accion'] ) && $_GET['accion'] == 'descarga_csv' ) {
	add_action( 'admin_init', 'genera_csv' );
}

function genera_csv() {
	// Comprueba que el usuario actual tenga permisos suficientes.
	if( !current_user_can( 'manage_options' ) ){
		return false;
	}
	// Comprueba que estamos en el escritorio.
	if( !is_admin() ){
		return false;
	}
	// Comprueba Nonce.
	$nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
	if ( ! wp_verify_nonce( $nonce, 'descarga_csv' ) ) {
		die( 'Security check error' );
	}
	ob_start();
	$filename = 'mix-solicitudes-' . date('Ymd') . '.csv';

	$fila_titulos = array(
		'Nombre',
		'Documento',
		'Celular',
		'Teléfono',
		'Correo',
		'Departamento',
		'Ciudad',
		'Monto',
		'Fecha',
	);
	$filas_datos = array();
	global $wpdb;
	$tabla_solicitud = $wpdb->prefix . 'solicitud';
	$solicitudes     = $wpdb->get_results( "SELECT * FROM $tabla_solicitud", OBJECT);
	foreach ( $solicitudes as $solicitud ) {
		$tax_departamento = get_term_by( 'id', $solicitud->departamento_id, 'mix-departamento' );
		$fila = array(
			$solicitud->nombre, 
			$solicitud->documento,
			$solicitud->celular,
			$solicitud->telefono,
			$solicitud->correo,
			$tax_departamento->name,
			$solicitud->ciudad,
			$solicitud->monto,
			$solicitud->created_at,
		);
		$filas_datos[] = $fila;
	}
	$handler = @fopen( 'php://output', 'w' );
	fprintf( $handler, chr(0xEF) . chr(0xBB) . chr(0xBF) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	fputcsv( $handler, $fila_titulos );
	foreach ( $filas_datos as $fila ) {
		fputcsv( $handler, $fila );
	}
	fclose( $handler );
	ob_end_flush();
	die();
}
