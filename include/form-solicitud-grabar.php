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
add_action( 'admin_post_mix-solicitud', 'kfp_mix_graba_solicitud' );
add_action( 'admin_post_nopriv_mix-solicitud', 'kfp_mix_graba_solicitud' );

/**
 * Graba los datos enviados por el formulario como un nuevo CPT kfp-taller
 *
 * @return void
 */
function mix_graba_solicitud() {
	// Si viene en $_POST aprovecha uno de los campos que crea wp_nonce para volver al form.
	$url_origen = home_url( '/' );
	if ( ! empty( $_POST['_wp_http_referer'] ) ) {
		$url_origen = esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) );
	}
	// Comprueba campos requeridos y nonce.
	if ( isset( $_POST['nombre'] )
	&& isset( $_POST['id_departamento'] )
	&& isset( $_POST['mix-solicitud-nonce'] )
	&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mix-solicitud-nonce'] ) ), 'mix-solicitud' )
	) {
		$nombre          = sanitize_text_field( wp_unslash( $_POST['nombre'] ) );
		$documento       = sanitize_text_field( wp_unslash( $_POST['documento'] ) );
		$celular         = sanitize_text_field( wp_unslash( $_POST['celular'] ) );
		$telefono        = sanitize_text_field( wp_unslash( $_POST['telefono'] ) );
		$correo          = sanitize_text_field( wp_unslash( $_POST['correo'] ) );
		$id_departamento = (int) $_POST['id_departamento'];
		$ciudad          = sanitize_text_field( wp_unslash( $_POST['ciudad'] ) );
		$monto           = (float) $_POST['monto'];

		$term_taxonomy_ids = wp_set_object_terms( $post_id, $id_departamento, 'mix-departamento' );
		$query_arg = array( 'kfp-ftx-resultado' => 'success' );
		wp_redirect( esc_url_raw( add_query_arg( $query_arg , $url_origen ) ) );
		exit();
	}
	$query_arg = array( 'kfp-ftx-resultado' => 'error' );
	wp_redirect( esc_url_raw( add_query_arg( $query_arg , $url_origen ) ) );
	exit();
}
