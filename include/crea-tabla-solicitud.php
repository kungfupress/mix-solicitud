<?php
/**
 * File: mix-solicitud/include/crea-tabla-solicitud.php
 *
 * @package mix_sol
 */

defined( 'ABSPATH' ) || die();

// Cuando el plugin se active se crea la tabla del mismo si no existe.
register_activation_hook( MIX_SOL_PLUGIN_FILE, 'mix_crea_tabla_solicitud' );

/**
 * Realiza las acciones necesarias para configurar el plugin cuando se activa
 *
 * @return void
 */
function mix_crea_tabla_solicitud() {
	global $wpdb; // Este objeto global nos permite trabajar con la BD de WP
	// Crea la tabla si no existe.
	$tabla_solicitud = $wpdb->prefix . 'solicitud';
	$charset_collate = $wpdb->get_charset_collate();
	$query           = "CREATE TABLE IF NOT EXISTS $tabla_solicitud (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(250) NOT NULL,
        documento varchar(12) NOT NULL,
        celular varchar(15) NOT NULL,
        telefono varchar(15) NOT NULL,
        correo varchar(100) NOT NULL,
        departamento_id mediumint(9) NOT NULL,
        ciudad varchar(250) NOT NULL,
		monto float(10) NOT NULL,
        created_at datetime NOT NULL,
        UNIQUE (id)
        ) $charset_collate;";
	// La función dbDelta que nos permite crear tablas de manera segura se
	// define en el fichero upgrade.php que se incluye a continuación.
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $query );
}
