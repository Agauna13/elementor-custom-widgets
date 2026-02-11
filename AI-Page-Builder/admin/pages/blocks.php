<?php
/**
 * Vista: Página de gestión de bloques.
 *
 * Variables disponibles:
 * @var array $installed_blocks Lista de slugs de bloques instalados.
 * @var string $message Mensajes de éxito/error.
 */

// Evitar acceso directo.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap aipb-wrap">
	<h1 class="wp-heading-inline">Gestión de Bloques AI</h1>
	<hr class="wp-header-end">

	<?php echo $message; ?>

	<div class="aipb-header">
		<h2>Instalar Nuevo Bloque</h2>
		<p class="description">Sube un archivo .zip que contenga la carpeta del bloque (markup.html, styles.css, schema.json).</p>
	</div>

	<div class="aipb-upload-area">
		<form method="post" enctype="multipart/form-data">
			<?php wp_nonce_field( 'aipb_upload_action', 'aipb_upload_nonce' ); ?>
			<input type="file" name="aipb_zip_file" accept=".zip" required>
			<?php submit_button( 'Subir e Instalar', 'primary', 'aipb_upload_submit', false ); ?>
		</form>
	</div>

	<div class="aipb-header">
		<h2>Bloques Instalados</h2>
	</div>

	<table class="aipb-table widefat fixed striped">
		<thead>
			<tr>
				<th>ID (Slug)</th>
				<th>Shortcode</th>
				<th>Archivos</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $installed_blocks ) ) : ?>
				<tr>
					<td colspan="4">No hay bloques instalados.</td>
				</tr>
			<?php else : ?>
				<?php foreach ( $installed_blocks as $slug ) : ?>
					<tr>
						<td><strong><?php echo esc_html( $slug ); ?></strong></td>
						<td><code>[ai_block id="<?php echo esc_attr( $slug ); ?>"]</code></td>
						<td>
							<?php 
							// Verificar integridad básica visualmente
							$path = AIPB_UPLOADS_DIR . '/' . $slug;
							$has_html = file_exists( $path . '/markup.html' ) ? '✅ HTML' : '❌ HTML';
							$has_css = file_exists( $path . '/styles.css' ) ? '✅ CSS' : '❌ CSS';
							$has_json = file_exists( $path . '/schema.json' ) ? '✅ JSON' : '❌ JSON';
							echo "$has_html $has_css $has_json";
							?>
						</td>
						<td>
							<button class="button button-small button-link-delete">Borrar (Próximamente)</button>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>
