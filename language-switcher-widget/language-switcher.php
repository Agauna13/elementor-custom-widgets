<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Language Switcher Widget (WPML Compatible).
 *
 * @since 1.1.0
 */
class Elementor_Language_Switcher_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rw-language-switcher';
	}

	public function get_title() {
		return 'RW Language Switcher';
	}

	public function get_icon() {
		return 'eicon-globe';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'language', 'switcher', 'menu', 'dropdown', 'wpml', 'translate' ];
	}

public function get_script_depends() {

	wp_register_script(
		'rw-language-switcher-js',
		get_stylesheet_directory_uri() . '/custom-widgets/assets/js/language-switcher.js',
		[ 'jquery' ],
		filemtime( get_stylesheet_directory() . '/custom-widgets/assets/js/language-switcher.js' ),
		true
	);

	return [ 'rw-language-switcher-js' ];
}

public function get_style_depends() {

	wp_register_style(
		'rw-language-switcher-css',
		get_stylesheet_directory_uri() . '/custom-widgets/assets/css/language-switcher.css',
		[],
		filemtime( get_stylesheet_directory() . '/custom-widgets/assets/css/language-switcher.css' )
	);

	return [ 'rw-language-switcher-css' ];
}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'elementor-language-switcher' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'wpml_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'This widget requires WPML to be active and configured.', 'elementor-language-switcher' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'display_flag',
			[
				'label' => esc_html__( 'Show Flag', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor-language-switcher' ),
				'label_off' => esc_html__( 'No', 'elementor-language-switcher' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'display_native_name',
			[
				'label' => esc_html__( 'Show Native Name', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor-language-switcher' ),
				'label_off' => esc_html__( 'No', 'elementor-language-switcher' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'display_translated_name',
			[
				'label' => esc_html__( 'Show Translated Name', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor-language-switcher' ),
				'label_off' => esc_html__( 'No', 'elementor-language-switcher' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'display_code',
			[
				'label' => esc_html__( 'Show Language Code', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor-language-switcher' ),
				'label_off' => esc_html__( 'No', 'elementor-language-switcher' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'dropdown_icon',
			[
				'label' => esc_html__( 'Dropdown Icon', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		
		/* --- Button Style --- */
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'elementor-language-switcher' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'elementor-language-switcher' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-language-switcher' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'elementor-language-switcher' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-language-switcher-wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .els-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-language-switcher' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .els-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .els-button svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'selector' => '{{WRAPPER}} .els-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-language-switcher' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .els-button:hover, {{WRAPPER}} .els-button.active' => 'color: {{VALUE}};',
					'{{WRAPPER}} .els-button:hover svg, {{WRAPPER}} .els-button.active svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'selector' => '{{WRAPPER}} .els-button:hover, {{WRAPPER}} .els-button.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .els-button',
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .els-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .els-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		/* --- Flag Style (Button) --- */
		$this->add_control(
			'heading_flag_style',
			[
				'label' => esc_html__( 'Flag Style', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [ 'display_flag' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'flag_width',
			[
				'label' => esc_html__( 'Flag Width', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [ 'min' => 10, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .els-flag' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'display_flag' => 'yes' ],
			]
		);

		$this->add_control(
			'flag_gap',
			[
				'label' => esc_html__( 'Flag Gap', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .els-flag' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'display_flag' => 'yes' ],
			]
		);
		
		$this->add_control(
			'dropdown_arrow_gap',
			[
				'label' => esc_html__( 'Dropdown Arrow Gap', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .els-button .els-dropdown-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* --- Dropdown Style --- */
		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => esc_html__( 'Dropdown Menu', 'elementor-language-switcher' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'dropdown_width',
			[
				'label' => esc_html__( 'Width', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [ 'min' => 100, 'max' => 500 ],
				],
				'selectors' => [
					'{{WRAPPER}} .els-dropdown' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_position_top',
			[
				'label' => esc_html__( 'Top Offset', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .els-dropdown' => 'top: calc(100% + {{SIZE}}{{UNIT}});',
				],
			]
		);
		
		$this->add_control(
			'dropdown_alignment',
			[
				'label' => esc_html__( 'Alignment', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-language-switcher' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-language-switcher' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'toggle' => false,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'dropdown_background',
				'selector' => '{{WRAPPER}} .els-dropdown',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'dropdown_shadow',
				'selector' => '{{WRAPPER}} .els-dropdown',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_border',
				'selector' => '{{WRAPPER}} .els-dropdown',
			]
		);

		$this->add_responsive_control(
			'dropdown_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .els-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .els-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* --- Items Style --- */
		$this->start_controls_section(
			'section_style_items',
			[
				'label' => esc_html__( 'Dropdown Items', 'elementor-language-switcher' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_typography',
				'selector' => '{{WRAPPER}} .els-item',
			]
		);

		$this->start_controls_tabs( 'tabs_item_style' );

		$this->start_controls_tab(
			'tab_item_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-language-switcher' ),
			]
		);

		$this->add_control(
			'item_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .els-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .els-item svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .els-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_item_hover',
			[
				'label' => esc_html__( 'Hover ( & Active )', 'elementor-language-switcher' ),
			]
		);

		$this->add_control(
			'item_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .els-item:hover, {{WRAPPER}} .els-item.active' => 'color: {{VALUE}};',
					'{{WRAPPER}} .els-item:hover svg, {{WRAPPER}} .els-item.active svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .els-item:hover, {{WRAPPER}} .els-item.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .els-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'item_gap',
			[
				'label' => esc_html__( 'Content Gap (Flag)', 'elementor-language-switcher' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .els-item .els-flag' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// WPML Active Languages Filter
		// Reference: https://wpml.org/wpml-hook/wpml_active_languages/
		$languages = apply_filters( 'wpml_active_languages', null, [
			'skip_missing' => 0,
			'orderby' => 'code',
			'display_names_in_current_lang' => 1, // We will access both native and translated names typically available in the object
		] );

		if ( empty( $languages ) || ! is_array( $languages ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__( 'No languages found. Please check WPML configuration.', 'elementor-language-switcher' ) . '</div>';
			}
			return;
		}

		$current_language = null;
		foreach ( $languages as $code => $lang ) {
			if ( isset( $lang['active'] ) && $lang['active'] ) {
				$current_language = $lang;
				break;
			}
		}

		// Fallback if no active language returned (edge case)
		if ( ! $current_language ) {
			$current_language = reset( $languages );
		}

		$wrapper_classes = [ 'elementor-language-switcher-wrapper' ];
		if ( 'right' === $settings['dropdown_alignment'] ) {
			$wrapper_classes[] = 'align-right';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			
			<button class="els-button" type="button" aria-expanded="false" aria-haspopup="true">
				<span class="els-content">
					<?php $this->render_language_content( $current_language, $settings ); ?>
				</span>
				<span class="els-dropdown-icon">
					<?php \Elementor\Icons_Manager::render_icon( $settings['dropdown_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			</button>

			<div class="els-dropdown" aria-hidden="true">
				<ul class="els-list">
					<?php foreach ( $languages as $code => $lang ) : 
						$is_active = isset( $lang['active'] ) && $lang['active'];
						$item_classes = 'els-item';
						if ( $is_active ) {
							$item_classes .= ' active';
						}
						?>
						<li>
							<a href="<?php echo esc_url( $lang['url'] ); ?>" class="<?php echo esc_attr( $item_classes ); ?>" data-code="<?php echo esc_attr( $code ); ?>">
								<?php $this->render_language_content( $lang, $settings ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Helper to render the content inside button and list items based on settings
	 */
	protected function render_language_content( $lang, $settings ) {
		// Flag
		if ( 'yes' === $settings['display_flag'] && ! empty( $lang['country_flag_url'] ) ) {
			echo '<img src="' . esc_url( $lang['country_flag_url'] ) . '" alt="' . esc_attr( $lang['code'] ) . '" class="els-flag" />';
		}

		// Text Wrapper
		echo '<span class="els-text">';
		
		$parts = [];

		if ( 'yes' === $settings['display_native_name'] ) {
			$parts[] = $lang['native_name'];
		}
		
		// WPML often gives 'translated_name' or just 'name' depending on args.
		// 'translated_name' is usually available if display_names_in_current_lang is set.
		if ( 'yes' === $settings['display_translated_name'] ) {
			// Avoid duplicate if native and translated are same
			if ( ! in_array( $lang['translated_name'], $parts ) ) {
				$parts[] = $lang['translated_name'];
			}
		}

		if ( 'yes' === $settings['display_code'] ) {
			$parts[] = strtoupper( $lang['code'] );
		}

		echo esc_html( implode( ' - ', $parts ) );

		echo '</span>';
	}
}
