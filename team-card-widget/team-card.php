<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Team Card Widget.
 *
 * Elementor widget that displays a team member card with a background image,
 * hover effect, name, position, description, and social icons.
 *
 * @since 1.0.0
 */
class Elementor_Team_Card_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		return [ 'team-card-style' ];
	}

	public function get_script_depends() {
		return [ 'team-card-script' ];
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve team card widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'team_card';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve team card widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Team Card', 'elementor-team-card' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve team card widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the team card widget belongs to.
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
	 * Register team card widget controls.
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
				'label' => esc_html__( 'Content', 'elementor-team-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'John Doe', 'elementor-team-card' ),
				'placeholder' => esc_html__( 'Type name here', 'elementor-team-card' ),
			]
		);

		$this->add_control(
			'position',
			[
				'label' => esc_html__( 'Position', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'CEO & Founder', 'elementor-team-card' ),
				'placeholder' => esc_html__( 'Type position here', 'elementor-team-card' ),
			]
		);

		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Passionate about leading teams and building great products.', 'elementor-team-card' ),
				'placeholder' => esc_html__( 'Type description here', 'elementor-team-card' ),
			]
		);

		$this->add_control(
			'image_normal',
			[
				'label' => esc_html__( 'Normal Image', 'elementor-team-card' ),
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
				'label' => esc_html__( 'Hover Image', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-facebook',
					'library' => 'fa-brands',
				],
			]
		);

		$repeater->add_control(
			'social_link',
			[
				'label' => esc_html__( 'Link', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-social-link.com', 'elementor-team-card' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'social_icons',
			[
				'label' => esc_html__( 'Social Icons', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'social_icon' => [
							'value' => 'fab fa-facebook',
							'library' => 'fa-brands',
						],
						'social_link' => [
							'url' => '#',
						],
					],
					[
						'social_icon' => [
							'value' => 'fab fa-twitter',
							'library' => 'fa-brands',
						],
						'social_link' => [
							'url' => '#',
						],
					],
					[
						'social_icon' => [
							'value' => 'fab fa-linkedin',
							'library' => 'fa-brands',
						],
						'social_link' => [
							'url' => '#',
						],
					],
				],
				'title_field' => '{{{ social_icon.value }}}',
			]
		);

		$this->add_responsive_control(
			'social_icons_align',
			[
				'label' => esc_html__( 'Social Icons Alignment', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'elementor-team-card' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-team-card' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'elementor-team-card' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icons' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Container
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'elementor-team-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_height',
			[
				'label' => esc_html__( 'Min Height', 'elementor-team-card' ),
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
					'{{WRAPPER}} .team-card-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .team-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-card-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Vertical Alignment', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'elementor-team-card' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'elementor-team-card' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'elementor-team-card' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .team-card-content' => 'justify-content: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'text_alignment',
			[
				'label' => esc_html__( 'Text Alignment', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-team-card' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-team-card' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-team-card' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .team-card-content' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
					'{{WRAPPER}} .team-card-wrapper' => 'text-align: {{VALUE}};', // Fallback
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => esc_html__( 'Gap', 'elementor-team-card' ),
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
					'{{WRAPPER}} .team-card-content' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_container_style' );

		$this->start_controls_tab(
			'tab_container_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-team-card' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .team-card-wrapper',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .team-card-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_container_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-team-card' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border_hover',
				'selector' => '{{WRAPPER}} .team-card-wrapper:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow_hover',
				'selector' => '{{WRAPPER}} .team-card-wrapper:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Style Tab - Background & Overlay
		$this->start_controls_section(
			'section_style_background',
			[
				'label' => esc_html__( 'Background & Effects', 'elementor-team-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Overlay Color (Normal)', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.3)',
				'selectors' => [
					'{{WRAPPER}} .team-card-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_color_hover',
			[
				'label' => esc_html__( 'Overlay Color (Hover)', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .team-card-wrapper:hover .team-card-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ken_burns_scale',
			[
				'label' => esc_html__( 'Zoom Scale', 'elementor-team-card' ),
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
					'{{WRAPPER}} .team-card-wrapper:hover .team-card-bg' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->add_control(
			'transition_duration',
			[
				'label' => esc_html__( 'Transition Duration (s)', 'elementor-team-card' ),
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
					'{{WRAPPER}} .team-card-bg, {{WRAPPER}} .team-card-overlay' => 'transition: all {{SIZE}}s ease-in-out;',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Typography
		$this->start_controls_section(
			'section_style_typography',
			[
				'label' => esc_html__( 'Typography', 'elementor-team-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Name
		$this->add_control(
			'heading_name',
			[
				'label' => esc_html__( 'Name', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .team-card-name',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .team-card-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'name_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .team-card-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Position
		$this->add_control(
			'heading_position',
			[
				'label' => esc_html__( 'Position', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'position_typography',
				'selector' => '{{WRAPPER}} .team-card-position',
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' => esc_html__( 'Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e0e0e0',
				'selectors' => [
					'{{WRAPPER}} .team-card-position' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'position_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .team-card-position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Description
		$this->add_control(
			'heading_desc',
			[
				'label' => esc_html__( 'Description', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'selector' => '{{WRAPPER}} .team-card-desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f0f0f0',
				'selectors' => [
					'{{WRAPPER}} .team-card-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .team-card-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Social Icons
		$this->start_controls_section(
			'section_style_social',
			[
				'label' => esc_html__( 'Social Icons', 'elementor-team-card' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'social_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .team-card-social-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icon_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icon_gap',
			[
				'label' => esc_html__( 'Spacing', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icons' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_social_style' );

		$this->start_controls_tab(
			'tab_social_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-team-card' ),
			]
		);

		$this->add_control(
			'social_color',
			[
				'label' => esc_html__( 'Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .team-card-social-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'social_border',
				'selector' => '{{WRAPPER}} .team-card-social-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-team-card' ),
			]
		);

		$this->add_control(
			'social_color_hover',
			[
				'label' => esc_html__( 'Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#0073e6',
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .team-card-social-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'social_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'social_border_hover',
				'selector' => '{{WRAPPER}} .team-card-social-icon:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'social_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-team-card' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-card-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render team card widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Register dependencies
		wp_register_style( 'team-card-style', plugins_url( 'team-card.css', __FILE__ ) );
		wp_register_script( 'team-card-script', plugins_url( 'team-card.js', __FILE__ ), [ 'jquery' ], '1.0.0', true );

		wp_enqueue_style( 'team-card-style' );
		wp_enqueue_script( 'team-card-script' );

		$this->add_render_attribute( 'wrapper', 'class', 'team-card-wrapper' );
		
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="team-card-bg normal" style="background-image: url('<?php echo esc_url( $settings['image_normal']['url'] ); ?>');"></div>
			<div class="team-card-bg hover" style="background-image: url('<?php echo esc_url( $settings['image_hover']['url'] ); ?>');"></div>
			<div class="team-card-overlay"></div>
			
			<div class="team-card-content">
				<?php if ( ! empty( $settings['name'] ) ) : ?>
					<h3 class="team-card-name"><?php echo esc_html( $settings['name'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $settings['position'] ) ) : ?>
					<p class="team-card-position"><?php echo esc_html( $settings['position'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<div class="team-card-desc"><?php echo wp_kses_post( $settings['description'] ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $settings['social_icons'] ) ) : ?>
					<div class="team-card-social-icons">
						<?php foreach ( $settings['social_icons'] as $index => $item ) : 
							$link_key = 'social_link_' . $index;
							if ( ! empty( $item['social_link']['url'] ) ) {
								$this->add_link_attributes( $link_key, $item['social_link'] );
							}
							$this->add_render_attribute( $link_key, 'class', 'team-card-social-icon' );
							?>
							<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
								<?php \Elementor\Icons_Manager::render_icon( $item['social_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
