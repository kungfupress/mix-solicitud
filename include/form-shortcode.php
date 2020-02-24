<?php
/**
 * File: mix-solicitud/include/form-shortcode.php
 *
 * @package mix_sol
 */

defined( 'ABSPATH' ) || die();

add_shortcode( 'mix_form_solicitud', 'mix_form_solicitud' );
/**
 * Implementa formulario para crear un nuevo taller.
 *
 * @return string
 */
function mix_form_solicitud() {
	// Carga hoja de estilo.
	wp_enqueue_style( 'css_aspirante', MIX_SOL_URL . 'assets/style.css', null, MIX_SOL_VERSION );
	// Carga el script que valida el documento identidad.
	wp_enqueue_script( 'js_cedula', MIX_SOL_URL . 'assets/cedula-uruguay.js', array( 'jquery' ), MIX_SOL_VERSION, true );
	// Trae los departamentos existentes a una variable.
	// Esta variable recibirá un array de objetos de tipo taxonomy.
	$departamentos = get_terms(
		'mix-departamento',
		array(
			'orderby'    => 'term_id',
			'hide_empty' => 0,
		)
	);
	ob_start();
	if ( filter_input( INPUT_GET, 'mix-solicitud-resultado', FILTER_SANITIZE_STRING ) === 'success' ) {
		echo '<h4>Se ha grabado su solicitud correctamente</h4>';
	}
	if ( filter_input( INPUT_GET, 'mix-solicitud-resultado', FILTER_SANITIZE_STRING ) === 'error' ) {
		echo '<h4>Se ha producido un error al grabar su solicitud</h4>';
	}
	?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"
		id="form_solicitud">
		<?php wp_nonce_field( 'mix-solicitud', 'mix-solicitud-nonce' ); ?>
		<input type="hidden" name="action" value="mix-solicitud">
		<div class="form-input">
			<label for="nombre">Nombre y Apellidos</label>
			<input type="text" name="nombre" id="nombre" required>
		</div>
		<div class="form-input">
			<label for="documento">Documento de identidad</label>
			<input name="documento" id="ci" type="text" required="required" placeholder="0.000.000-0">
		</div>
		<div class="form-input">
			<label for="celular">Celular</label>
			<input name="celular" id="celular" type="number" required="required">
		</div>
		<div class="form-input">
			<label for="telefono">Teléfono</label>
			<input name="telefono" id="telefono" type="number">
		</div>
		<div class="form-input">
			<label for="correo">Correo</label>
			<input name="correo" id="correo" type="email">
		</div>
		<div class="form-input">
			<label for="id_departamento">Departamentos</label>
			<select name="id_departamento" required>
				<option value="">Seleccione departamento</option>
				<?php
				foreach ( $departamentos as $departamento ) {
					echo(
						'<option value="' . esc_attr( $departamento->term_id ) . '">'
						. esc_attr( $departamento->name ) . '</option>'
					);
				}
				?>
			</select>
		</div>
		<div class="form-input">
			<label for="ciudad">Ciudad/Localidad/Barrio</label>
			<input name="ciudad" id="ciudad" type="text" required>
		</div>
		<div class="form-input">
			<label for="monto">Monto deseado</label>
			<input name="monto" id="monto" type="number" required>
		</div>
		<div class="form-input">
			<input type="submit" value="Enviar">
		</div>
	</form>
	<?php
	return ob_get_clean();
}
