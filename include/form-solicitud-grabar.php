<?php
/**
 * File: mix-solicitud/include/form-solicitud-grabar.php
 *
 * @package mix-sol
 */

defined( 'ABSPATH' ) || die();

// Agrega los action hooks para grabar el formulario (el primero para usuarios
// logeados y el otro para el resto)
// Lo que viene tras admin_post_ y admin_post_nopriv_ tiene que coincidir con
// el valor del campo input con nombre "action" del formulario enviado.
add_action( 'admin_post_mix-solicitud', 'mix_graba_solicitud' );
add_action( 'admin_post_nopriv_mix-solicitud', 'mix_graba_solicitud' );
/**
 * Graba los datos enviados por el formulario como un nuevo CPT kfp-taller
 *
 * @return void
 */
function mix_graba_solicitud() {
	global $wpdb;
	// Si viene en $_POST aprovecha uno de los campos que crea wp_nonce para volver al form.
	$url_origen = home_url( '/' );
	if ( ! empty( $_POST['_wp_http_referer'] ) ) {
		$url_origen = esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) );
	}
	// Define condicion de error a priori y si la cosa sale bien cambia a 'success'
	$query_arg = array( 'mix-solicitud-resultado' => 'error' );
	// Comprueba campos requeridos y nonce.
	if ( isset( $_POST['nombre'] )
	&& isset( $_POST['documento'] )
	&& isset( $_POST['celular'] )
	&& isset( $_POST['id_departamento'] )
	&& isset( $_POST['ciudad'] )
	&& isset( $_POST['monto'] )
	&& isset( $_POST['mix-solicitud-nonce'] )
	&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mix-solicitud-nonce'] ) ), 'mix-solicitud' )
	) {
		$nombre          = sanitize_text_field( wp_unslash( $_POST['nombre'] ) );
		$documento       = sanitize_text_field( wp_unslash( $_POST['documento'] ) );
		$celular         = sanitize_text_field( wp_unslash( $_POST['celular'] ) );
		$id_departamento = (int) $_POST['id_departamento'];
		$ciudad          = sanitize_text_field( wp_unslash( $_POST['ciudad'] ) );
		$monto           = (float) $_POST['monto'];
		$created_at = date('Y-m-d H:i:s');
		$telefono = isset( $_POST['telefono'] )?sanitize_text_field( wp_unslash( $_POST['telefono'] ) ):null;
		$correo = isset( $_POST['correo'] )?sanitize_text_field( wp_unslash( $_POST['correo'] ) ):null;
		$tabla_solicitud = $wpdb->prefix . 'solicitud';
		$resultado = $wpdb->insert(
			$tabla_solicitud,
			array(
				'nombre' => $nombre,
				'documento' => $documento,
				'celular' => $celular,
				'departamento_id' => $id_departamento,
				'ciudad' => $ciudad,
				'monto' => $monto,
				'telefono' => $telefono,
				'correo' => $correo,
				'created_at' => $created_at,
			)
		);
		if ( $resultado ) {
			$query_arg = array( 'mix-solicitud-resultado' => 'success' );
		}
	}
	wp_redirect( esc_url_raw( add_query_arg( $query_arg , $url_origen ) ) );
	exit();
}
