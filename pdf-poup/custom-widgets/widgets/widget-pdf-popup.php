<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enterprise PDF Popup Widget.
 *
 * Elementor widget that displays a PDF in a popup modal.
 *
 * @since 1.0.0
 */
class Elementor_Enterprise_Pdf_Popup extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'enterprise-pdf-popup';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Enterprise PDF Popup', 'elementor-custom-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-file-download';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		// Content Tab: Trigger
		$this->start_controls_section(
			'section_trigger',
			[
				'label' => esc_html__( 'Trigger Settings', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'trigger_type',
			[
				'label' => esc_html__( 'Trigger Type', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button' => esc_html__( 'Button', 'elementor-custom-widgets' ),
					'text' => esc_html__( 'Text / Link', 'elementor-custom-widgets' ),
				],
			]
		);

		$this->add_control(
			'trigger_text',
			[
				'label' => esc_html__( 'Trigger Text', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Open PDF', 'elementor-custom-widgets' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'trigger_alignment',
			[
				'label' => esc_html__( 'Alignment', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-custom-widgets' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-custom-widgets' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-custom-widgets' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Content Tab: Popup Content
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Popup Content', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pdf_url',
			[
				'label' => esc_html__( 'PDF URL', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-site.com/file.pdf', 'elementor-custom-widgets' ),
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'modal_title',
			[
				'label' => esc_html__( 'Modal Title', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Document Preview', 'elementor-custom-widgets' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		// Content Tab: Settings
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Behavior Settings', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_overlay',
			[
				'label' => esc_html__( 'Show Overlay', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor-custom-widgets' ),
				'label_off' => esc_html__( 'No', 'elementor-custom-widgets' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'close_on_esc',
			[
				'label' => esc_html__( 'Close on ESC Key', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'close_on_overlay_click',
			[
				'label' => esc_html__( 'Close on Overlay Click', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'lock_body_scroll',
			[
				'label' => esc_html__( 'Lock Body Scroll', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Style Tab: Trigger
		$this->start_controls_section(
			'section_style_trigger',
			[
				'label' => esc_html__( 'Trigger', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'trigger_typography',
				'selector' => '{{WRAPPER}} .ep-pdf-trigger',
			]
		);

		$this->start_controls_tabs( 'tabs_trigger_style' );

		$this->start_controls_tab(
			'tab_trigger_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-custom-widgets' ),
			]
		);

		$this->add_control(
			'trigger_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'trigger_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_trigger_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-custom-widgets' ),
			]
		);

		$this->add_control(
			'trigger_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'trigger_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'trigger_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'trigger_border',
				'selector' => '{{WRAPPER}} .ep-pdf-trigger',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'trigger_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'trigger_box_shadow',
				'selector' => '{{WRAPPER}} .ep-pdf-trigger',
			]
		);

		$this->add_responsive_control(
			'trigger_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab: Modal
		$this->start_controls_section(
			'section_style_modal',
			[
				'label' => esc_html__( 'Modal Window', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'modal_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-modal' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'modal_width',
			[
				'label' => esc_html__( 'Width', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 90,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-modal' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'modal_max_width',
			[
				'label' => esc_html__( 'Max Width', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1920,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1000,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-modal' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'modal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'modal_box_shadow',
				'selector' => '{{WRAPPER}} .ep-pdf-modal',
			]
		);

		$this->add_control(
			'header_separator',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'modal_title_color',
			[
				'label' => esc_html__( 'Title Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-modal-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ep-pdf-close-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'modal_title_typography',
				'selector' => '{{WRAPPER}} .ep-pdf-modal-title',
			]
		);

		$this->end_controls_section();

		// Style Tab: PDF Viewer
		$this->start_controls_section(
			'section_style_viewer',
			[
				'label' => esc_html__( 'PDF Viewer', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'viewer_height',
			[
				'label' => esc_html__( 'Height', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
					'vh' => [
						'min' => 10,
						'max' => 95,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-iframe' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab: Overlay
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'elementor-custom-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.5)',
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_backdrop_filter',
			[
				'label' => esc_html__( 'Backdrop Blur', 'elementor-custom-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-pdf-overlay' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id = $this->get_id();

		// Attributes for the trigger
		$this->add_render_attribute( 'trigger', 'class', 'ep-pdf-trigger' );
		$this->add_render_attribute( 'trigger', 'class', 'ep-pdf-trigger-' . $settings['trigger_type'] );
		$this->add_render_attribute( 'trigger', 'role', 'button' );
		$this->add_render_attribute( 'trigger', 'tabindex', '0' );
		$this->add_render_attribute( 'trigger', 'aria-haspopup', 'dialog' );
		$this->add_render_attribute( 'trigger', 'aria-controls', 'ep-pdf-modal-' . $id );
		$this->add_render_attribute( 'trigger', 'aria-expanded', 'false' );

		// Attributes for the modal wrapper
		$this->add_render_attribute( 'modal-wrapper', 'id', 'ep-pdf-modal-' . $id );
		$this->add_render_attribute( 'modal-wrapper', 'class', 'ep-pdf-modal-wrapper' );
		$this->add_render_attribute( 'modal-wrapper', 'role', 'dialog' );
		$this->add_render_attribute( 'modal-wrapper', 'aria-modal', 'true' );
		$this->add_render_attribute( 'modal-wrapper', 'aria-hidden', 'true' );
		$this->add_render_attribute( 'modal-wrapper', 'aria-labelledby', 'ep-pdf-title-' . $id );

		// Data attributes for JS behavior
		$this->add_render_attribute( 'modal-wrapper', 'data-close-esc', $settings['close_on_esc'] );
		$this->add_render_attribute( 'modal-wrapper', 'data-close-click', $settings['close_on_overlay_click'] );
		$this->add_render_attribute( 'modal-wrapper', 'data-lock-scroll', $settings['lock_body_scroll'] );

		/* PDF URL handling */
		$pdf_url = $settings['pdf_url']['url'];
		?>

		<div class="ep-pdf-trigger-wrapper">
			<div <?php echo $this->get_render_attribute_string( 'trigger' ); ?>>
				<?php echo esc_html( $settings['trigger_text'] ); ?>
			</div>
		</div>

		<div <?php echo $this->get_render_attribute_string( 'modal-wrapper' ); ?>>
			<?php if ( 'yes' === $settings['show_overlay'] ) : ?>
				<div class="ep-pdf-overlay"></div>
			<?php endif; ?>

			<div class="ep-pdf-modal">
				<div class="ep-pdf-modal-header">
					<h3 id="ep-pdf-title-<?php echo esc_attr( $id ); ?>" class="ep-pdf-modal-title">
						<?php echo esc_html( $settings['modal_title'] ); ?>
					</h3>
					<button class="ep-pdf-close-btn" aria-label="<?php esc_attr_e( 'Close', 'elementor-custom-widgets' ); ?>">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="ep-pdf-modal-content">
					<?php if ( ! empty( $pdf_url ) ) : ?>
						<iframe src="<?php echo esc_url( $pdf_url ); ?>" class="ep-pdf-iframe" title="<?php echo esc_attr( $settings['modal_title'] ); ?>" frameborder="0"></iframe>
					<?php else : ?>
						<div class="ep-pdf-placeholder">
							<?php esc_html_e( 'Please select a PDF file.', 'elementor-custom-widgets' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div class="ep-pdf-trigger-wrapper">
			<div class="ep-pdf-trigger ep-pdf-trigger-{{ settings.trigger_type }}" role="button" tabindex="0">
				{{{ settings.trigger_text }}}
			</div>
		</div>

		<div class="ep-pdf-modal-wrapper ep-pdf-editor-preview">
			<# if ( 'yes' === settings.show_overlay ) { #>
				<div class="ep-pdf-overlay"></div>
			<# } #>

			<div class="ep-pdf-modal">
				<div class="ep-pdf-modal-header">
					<h3 class="ep-pdf-modal-title">
						{{{ settings.modal_title }}}
					</h3>
					<button class="ep-pdf-close-btn">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="ep-pdf-modal-content">
					<# if ( settings.pdf_url && settings.pdf_url.url ) { #>
						<iframe src="{{ settings.pdf_url.url }}" class="ep-pdf-iframe" frameborder="0"></iframe>
					<# } else { #>
						<div class="ep-pdf-placeholder">
							Please select a PDF file.
						</div>
					<# } #>
				</div>
			</div>
		</div>
		<?php
	}
}
