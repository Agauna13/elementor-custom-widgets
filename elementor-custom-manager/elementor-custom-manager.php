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
		
		// Setup theme automatically
		add_action( 'admin_init', [ $this, 'setup_theme_loader' ] );
	}
	
	public function setup_theme_loader() {
		$theme_dir = get_stylesheet_directory();
		$widgets_dir = $theme_dir . '/custom-widgets';
		$loader_file = $widgets_dir . '/widgets-loader.php';
		$functions_file = $theme_dir . '/functions.php';

		// 1. Create directory if not exists
		if ( ! is_dir( $widgets_dir ) ) {
			wp_mkdir_p( $widgets_dir );
		}
		
		// Create subdirectories just in case
		wp_mkdir_p( $widgets_dir . '/widgets' );
		wp_mkdir_p( $widgets_dir . '/assets/css' );
		wp_mkdir_p( $widgets_dir . '/assets/js' );

		// 2. Generate loader file
		$loader_content = '<?php
namespace AG_Custom_Widgets;

if ( ! defined( "ABSPATH" ) ) exit;

class Widgets_Loader {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		add_action( "elementor/widgets/register", [ $this, "register_widgets" ] );
		add_action( "elementor/frontend/after_register_styles", [ $this, "register_styles" ] );
		add_action( "elementor/frontend/after_register_scripts", [ $this, "register_scripts" ] );
	}

	public function register_widgets( $widgets_manager ) {
		$widgets_dir = __DIR__ . "/widgets/";
		if ( ! is_dir( $widgets_dir ) ) return;

		$files = glob( $widgets_dir . "*.php" );
		if ( ! $files ) return;

		foreach ( $files as $file ) {
			$classes_before = get_declared_classes();
			require_once $file;
			$classes_after = get_declared_classes();
			$new_classes = array_diff( $classes_after, $classes_before );

			foreach ( $new_classes as $class ) {
				if ( class_exists( $class ) && is_subclass_of( $class, "\Elementor\Widget_Base" ) ) {
					$widgets_manager->register( new $class() );
				}
			}
		}
	}

	public function register_styles() {
		$css_dir = __DIR__ . "/assets/css/";
		$css_uri = get_stylesheet_directory_uri() . "/custom-widgets/assets/css/";
		if ( ! is_dir( $css_dir ) ) return;

		$files = glob( $css_dir . "*.css" );
		if ( ! $files ) return;

		$version = wp_get_theme()->get( "Version" );

		foreach ( $files as $file ) {
			$filename = basename( $file );
			$handle   = "ag-widget-" . basename( $filename, ".css" );
			wp_register_style( $handle, $css_uri . $filename, [], $version );
		}
	}

	public function register_scripts() {
		$js_dir = __DIR__ . "/assets/js/";
		$js_uri = get_stylesheet_directory_uri() . "/custom-widgets/assets/js/";
		if ( ! is_dir( $js_dir ) ) return;

		$files = glob( $js_dir . "*.js" );
		if ( ! $files ) return;

		$version = wp_get_theme()->get( "Version" );

		foreach ( $files as $file ) {
			$filename = basename( $file );
			$handle   = "ag-widget-" . basename( $filename, ".js" );
			wp_register_script( $handle, $js_uri . $filename, [ "jquery", "elementor-frontend" ], $version, true );
		}
	}
}

Widgets_Loader::instance();
';
		if ( ! file_exists( $loader_file ) || file_get_contents( $loader_file ) !== $loader_content ) {
			file_put_contents( $loader_file, $loader_content );
		}

		// 3. Inject into functions.php
		if ( file_exists( $functions_file ) ) {
			$functions_content = file_get_contents( $functions_file );
			$require_statement = "require_once get_stylesheet_directory() . '/custom-widgets/widgets-loader.php';";
			
			if ( strpos( $functions_content, 'widgets-loader.php' ) === false ) {
				// Append to functions.php
				$functions_content .= "\n\n// Elementor Custom Widgets Loader\n" . $require_statement . "\n";
				file_put_contents( $functions_file, $functions_content );
			}
		}
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
				if ( substr( $filename, -1 ) === '/' || strpos( $filename, '__MACOSX' ) !== false ) {
					continue;
				}

				$extension = isset( $fileinfo['extension'] ) ? strtolower( $fileinfo['extension'] ) : '';
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

}

Elementor_Custom_Widget_Manager::instance();
