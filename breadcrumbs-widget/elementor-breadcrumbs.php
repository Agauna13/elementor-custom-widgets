<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Breadcrumbs Widget.
 *
 * Elementor widget that displays breadcrumbs with extensive customization options.
 *
 * @since 1.0.0
 */
class Elementor_Breadcrumbs_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'elementor_breadcrumbs';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Advanced Breadcrumbs', 'elementor-breadcrumbs' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'breadcrumbs', 'navigation', 'menu', 'path' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		// Enqueue script if it hasn't been enqueued yet.
		// Note: The loader typically handles enqueuing, but for a standalone export, proper registration is key.
		// Given the user constraint of a self-contained folder, we register it here if needed.
		wp_register_script( 'elementor-breadcrumbs-js', plugin_dir_url( __FILE__ ) . 'elementor-breadcrumbs.js', [ 'jquery' ], '1.0.0', true );
		return [ 'elementor-breadcrumbs-js' ];
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		wp_register_style( 'elementor-breadcrumbs-css', plugin_dir_url( __FILE__ ) . 'elementor-breadcrumbs.css', [], '1.0.0' );
		return [ 'elementor-breadcrumbs-css' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register Content Controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Broadcrumbs Settings', 'elementor-breadcrumbs' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'source',
			[
				'label' => esc_html__( 'Source', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto' => esc_html__( 'Auto (WordPress Hierarchy)', 'elementor-breadcrumbs' ),
					'manual' => esc_html__( 'Manual (Custom Links)', 'elementor-breadcrumbs' ),
				],
			]
		);

		/* --- Auto Mode Settings --- */

		$this->add_control(
			'show_home',
			[
				'label' => esc_html__( 'Show Home', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor-breadcrumbs' ),
				'label_off' => esc_html__( 'No', 'elementor-breadcrumbs' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'source' => 'auto',
				],
			]
		);

		$this->add_control(
			'home_text',
			[
				'label' => esc_html__( 'Home Text', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Home', 'elementor-breadcrumbs' ),
				'condition' => [
					'source' => 'auto',
					'show_home' => 'yes',
				],
			]
		);

		$this->add_control(
			'home_icon',
			[
				'label' => esc_html__( 'Home Icon', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => [
					'source' => 'auto',
					'show_home' => 'yes',
				],
			]
		);

		/* --- Manual Mode Settings --- */

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Item', 'elementor-breadcrumbs' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-breadcrumbs' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::ICONS,
			]
		);

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Breadcrumb Items', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => esc_html__( 'Home', 'elementor-breadcrumbs' ),
						'link' => [ 'url' => '/' ],
					],
					[
						'text' => esc_html__( 'Category', 'elementor-breadcrumbs' ),
					],
					[
						'text' => esc_html__( 'Current Page', 'elementor-breadcrumbs' ),
					],
				],
				'title_field' => '{{{ text }}}',
				'condition' => [
					'source' => 'manual',
				],
			]
		);

		$this->end_controls_section();

		/* --- Separator Settings --- */
		
		$this->start_controls_section(
			'section_separator',
			[
				'label' => esc_html__( 'Separator', 'elementor-breadcrumbs' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'separator_type',
			[
				'label' => esc_html__( 'Separator Type', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__( 'Icon', 'elementor-breadcrumbs' ),
					'text' => esc_html__( 'Text', 'elementor-breadcrumbs' ),
				],
			]
		);

		$this->add_control(
			'separator_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'separator_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'separator_text',
			[
				'label' => esc_html__( 'Text', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '/',
				'condition' => [
					'separator_type' => 'text',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Controls
	 */
	protected function register_style_controls() {
		
		/* --- Container Style --- */

		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'elementor-breadcrumbs' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'elementor-breadcrumbs' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-breadcrumbs' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'elementor-breadcrumbs' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-container' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'elementor-breadcrumbs' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-breadcrumbs-container',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'label' => esc_html__( 'Border', 'elementor-breadcrumbs' ),
				'selector' => '{{WRAPPER}} .elementor-breadcrumbs-container',
			]
		);

		$this->add_responsive_control(
			'container_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* --- Items Style --- */

		$this->start_controls_section(
			'section_style_items',
			[
				'label' => esc_html__( 'Items', 'elementor-breadcrumbs' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'items_typography',
				'selector' => '{{WRAPPER}} .elementor-breadcrumbs-item',
			]
		);

		$this->start_controls_tabs( 'tabs_items_style' );

		$this->start_controls_tab(
			'tab_items_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-breadcrumbs' ),
			]
		);

		$this->add_control(
			'item_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-item, {{WRAPPER}} .elementor-breadcrumbs-item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_items_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-breadcrumbs' ),
			]
		);

		$this->add_control(
			'item_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-item:hover, {{WRAPPER}} .elementor-breadcrumbs-item a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Icon Color', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-item:hover .elementor-breadcrumbs-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_spacing',
			[
				'label' => esc_html__( 'Spacing between items', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* --- Separator Style --- */

		$this->start_controls_section(
			'section_style_separator',
			[
				'label' => esc_html__( 'Separator', 'elementor-breadcrumbs' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Color', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-separator' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label' => esc_html__( 'Size', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-separator' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-breadcrumbs-separator svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'separator_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-separator' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* --- Current Item Style --- */

		$this->start_controls_section(
			'section_style_current',
			[
				'label' => esc_html__( 'Current Item', 'elementor-breadcrumbs' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'current_item_color',
			[
				'label' => esc_html__( 'Color', 'elementor-breadcrumbs' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-breadcrumbs-item.current-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'current_item_typography',
				'selector' => '{{WRAPPER}} .elementor-breadcrumbs-item.current-item',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$source = $settings['source'];
		$items = [];

		// Generate Items based on source
		if ( 'auto' === $source ) {
			$items = $this->get_auto_breadcrumbs( $settings );
		} else {
			$items = $settings['items'];
		}

		if ( empty( $items ) ) {
			return;
		}

		echo '<nav class="elementor-breadcrumbs-container" aria-label="Breadcrumb">';
		echo '<ol class="elementor-breadcrumbs-list">';

		$total_items = count( $items );
		$counter = 0;

		foreach ( $items as $index => $item ) {
			$counter++;
			$is_last = ( $counter === $total_items );
			$item_class = 'elementor-breadcrumbs-item';
			if ( $is_last ) $item_class .= ' current-item';
			
			// Determine text and link
			$text = isset( $item['text'] ) ? $item['text'] : '';
			$link = isset( $item['link']['url'] ) ? $item['link']['url'] : '';
			$icon = isset( $item['icon'] ) ? $item['icon'] : null;

			echo '<li class="' . esc_attr( $item_class ) . '">';
				
				// Item Content
				if ( ! empty( $link ) && ! $is_last ) {
					echo '<a href="' . esc_url( $link ) . '">';
				} else {
					echo '<span>';
				}

				// Icon
				if ( ! empty( $icon ) && ! empty( $icon['value'] ) ) {
					echo '<span class="elementor-breadcrumbs-icon">';
					\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
					echo '</span> ';
				}

				echo esc_html( $text );

				if ( ! empty( $link ) && ! $is_last ) {
					echo '</a>';
				} else {
					echo '</span>';
				}

			echo '</li>';

			// Separator
			if ( ! $is_last ) {
				echo '<li class="elementor-breadcrumbs-separator" aria-hidden="true">';
				if ( 'text' === $settings['separator_type'] ) {
					echo esc_html( $settings['separator_text'] );
				} else {
					\Elementor\Icons_Manager::render_icon( $settings['separator_icon'], [ 'aria-hidden' => 'true' ] );
				}
				echo '</li>';
			}
		}

		echo '</ol>';
		echo '</nav>';
	}

	/**
	 * Get Auto Breadcrumbs (Simplified Logic)
	 */
	private function get_auto_breadcrumbs( $settings ) {
		$items = [];

		// 1. Home Link
		if ( 'yes' === $settings['show_home'] ) {
			$items[] = [
				'text' => $settings['home_text'],
				'link' => [ 'url' => home_url( '/' ) ],
				'icon' => $settings['home_icon'],
			];
		}

		// 2. Dynamic Hierarchy
		if ( is_front_page() || is_home() ) {
			// Already covered by Home link if enabled. 
			// If on blog index and it's not front page:
			if ( is_home() && ! is_front_page() ) {
				$items[] = [
					'text' => single_post_title( '', false ),
					'link' => [],
				];
			}
		} elseif ( is_page() ) {
			$post = get_post();
			if ( $post->post_parent ) {
				$ancestors = get_post_ancestors( $post->ID );
				$ancestors = array_reverse( $ancestors );
				foreach ( $ancestors as $ancestor ) {
					$items[] = [
						'text' => get_the_title( $ancestor ),
						'link' => [ 'url' => get_permalink( $ancestor ) ],
					];
				}
			}
			$items[] = [
				'text' => get_the_title(),
				'link' => [],
			];
		} elseif ( is_single() ) {
			$post_type = get_post_type();
			if ( 'post' === $post_type ) {
				$categories = get_the_category();
				if ( $categories ) {
					$cat = $categories[0];
					$items[] = [
						'text' => $cat->name,
						'link' => [ 'url' => get_category_link( $cat->term_id ) ],
					];
				}
			}
			$items[] = [
				'text' => get_the_title(),
				'link' => [],
			];
		} elseif ( is_category() ) {
			$items[] = [
				'text' => single_cat_title( '', false ),
				'link' => [],
			];
		} elseif ( is_archive() ) {
			$items[] = [
				'text' => get_the_archive_title(),
				'link' => [],
			];
		} elseif ( is_search() ) {
			$items[] = [
				'text' => 'Search: ' . get_search_query(),
				'link' => [],
			];
		} elseif ( is_404() ) {
			$items[] = [
				'text' => '404',
				'link' => [],
			];
		}

		return $items;
	}
}
