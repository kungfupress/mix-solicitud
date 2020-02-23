<?php
/**
 * File: mix-solicitud/include/plugin-init.php
 *
 * @package mix_sol
 */

defined( 'ABSPATH' ) || die();


// Cuando el plugin se active se crea la tabla del mismo si no existe.
register_activation_hook( MIX_SOL_PLUGIN_FILE, 'mix_solicitud_init' );

/**
 * Realiza las acciones necesarias para configurar el plugin cuando se activa
 *
 * @return void
 */
function mix_solicitud_init() {
	global $wpdb; // Este objeto global nos permite trabajar con la BD de WP
	// Crea la tabla si no existe.
	$tabla_solicitud = $wpdb->prefix . 'solicitud';
	$charset_collate = $wpdb->get_charset_collate();
	$query           = "CREATE TABLE IF NOT EXISTS $tabla_solicitud (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(250) NOT NULL,
        documento varchar(12) NOT NULL,
        celular varchar(15) NOT NULL,
        telefono varchar(15),
        correo varchar(100) NOT NULL,
        departamento_id mediumint(9) NOT NULL,
        ciudad varchar(250) NOT NULL,
		monto float(10) NOT NULL,
        aceptacion smallint(4) NOT NULL,
        ip varchar(300),
        created_at datetime NOT NULL,
        UNIQUE (id)
        ) $charset_collate;";
	// La función dbDelta que nos permite crear tablas de manera segura se
	// define en el fichero upgrade.php que se incluye a continuación.
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $query );
}
