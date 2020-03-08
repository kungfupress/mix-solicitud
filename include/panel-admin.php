<?php
/**
 * File: mix-solicitud/include/panel-admin.php
 *
 * @package mix_sol
 */

defined( 'ABSPATH' ) || die();

add_action( 'admin_menu', 'mix_solicitud_menu' );

/**
 * Agrega el menú del plugin al formulario de WordPress
 *
 * @return void
 */
function mix_solicitud_menu() {
	add_menu_page(
		'Solicitudes Credito',
		'Solicitudes',
		'manage_options',
		'mix_solicitud_menu',
		'mix_solicitud_admin',
		'dashicons-feedback',
		75
	);
}

/**
 * Agrega el panel de administración del plugin al escritorio
 *
 * @return void
 */
function mix_solicitud_admin() {
	global $wpdb;
	$tabla_solicitud = $wpdb->prefix . 'solicitud';
	$solicitudes     = $wpdb->get_results( "SELECT * FROM $tabla_solicitud", OBJECT );

	$html  = '<div class="wrap"><h1>Lista de solicitudes</h1>';
	$html .= '<div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=mix_solicitud_menu' );
	$html .= '&accion=descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'descarga_csv' ) . '">Descargar fichero CSV</a></div><br>';
	$html .= '<table class="wp-list-table widefat fixed striped">';
	$html .= '<thead><tr><th>Nombre</th><th>Documento</th><th>Celular</th>';
	$html .= '<th>Teléfono</th><th>Correo</th><th>Departamento</th><th>Ciudad / Localidad / Barrio</th><th>Monto</th>';
	$html .= '<td></td></tr></thead>';
	$html .= '<tbody id="the-list">';
	foreach ( $solicitudes as $solicitud ) {
		$nombre           = esc_textarea( $solicitud->nombre );
		$documento        = esc_textarea( $solicitud->documento );
		$celular          = esc_textarea( $solicitud->celular );
		$telefono         = esc_textarea( $solicitud->telefono );
		$correo           = esc_textarea( $solicitud->correo );
		$tax_departamento = get_term_by( 'id', $solicitud->departamento_id, 'mix-departamento' );
		$departamento     = $tax_departamento->name;
		$ciudad           = esc_textarea( $solicitud->ciudad );
		$monto            = (int) $solicitud->monto;
		$html .= "<tr><td>$nombre</td><td>$documento</td><td>$celular</td>";
		$html .= "<td>$telefono</td><td>$correo</td><td>$departamento</td>";
		$html .= "<td>$ciudad</td><td>$monto</td>";
		$html .= "<td><a href='#' data-solicitud_id='$solicitud->id' class='sol-borrar'>Borrar</a></td></tr>";
	}
	$html .= '</tbody></table></div>';
	$html .= '<br><div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=mix_solicitud_menu' );
	$html .= '&accion=descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'descarga_csv' ) . '">Descargar fichero CSV</a></div>';
	echo $html;
}

add_action( 'admin_enqueue_scripts', 'mix_solicitud_admin_scripts' );
/**
 * Agrega el script que borra mediante AJAX las solicitudes desde el panel de administración
 *
 * @return void
 */
function mix_solicitud_admin_scripts() {
	wp_register_script(
		'mix-solicitud-borrar',
		MIX_SOL_URL . 'assets/solicitud-borrar.js',
		array( 'jquery' ),
		MIX_SOL_VERSION,
		false
	);
	wp_localize_script(
		'mix-solicitud-borrar',
		'ajax_object',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'mix_solicitud_borrar_' . admin_url( 'admin-ajax.php' ) ),
		)
	);
	wp_enqueue_script( 'mix-solicitud-borrar' );

}

// Prepara los hooks para borrar solicitudes con ajax.
add_action( 'wp_ajax_mix_solicitud_borrar', 'mix_solicitud_borrar' );
/**
 * Borra la solicitud seleccionada
 *
 * @return void
 */
function mix_solicitud_borrar() {
	global $wpdb;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
			'mix_solicitud_borrar_' . admin_url( 'admin-ajax.php' )
		)
		) {
		$solicitud_id = filter_input( INPUT_POST, 'solicitud_id', FILTER_SANITIZE_NUMBER_INT );
		$tabla_solicitud = $wpdb->prefix . 'solicitud';
		$wpdb->delete( $tabla_solicitud, array( 'id' => $solicitud_id )  , array( '%d' ) );
		echo '1';
		die();
	} else {
		echo '-1';
		die( 'Error de seguridad' );
	}
}
