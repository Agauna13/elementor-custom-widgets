<?php
/**
 * Plugin Name: Elementor Custom Widget Manager
 * Description: Lightweight plugin to upload and auto-load Elementor widgets independently from the child theme structure.
 * Version: 1.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Elementor_Custom_Widget_Manager {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		// Admin Menu for Uploads
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
		
		// Widget Registration (The "Auto-Loader")
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ], 99 ); // Late priority to ensure theme is loaded
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_assets' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * 1. Admin Menu & Upload Handler
	 */
	public function register_admin_menu() {
		add_submenu_page(
			'tools.php',
			'Custom Widgets Manager',
			'Custom Widgets',
			'manage_options',
			'elementor-custom-widgets',
			[ $this, 'render_admin_page' ]
		);
	}

	public function render_admin_page() {
		// Handle Upload
		$message = '';
		if ( isset( $_POST['ecwm_upload_nonce'], $_FILES['widget_zip'] ) && wp_verify_nonce( $_POST['ecwm_upload_nonce'], 'ecwm_upload_action' ) ) {
			$message = $this->handle_zip_upload( $_FILES['widget_zip'] );
		}

		?>
		<div class="wrap">
			<h1>Elementor Custom Widget Uploader</h1>
			<p>Upload a .zip file containing your Elementor widget files (.php, .css, .js). The plugin will automatically place them in your active child theme.</p>
			
			<?php if ( ! empty( $message ) ) : ?>
				<div class="updated notice is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
			<?php endif; ?>

			<form method="post" enctype="multipart/form-data" style="margin-top: 20px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 600px;">
				<?php wp_nonce_field( 'ecwm_upload_action', 'ecwm_upload_nonce' ); ?>
				
				<label for="widget_zip" style="font-weight: bold; display: block; margin-bottom: 10px;">Select Widget ZIP File</label>
				<input type="file" name="widget_zip" id="widget_zip" accept=".zip" required style="margin-bottom: 20px;">
				<br>
				<input type="submit" class="button button-primary" value="Upload & Install Widget">
			</form>

			<hr>

			<h3>Current Structure (Target)</h3>
			<p>Files will be installed to: <code><?php echo esc_html( get_stylesheet_directory() . '/custom-widgets/' ); ?></code></p>
			<ul>
				<li><strong>PHP Widgets:</strong> <code>/custom-widgets/widgets/</code></li>
				<li><strong>CSS:</strong> <code>/custom-widgets/assets/css/</code></li>
				<li><strong>JS:</strong> <code>/custom-widgets/assets/js/</code></li>
			</ul>
		</div>
		<?php
	}

	private function handle_zip_upload( $file ) {
		if ( empty( $file ) || $file['error'] !== UPLOAD_ERR_OK ) {
			return 'Error: File upload failed.';
		}

		$zip = new ZipArchive;
		if ( $zip->open( $file['tmp_name'] ) === TRUE ) {
			
			$target_base = get_stylesheet_directory() . '/custom-widgets/';
			$extracted_count = 0;

			for ( $i = 0; $i < $zip->numFiles; $i++ ) {
				$filename = $zip->getNameIndex( $i );
				$fileinfo = pathinfo( $filename );
				
				// Skip directories and __MACOSX garbage
				if ( substr( $filename, -1 ) == '/' || strpos( $filename, '__MACOSX' ) !== false ) {
					continue;
				}

				$extension = strtolower( $fileinfo['extension'] );
				$target_path = '';

				if ( $extension === 'php' ) {
					$target_path = $target_base . 'widgets/' . basename( $filename );
				} elseif ( $extension === 'css' ) {
					$target_path = $target_base . 'assets/css/' . basename( $filename );
				} elseif ( $extension === 'js' ) {
					$target_path = $target_base . 'assets/js/' . basename( $filename );
				}

				if ( ! empty( $target_path ) ) {
					// Ensure directory exists (Recursive creation)
					$target_dir = dirname( $target_path );
					if ( ! file_exists( $target_dir ) ) {
						wp_mkdir_p( $target_dir ); // WordPress helper for recursive mkdir
					}

					// Read from zip and write to target
					$content = $zip->getFromIndex( $i );
					if ( file_put_contents( $target_path, $content ) !== false ) {
						$extracted_count++;
					}
				}
			}
			
			$zip->close();
			return "Success! Installed $extracted_count files to child theme.";
		} else {
			return 'Error: Failed to open ZIP file.';
		}
	}

	/**
	 * 2. Auto-Registration Logic
	 */
	public function register_widgets( $widgets_manager ) {
		$widgets_dir = get_stylesheet_directory() . '/custom-widgets/widgets/';

		if ( ! is_dir( $widgets_dir ) ) {
			return;
		}

		$files = glob( $widgets_dir . '*.php' );

		foreach ( $files as $file ) {
			// Check declared classes before
			$classes_before = get_declared_classes();
			
			// Include the file
			require_once $file;
			
			// Check declared classes after
			$classes_after = get_declared_classes();
			$new_classes = array_diff( $classes_after, $classes_before );

			foreach ( $new_classes as $class ) {
				// Verify if it extends Elementor Widget_Base
				if ( class_exists( $class ) && is_subclass_of( $class, '\Elementor\Widget_Base' ) ) {
					$widgets_manager->register( new $class() );
				}
			}
		}
	}

	public function enqueue_assets() {
		$version = '1.0.0'; // You might want dynamic versioning based on filemtime
		
		// Enqueue CSS
		$css_dir = get_stylesheet_directory() . '/custom-widgets/assets/css/';
		$css_uri = get_stylesheet_directory_uri() . '/custom-widgets/assets/css/';
		if ( is_dir( $css_dir ) ) {
			foreach ( glob( $css_dir . '*.css' ) as $file ) {
				$handle = 'custom-widget-' . basename( $file, '.css' );
				wp_enqueue_style( $handle, $css_uri . basename( $file ), [], $version );
			}
		}

		// Enqueue JS
		$js_dir = get_stylesheet_directory() . '/custom-widgets/assets/js/';
		$js_uri = get_stylesheet_directory_uri() . '/custom-widgets/assets/js/';
		if ( is_dir( $js_dir ) ) {
			foreach ( glob( $js_dir . '*.js' ) as $file ) {
				$handle = 'custom-widget-' . basename( $file, '.js' );
				wp_enqueue_script( $handle, $js_uri . basename( $file ), [ 'jquery', 'elementor-frontend' ], $version, true );
			}
		}
	}

}

Elementor_Custom_Widget_Manager::instance();
