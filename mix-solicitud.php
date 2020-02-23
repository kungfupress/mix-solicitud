<?php
/**
 * Plugin Name:  KFP FormTaxon
 * Plugin URI:   https://github.com/kungfupress/mix-solicitud
 * Description:  Formulario de solicitud. Inserta el shortcode [mix_form_solicitud]
 * Version:      0.1.0
 * Author:       KungFuPress
 * Author URI:   https://kungfupress.com/
 * PHP Version:  5.6
 *
 * @package  mix_sol
 */

defined( 'ABSPATH' ) || die();

// Constantes que afectan a todos los ficheros del plugin.
define( 'MIX_SOL_DIR', plugin_dir_path( __FILE__ ) );
define( 'MIX_SOL_URL', plugin_dir_url( __FILE__ ) );
define( 'MIX_SOL_VERSION', '0.1.0' );

// Crea tablas y taxonomías.
require_once MIX_SOL_DIR . 'include/plugin-init.php';

// Agrega shortcode [mix_form_solicitud] con formulario para crear talleres.
require_once MIX_SOL_DIR . 'include/form-shortcode.php';

// Agrega función para que admin-post.php capture el envío de un nuevo taller desde un formulario.
require_once MIX_SOL_DIR . 'include/form-grabar.php';
