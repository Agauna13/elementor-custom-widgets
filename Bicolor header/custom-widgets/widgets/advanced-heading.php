<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Child_Theme_Advanced_Heading_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'child_theme_advanced_heading';
	}

	public function get_title() {
		return esc_html__( 'Advanced Heading (Child)', 'renax-child' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'heading', 'title', 'advanced', 'dual color', 'custom' ];
	}

	protected function register_controls() {

		// Content Tab
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'renax-child' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pre_title',
			[
				'label' => esc_html__( 'Pre Title', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Welcome to', 'renax-child' ),
				'placeholder' => esc_html__( 'Type your pre title here', 'renax-child' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_part_1',
			[
				'label' => esc_html__( 'Main Title - Part 1', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Your', 'renax-child' ),
				'placeholder' => esc_html__( 'First part of title', 'renax-child' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_part_2',
			[
				'label' => esc_html__( 'Main Title - Part 2', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Website', 'renax-child' ),
				'placeholder' => esc_html__( 'Second part of title', 'renax-child' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'renax-child' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'renax-child' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'renax-child' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'renax-child' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ors-adv-heading-wrapper' => 'text-align: {{VALUE}};',
				],
				'prefix_class' => 'elementor-align-',
			]
		);

		$this->end_controls_section();

		// Style Tab - Pre Title
		$this->start_controls_section(
			'section_style_pre_title',
			[
				'label' => esc_html__( 'Pre Title', 'renax-child' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'pre_title!' => '',
				],
			]
		);

		$this->add_control(
			'pre_title_color',
			[
				'label' => esc_html__( 'Text Color', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ors-adv-pre-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'pre_title_typography',
				'selector' => '{{WRAPPER}} .ors-adv-pre-title',
			]
		);

		$this->add_responsive_control(
			'pre_title_margin_bottom',
			[
				'label' => esc_html__( 'Spacing', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ors-adv-pre-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Main Title
		$this->start_controls_section(
			'section_style_main_title',
			[
				'label' => esc_html__( 'Main Title', 'renax-child' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'renax-child' ),
				'selector' => '{{WRAPPER}} .ors-adv-main-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ors-adv-main-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ors-adv-main-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_part_1',
			[
				'label' => esc_html__( 'Part 1 Style', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_part_1_color',
			[
				'label' => esc_html__( 'Color Example', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ors-adv-main-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_part_2',
			[
				'label' => esc_html__( 'Part 2 Style', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_part_2_color',
			[
				'label' => esc_html__( 'Color Example', 'renax-child' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ors-adv-title-part-2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'label' => esc_html__( 'Text Shadow', 'renax-child' ),
				'selector' => '{{WRAPPER}} .ors-adv-main-title',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$pre_title = $settings['pre_title'];
		$title_part_1 = $settings['title_part_1'];
		$title_part_2 = $settings['title_part_2'];
		$header_tag = \Elementor\Utils::validate_html_tag( $settings['header_size'] );

		$this->add_render_attribute( 'wrapper', 'class', 'ors-adv-heading-wrapper' );
		
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $pre_title ) ) : ?>
				<div class="ors-adv-pre-title"><?php echo esc_html( $pre_title ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $title_part_1 ) || ! empty( $title_part_2 ) ) : ?>
				<<?php echo $header_tag; ?> class="ors-adv-main-title">
					<?php if ( ! empty( $title_part_1 ) ) echo esc_html( $title_part_1 ); ?><?php if ( ! empty( $title_part_1 ) && ! empty( $title_part_2 ) ) echo ' '; ?><?php if ( ! empty( $title_part_2 ) ) : ?><span class="ors-adv-title-part-2"><?php echo esc_html( $title_part_2 ); ?></span><?php endif; ?>
				</<?php echo $header_tag; ?>>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		var header_tag = elementor.helpers.validateHTMLTag( settings.header_size );
		#>
		<div class="ors-adv-heading-wrapper">
			<# if ( settings.pre_title ) { #>
				<div class="ors-adv-pre-title">{{{ settings.pre_title }}}</div>
			<# } #>

			<# if ( settings.title_part_1 || settings.title_part_2 ) { #>
				<{{ header_tag }} class="ors-adv-main-title">
					{{{ settings.title_part_1 }}}<# if ( settings.title_part_1 && settings.title_part_2 ) { #> <# } #><# if ( settings.title_part_2 ) { #><span class="ors-adv-title-part-2">{{{ settings.title_part_2 }}}</span><# } #>
				</{{ header_tag }}>
			<# } #>
		</div>
		<?php
	}
}
