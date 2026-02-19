<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Service Card Widget.
 *
 * Elementor widget that displays a service card with a background image,
 * hover effect, and a button.
 *
 * @since 1.0.0
 */
class Elementor_Service_Card_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve service card widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'service_card';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve service card widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Service Card (Ken Burns)', 'elementor-service-card' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve service card widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-box';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the service card widget belongs to.
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
	 * Register service card widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		// Content Tab
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'elementor-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Service Title', 'elementor-service-card' ),
				'placeholder' => esc_html__( 'Type your title here', 'elementor-service-card' ),
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Short description of the service.', 'elementor-service-card' ),
				'placeholder' => esc_html__( 'Type your description here', 'elementor-service-card' ),
			]
		);

		$this->add_control(
			'image_normal',
			[
				'label' => esc_html__( 'Normal Image', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'image_hover',
			[
				'label' => esc_html__( 'Hover Image', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Learn More', 'elementor-service-card' ),
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Button Link', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-service-card' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Button Icon', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-service-card' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-service-card' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .service-card-btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .service-card-btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Container
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'elementor-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_height',
			[
				'label' => esc_html__( 'Min Height', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .service-card-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .service-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .service-card-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'elementor-service-card' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'elementor-service-card' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'elementor-service-card' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .service-card-content' => 'justify-content: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'text_alignment',
			[
				'label' => esc_html__( 'Text Alignment', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-service-card' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-service-card' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-service-card' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .service-card-content' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
					'{{WRAPPER}} .service-card-wrapper' => 'text-align: {{VALUE}};', // Fallback
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => esc_html__( 'Gap', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .service-card-content' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_container_style' );

		$this->start_controls_tab(
			'tab_container_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-service-card' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .service-card-wrapper',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .service-card-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_container_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-service-card' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border_hover',
				'selector' => '{{WRAPPER}} .service-card-wrapper:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow_hover',
				'selector' => '{{WRAPPER}} .service-card-wrapper:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Style Tab - Background & Overlay
		$this->start_controls_section(
			'section_style_background',
			[
				'label' => esc_html__( 'Background & Effects', 'elementor-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Overlay Color (Normal)', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.3)',
				'selectors' => [
					'{{WRAPPER}} .service-card-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_color_hover',
			[
				'label' => esc_html__( 'Overlay Color (Hover)', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .service-card-wrapper:hover .service-card-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ken_burns_scale',
			[
				'label' => esc_html__( 'Zoom Scale', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1.2,
				],
				'selectors' => [
					'{{WRAPPER}} .service-card-wrapper:hover .service-card-bg' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->add_control(
			'transition_duration',
			[
				'label' => esc_html__( 'Transition Duration (s)', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .service-card-bg, {{WRAPPER}} .service-card-overlay' => 'transition: all {{SIZE}}s ease-in-out;',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Typography
		$this->start_controls_section(
			'section_style_typography',
			[
				'label' => esc_html__( 'Typography', 'elementor-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .service-card-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .service-card-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .service-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_desc',
			[
				'label' => esc_html__( 'Description', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'selector' => '{{WRAPPER}} .service-card-desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Color', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f0f0f0',
				'selectors' => [
					'{{WRAPPER}} .service-card-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .service-card-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Button
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'elementor-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .service-card-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-service-card' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .service-card-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#0073e6',
				'selectors' => [
					'{{WRAPPER}} .service-card-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .service-card-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-service-card' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .service-card-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#005bb5',
				'selectors' => [
					'{{WRAPPER}} .service-card-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .service-card-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .service-card-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .service-card-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render service card widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'service-card-wrapper' );
		
		// Button Attributes
		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
			$this->add_render_attribute( 'button', 'class', 'service-card-btn' );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="service-card-bg normal" style="background-image: url('<?php echo esc_url( $settings['image_normal']['url'] ); ?>');"></div>
			<div class="service-card-bg hover" style="background-image: url('<?php echo esc_url( $settings['image_hover']['url'] ); ?>');"></div>
			<div class="service-card-overlay"></div>
			
			<div class="service-card-content">
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<h3 class="service-card-title"><?php echo esc_html( $settings['title'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<div class="service-card-desc"><?php echo wp_kses_post( $settings['description'] ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php
						if ( ! empty( $settings['button_icon']['value'] ) && 'left' === $settings['button_icon_align'] ) {
							\Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true', 'class' => 'service-card-btn-icon-left' ] );
						}
						?>
						<?php echo esc_html( $settings['button_text'] ); ?>
						<?php
						if ( ! empty( $settings['button_icon']['value'] ) && 'right' === $settings['button_icon_align'] ) {
							\Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true', 'class' => 'service-card-btn-icon-right' ] );
						}
						?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
