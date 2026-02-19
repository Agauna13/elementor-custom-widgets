<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Hover Service Card Widget.
 *
 * Elementor widget that displays a service card with a background image,
 * dynamic labels, and a unique hover effect where the button rises to the center.
 *
 * @since 1.0.0
 */
class Elementor_Hover_Service_Card_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'hover_service_card';
	}

	public function get_title() {
		return esc_html__( 'Hover Service Card', 'elementor-hover-service-card' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_script_depends() {
		return [ 'hover-service-card-script' ];
	}

	public function get_style_depends() {
		return [ 'hover-service-card-style' ];
	}
	
	protected function register_controls() {

		// Content Tab
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'elementor-hover-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);



		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Background Image', 'elementor-hover-service-card' ),
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
				'label' => esc_html__( 'Button Text', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Learn More', 'elementor-hover-service-card' ),
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Button Link', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-hover-service-card' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Button Icon', 'elementor-hover-service-card' ),
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
				'label' => esc_html__( 'Icon Position', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-hover-service-card' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-hover-service-card' ),
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
				'label' => esc_html__( 'Icon Spacing', 'elementor-hover-service-card' ),
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
					'{{WRAPPER}} .hover-service-card-btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hover-service-card-btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Container
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'elementor-hover-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_height',
			[
				'label' => esc_html__( 'Height', 'elementor-hover-service-card' ),
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
					'{{WRAPPER}} .hover-service-card-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_vertical_align',
			[
				'label' => esc_html__( 'Content Vertical Align', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'elementor-hover-service-card' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'elementor-hover-service-card' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'elementor-hover-service-card' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-content' => 'justify-content: {{VALUE}};',
				],
			]
		);
		


		$this->start_controls_tabs( 'tabs_container_style' );

		$this->start_controls_tab(
			'tab_container_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-hover-service-card' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .hover-service-card-wrapper',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .hover-service-card-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_container_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-hover-service-card' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border_hover',
				'selector' => '{{WRAPPER}} .hover-service-card-wrapper:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow_hover',
				'selector' => '{{WRAPPER}} .hover-service-card-wrapper:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Style Tab - Background & Overlay
		$this->start_controls_section(
			'section_style_background',
			[
				'label' => esc_html__( 'Background & Effects', 'elementor-hover-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Overlay Color (Normal)', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.3)',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_color_hover',
			[
				'label' => esc_html__( 'Overlay Color (Hover)', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.6)',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-wrapper:hover .hover-service-card-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'transition_duration',
			[
				'label' => esc_html__( 'Transition Duration (s)', 'elementor-hover-service-card' ),
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
					'{{WRAPPER}} .hover-service-card-bg, {{WRAPPER}} .hover-service-card-overlay, {{WRAPPER}} .hover-service-card-btn-wrapper, {{WRAPPER}} .hover-service-card-content' => 'transition: all {{SIZE}}s ease-in-out;',
				],
			]
		);

		$this->add_control(
			'ken_burns_scale',
			[
				'label' => esc_html__( 'Background Zoom Scale', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1.1,
				],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-wrapper:hover .hover-service-card-bg' => 'transform: scale({{SIZE}});',
				],
			]
		);
		
		$this->add_control(
			'content_opacity_hover',
			[
				'label' => esc_html__( 'Content Opacity on Hover', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-wrapper:hover .hover-service-card-content' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();



		// Style Tab - Button
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'elementor-hover-service-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Width', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn' => 'width: {{SIZE}}{{UNIT}}; justify-content: center;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .hover-service-card-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-hover-service-card' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#0073e6',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .hover-service-card-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-hover-service-card' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#005bb5',
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .hover-service-card-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-hover-service-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .hover-service-card-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Register dependencies
		wp_register_style( 'hover-service-card-style', plugins_url( 'hover-service-card.css', __FILE__ ) );
		wp_register_script( 'hover-service-card-script', plugins_url( 'hover-service-card.js', __FILE__ ), [ 'jquery' ], '1.0.0', true );

		wp_enqueue_style( 'hover-service-card-style' );
		wp_enqueue_script( 'hover-service-card-script' );

		// Wrapper Tag & Link Logic
		$wrapper_tag = 'div';
		$button_tag = 'span'; // Button text becomes just a span since the container is the link

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$wrapper_tag = 'a';
			$this->add_link_attributes( 'wrapper', $settings['button_link'] );
		}

		$this->add_render_attribute( 'wrapper', 'class', 'hover-service-card-wrapper' );
		$this->add_render_attribute( 'button', 'class', 'hover-service-card-btn' );

		?>
		<<?php echo $wrapper_tag; ?> <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="hover-service-card-bg" style="background-image: url('<?php echo esc_url( $settings['image']['url'] ); ?>');"></div>
			<div class="hover-service-card-overlay"></div>
			

			<div class="hover-service-card-btn-wrapper">
				<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<<?php echo $button_tag; ?> <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php
						if ( ! empty( $settings['button_icon']['value'] ) && 'left' === $settings['button_icon_align'] ) {
							\Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true', 'class' => 'hover-service-card-btn-icon-left' ] );
						}
						?>
						<?php echo esc_html( $settings['button_text'] ); ?>
						<?php
						if ( ! empty( $settings['button_icon']['value'] ) && 'right' === $settings['button_icon_align'] ) {
							\Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true', 'class' => 'hover-service-card-btn-icon-right' ] );
						}
						?>
					</<?php echo $button_tag; ?>>
				<?php endif; ?>
			</div>
		</<?php echo $wrapper_tag; ?>>
		<?php
	}
}
