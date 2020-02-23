<?php
/**
 * File: mix-solicitud/include/tax-departamento.php
 *
 * @package mix_sol
 */

defined( 'ABSPATH' ) || die();

register_activation_hook( __FILE__, 'mix_taxonomy_departamento' );
add_action( 'init', 'mix_taxonomy_departamento', 0 );
/**
 * Registra la taxonomía con lo mínimo indispensable
 *
 * @return void
 */
function mix_taxonomy_departamento() {
	$args = array(
		'label'             => 'Departamento',
		'hierarchical'      => false,
		'show_admin_column' => true,
	);
	register_taxonomy( 'mix-departamento', array( 'mix-solicitud' ), $args );
}

add_action( 'init', 'mix_departamento_add', 1 );
/**
 * Agrega los departamentos de Uruguay
 *
 * @return void
 */
function mix_departamento_add() {
	$departamentos = array(
		'Artigas',
		'Canelones',
		'Cerro Largo',
		'Colonia',
		'Durazno',
		'Flores',
		'Florida',
		'Lavalleja',
		'Maldonado',
		'Montevideo',
		'Paysandú',
		'Río Negro',
		'Rivera',
		'Rocha',
		'Salto',
		'San José',
		'Soriano',
		'Tacuarembó',
		'Treinta y Tres',
	);
	foreach ( $departamentos as $departamento ) {
		wp_insert_term( $departamento, 'mix-departamento' );
	}
}