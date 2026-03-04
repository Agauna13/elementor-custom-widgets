<?php
namespace AG_Widgets\Widgets;

// Evitar ejecución directa
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AG_Masonry_Grid_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ag_masonry_grid';
	}

	public function get_title() {
		return __( 'AG Masonry Grid', 'textdomain' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * D) Convención de handles:
	 * El archivo es /custom-widgets/assets/css/ag-masonry-grid.css
	 * Retornamos exactamente: "ag-widget-ag-masonry-grid"
	 */
	public function get_style_depends() {
		return [ 'ag-widget-ag-masonry-grid' ];
	}

	/**
	 * D) Convención de handles para JS
	 * El archivo es /custom-widgets/assets/js/ag-masonry-grid.js
	 * Retornamos exactamente: "ag-widget-ag-masonry-grid"
	 */
	public function get_script_depends() {
		return [ 'ag-widget-ag-masonry-grid' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'textdomain' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => __( 'Title', 'textdomain' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'My Masonry Grid Title', 'textdomain' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ag-masonry-grid-wrapper">
			<h3 class="ag-masonry-title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<div class="ag-masonry-grid-content">
				<!-- Contenido de ejemplo para el Masonry Grid -->
				<div class="ag-masonry-item">Item 1</div>
				<div class="ag-masonry-item">Item 2</div>
				<div class="ag-masonry-item">Item 3</div>
			</div>
		</div>
		<?php
	}
}
