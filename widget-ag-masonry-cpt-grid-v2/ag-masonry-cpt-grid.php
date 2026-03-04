<?php
/**
 * Plugin Name: AG Masonry CPT Grid for Elementor
 * Description: Elementor widget that displays a masonry grid of custom post types with AJAX filtering and pagination.
 * Version: 1.1.0
 * Author: AG
 * Text Domain: ag-masonry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Enqueue Scripts & Styles
add_action( 'elementor/frontend/after_register_scripts', function() {
	wp_localize_script( 'ag-widget-ag-masonry-cpt-grid', 'ag_masonry_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'ag_masonry_nonce' )
	));
}, 20 );

// Helper functions for controls
if ( ! function_exists( 'ag_masonry_get_post_types' ) ) {
	function ag_masonry_get_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$options = array();
		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}
		return $options;
	}
}

if ( ! function_exists( 'ag_masonry_get_taxonomies' ) ) {
	function ag_masonry_get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$options = array( '' => __( 'Ninguno / Mostrar Todos', 'ag-masonry' ) );
		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}
		return $options;
	}
}

// 2. Widget Registration handled automatically by Custom Widgets Loader

if ( ! class_exists( 'AG_Masonry_Grid_Widget' ) ) {

class AG_Masonry_Grid_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'ag-masonry-grid';
		}

		public function get_title() {
			return __( 'AG Masonry Grid', 'ag-masonry' );
		}

		public function get_icon() {
			return 'eicon-gallery-grid';
		}

		public function get_categories() {
			return array( 'general' );
		}

		public function get_script_depends() {
			return array( 'ag-widget-ag-masonry-cpt-grid' );
		}

		public function get_style_depends() {
			return array( 'ag-widget-ag-masonry-cpt-grid' );
		}

		protected function register_controls() {

			// ==============================
			// TAB CONTENT
			// ==============================

			// 1. Query Defaults
			$this->start_controls_section(
				'section_query',
				array(
					'label' => __( 'Query / Content', 'ag-masonry' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'post_type',
				array(
					'label' => __( 'Post Type', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => ag_masonry_get_post_types(),
					'default' => 'post',
				)
			);

			$this->add_control(
				'taxonomy',
				array(
					'label' => __( 'Taxonomy Filter', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => ag_masonry_get_taxonomies(),
					'default' => '',
				)
			);

			$this->add_control(
				'orderby',
				array(
					'label' => __( 'Order By', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => array(
						'date' => __( 'Date', 'ag-masonry' ),
						'title' => __( 'Title', 'ag-masonry' ),
						'menu_order' => __( 'Menu Order', 'ag-masonry' ),
						'rand' => __( 'Random', 'ag-masonry' ),
					),
					'default' => 'date',
				)
			);

			$this->add_control(
				'order',
				array(
					'label' => __( 'Order', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => array(
						'DESC' => 'DESC',
						'ASC' => 'ASC',
					),
					'default' => 'DESC',
				)
			);

			$this->add_control(
				'require_image',
				array(
					'label' => __( 'Only Posts With Image', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'no',
				)
			);

			$this->add_control(
				'show_image',
				array(
					'label' => __( 'Show Image', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);

			$this->add_control(
				'show_title',
				array(
					'label' => __( 'Show Title', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);

			$this->add_control(
				'show_terms',
				array(
					'label' => __( 'Show Terms', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);

			$this->end_controls_section();

			// 2. Masonry Layout
			$this->start_controls_section(
				'section_layout',
				array(
					'label' => __( 'Masonry / Layout', 'ag-masonry' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_responsive_control(
				'columns',
				array(
					'label' => __( 'Columns', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 6,
					'default' => 3,
					'selectors' => array(
						'{{WRAPPER}} .ag-grid' => '--ag-columns: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'gap_h',
				array(
					'label' => __( 'Horizontal Gap', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
					'selectors' => array(
						'{{WRAPPER}} .ag-grid' => '--ag-gap-h: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'gap_v',
				array(
					'label' => __( 'Vertical Gap', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
					'selectors' => array(
						'{{WRAPPER}} .ag-item' => '--ag-gap-v: {{SIZE}}{{UNIT}};',
					),
				)
			);

            $this->add_responsive_control(
				'content_align',
				array(
					'label' => __( 'Content Alignment', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array( 'title' => 'Left', 'icon' => 'eicon-text-align-left' ),
						'center' => array( 'title' => 'Center', 'icon' => 'eicon-text-align-center' ),
						'right' => array( 'title' => 'Right', 'icon' => 'eicon-text-align-right' ),
					),
					'default' => 'left',
					'selectors' => array(
						'{{WRAPPER}} .ag-body' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
					),
				)
			);

			$this->end_controls_section();

			// 3. Filter
			$this->start_controls_section(
				'section_filter',
				array(
					'label' => __( 'Filter', 'ag-masonry' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'show_filter',
				array(
					'label' => __( 'Show Filter', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);

			$this->add_control(
				'filter_all_text',
				array(
					'label' => __( '"All" Text', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'All', 'ag-masonry' ),
					'condition' => array( 'show_filter' => 'yes' ),
				)
			);

            $this->add_control(
				'filter_show_count',
				array(
					'label' => __( 'Show Post Count', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'no',
					'condition' => array( 'show_filter' => 'yes' ),
				)
			);

			$this->end_controls_section();

			// 4. Pagination
			$this->start_controls_section(
				'section_pagination',
				array(
					'label' => __( 'Pagination', 'ag-masonry' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'posts_per_page',
				array(
					'label' => __( 'Posts Per Page', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'default' => 6,
				)
			);

			$this->add_control(
				'show_pagination',
				array(
					'label' => __( 'Show Pagination', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);

			$this->add_control(
				'pagination_type',
				array(
					'label' => __( 'Pagination Type', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => array(
						'numbers' => __( 'Numbers', 'ag-masonry' ),
						'prev_next' => __( 'Previous/Next', 'ag-masonry' ),
						'both' => __( 'Both', 'ag-masonry' ),
					),
					'default' => 'both',
					'condition' => array( 'show_pagination' => 'yes' ),
				)
			);

            $this->add_control(
				'allow_per_page',
				array(
					'label' => __( 'Allow User to Change Per Page', 'ag-masonry' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'no',
				)
			);

			$this->end_controls_section();


			// ==============================
			// TAB STYLE
			// ==============================

            $this->start_controls_section( 'style_container', [ 'label' => 'Container', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_responsive_control( 'container_padding', [
                'label' => 'Padding', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [ '{{WRAPPER}} .ag-masonry-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ] );
            $this->add_responsive_control( 'container_margin', [
                'label' => 'Margin', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [ '{{WRAPPER}} .ag-masonry-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ] );
            $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
                'name' => 'container_bg', 'types' => [ 'classic', 'gradient' ], 'selector' => '{{WRAPPER}} .ag-masonry-widget',
            ] );
            $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [ 'name' => 'container_border', 'selector' => '{{WRAPPER}} .ag-masonry-widget' ] );
            $this->add_control( 'container_radius', [
                'label' => 'Border Radius', 'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [ '{{WRAPPER}} .ag-masonry-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ] );
            $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [ 'name' => 'container_shadow', 'selector' => '{{WRAPPER}} .ag-masonry-widget' ] );
            $this->end_controls_section();

            // Grid Items
            $this->start_controls_section( 'style_item', [ 'label' => 'Grid Items', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [ 'name' => 'item_bg', 'selector' => '{{WRAPPER}} .ag-item', ] );
            $this->add_responsive_control( 'item_padding', [
                'label' => 'Padding', 'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [ '{{WRAPPER}} .ag-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ] );
            $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [ 'name' => 'item_border', 'selector' => '{{WRAPPER}} .ag-item' ] );
            $this->add_control( 'item_radius', [
                'label' => 'Border Radius', 'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [ '{{WRAPPER}} .ag-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ] );
            $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [ 'name' => 'item_shadow', 'selector' => '{{WRAPPER}} .ag-item' ] );
            $this->add_control( 'item_hover_scale', [
                'label' => 'Hover Scale', 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 0.5, 'max' => 2, 'step' => 0.01,
                'selectors' => [ '{{WRAPPER}} .ag-item:hover' => 'transform: scale({{VALUE}}); z-index: 2;' ],
            ] );
            $this->end_controls_section();

            // Image
            $this->start_controls_section( 'style_image', [ 'label' => 'Image', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_control( 'img_radius', [
                'label' => 'Border Radius', 'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [ '{{WRAPPER}} .ag-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ] );
            $this->add_control( 'img_hover_zoom', [
                'label' => 'Hover Zoom', 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 1, 'max' => 2, 'step' => 0.05,
                'selectors' => [ '{{WRAPPER}} .ag-item:hover .ag-thumb img' => 'transform: scale({{VALUE}});' ],
            ] );
            $this->add_control( 'img_overlay', [
                'label' => 'Overlay Color', 'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .ag-item-overlay' => 'background-color: {{VALUE}};' ],
            ] );
            $this->add_control( 'img_overlay_hover', [
                'label' => 'Hover Overlay Color', 'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .ag-item:hover .ag-item-overlay' => 'background-color: {{VALUE}};' ],
            ] );
            $this->end_controls_section();

            // Title
            $this->start_controls_section( 'style_title', [ 'label' => 'Title', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .ag-title' ] );
            $this->add_control( 'title_color', [ 'label' => 'Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-title' => 'color: {{VALUE}};' ] ] );
             $this->add_control( 'title_color_hover', [ 'label' => 'Hover Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-item:hover .ag-title' => 'color: {{VALUE}};' ] ] );
            $this->add_responsive_control( 'title_margin', [ 'label' => 'Margin', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
			$this->add_control( 'title_line_clamp', [ 'label' => 'Line Clamp', 'type' => \Elementor\Controls_Manager::NUMBER, 'selectors' => [ '{{WRAPPER}} .ag-title' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ] ] );
            $this->end_controls_section();

            // Terms
            $this->start_controls_section( 'style_terms', [ 'label' => 'Terms', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'terms_typo', 'selector' => '{{WRAPPER}} .ag-terms' ] );
            $this->add_control( 'terms_color', [ 'label' => 'Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-terms' => 'color: {{VALUE}};' ] ] );
            $this->add_responsive_control( 'terms_margin', [ 'label' => 'Margin', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-terms' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->end_controls_section();

            // Filter
            $this->start_controls_section( 'style_filter', [ 'label' => 'Filter', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_responsive_control( 'filter_wrap_margin', [ 'label' => 'Container Margin', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_responsive_control( 'filter_align', [ 'label' => 'Alignment', 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => [ 'left' => ['title'=>'Left','icon'=>'eicon-text-align-left'], 'center' => ['title'=>'Center','icon'=>'eicon-text-align-center'], 'right' => ['title'=>'Right','icon'=>'eicon-text-align-right'] ], 'selectors' => [ '{{WRAPPER}} .ag-controls' => 'text-align: {{VALUE}};', '{{WRAPPER}} .ag-filter' => 'justify-content: {{VALUE}};' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'filter_typo', 'selector' => '{{WRAPPER}} .ag-filter-btn' ] );
            $this->add_responsive_control( 'filter_padding', [ 'label' => 'Padding', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_responsive_control( 'filter_gap', [ 'label' => 'Gap', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .ag-filter' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
            $this->add_control( 'filter_radius', [ 'label' => 'Border Radius', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_control( 'filter_color', [ 'label' => 'Text Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-filter-btn' => 'color: {{VALUE}};' ] ] );
            $this->add_control( 'filter_bg', [ 'label' => 'Background', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-filter-btn' => 'background-color: {{VALUE}};' ] ] );
            $this->add_control( 'filter_color_active', [ 'label' => 'Active Text Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-filter-btn.active' => 'color: {{VALUE}};' ] ] );
            $this->add_control( 'filter_bg_active', [ 'label' => 'Active Background', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-filter-btn.active' => 'background-color: {{VALUE}};' ] ] );
            $this->end_controls_section();

            // Pagination
            $this->start_controls_section( 'style_pagination', [ 'label' => 'Pagination', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_responsive_control( 'page_wrap_margin', [ 'label' => 'Container Margin', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_responsive_control( 'page_gap', [ 'label' => 'Gap', 'type' => \Elementor\Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .ag-pagination' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'page_typo', 'selector' => '{{WRAPPER}} .ag-page-link, {{WRAPPER}} .ag-page-current' ] );
            $this->add_responsive_control( 'page_padding', [ 'label' => 'Padding', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-page-link, {{WRAPPER}} .ag-page-current' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_control( 'page_radius', [ 'label' => 'Border Radius', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .ag-page-link, {{WRAPPER}} .ag-page-current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_control( 'page_color', [ 'label' => 'Text Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-page-link, {{WRAPPER}} .ag-page-current' => 'color: {{VALUE}};' ] ] );
            $this->add_control( 'page_bg', [ 'label' => 'Background', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-page-link, {{WRAPPER}} .ag-page-current' => 'background-color: {{VALUE}};' ] ] );
			$this->add_control( 'page_color_active', [ 'label' => 'Active Text Color', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-page-current' => 'color: {{VALUE}};' ] ] );
            $this->add_control( 'page_bg_active', [ 'label' => 'Active Background', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ag-page-current' => 'background-color: {{VALUE}};' ] ] );
            $this->end_controls_section();

		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			$results = ag_masonry_get_render_output( $settings, 1, '' );

			echo '<div class="ag-masonry-widget" data-id="' . esc_attr( $this->get_id() ) . '" data-settings="' . esc_attr( wp_json_encode( $settings ) ) . '">';

			// Controls Bar
			if ( 'yes' === $settings['show_filter'] || 'yes' === $settings['allow_per_page'] ) {
				echo '<div class="ag-controls">';

				// Taxonomy Filter
				if ( 'yes' === $settings['show_filter'] && ! empty( $settings['taxonomy'] ) ) {
					echo '<ul class="ag-filter">';
					echo '<li><button class="ag-filter-btn active" data-filter="" aria-pressed="true">' . esc_html( $settings['filter_all_text'] ) . '</button></li>';

					$terms = get_terms( array( 'taxonomy' => $settings['taxonomy'], 'hide_empty' => true ) );
					if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $t ) {
							$count_html = ( 'yes' === $settings['filter_show_count'] ) ? ' (' . $t->count . ')' : '';
							echo '<li><button class="ag-filter-btn" data-filter="' . esc_attr( $t->slug ) . '" aria-pressed="false">' . esc_html( $t->name ) . esc_html( $count_html ) . '</button></li>';
						}
					}
					echo '</ul>';
				}

				// Per Page
				if( 'yes' === $settings['allow_per_page'] ) {
					echo '<div class="ag-per-page">';
					echo '<select class="ag-per-page-select">';
					foreach( array( 6, 9, 12, 24, -1 ) as $val ) {
						$selected = ( intval( $settings['posts_per_page'] ) === $val ) ? ' selected' : '';
						$label = ( -1 === $val ) ? __( 'All', 'ag-masonry' ) : $val;
						echo '<option value="' . esc_attr( $val ) . '"' . $selected . '>' . esc_html( $label ) . '</option>';
					}
					echo '</select>';
					echo '</div>';
				}

				echo '</div>';
			}

			// Grid
			echo '<div class="ag-grid">';
			echo wp_kses_post( $results['items'] );
			echo '</div>';

			// Pagination
			if ( 'yes' === $settings['show_pagination'] ) {
				echo '<ul class="ag-pagination">';
				echo wp_kses_post( $results['pagination'] );
				echo '</ul>';
			}

			echo '</div>';
		}
	}

} // end class_exists check

// 3. Render Output Function
if ( ! function_exists( 'ag_masonry_get_render_output' ) ) {
	function ag_masonry_get_render_output( $settings, $paged = 1, $term_slug = '' ) {
		$args = array(
			'post_type' => ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post',
			'posts_per_page' => isset( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 6,
			'paged' => $paged,
			'orderby' => ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
			'order' => ! empty( $settings['order'] ) ? $settings['order'] : 'DESC',
			'post_status' => 'publish',
		);

		if ( 'yes' === $settings['require_image'] ) {
			$args['meta_query'] = array( array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ) );
		}

		if ( ! empty( $term_slug ) && ! empty( $settings['taxonomy'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $settings['taxonomy'],
					'field' => 'slug',
					'terms' => $term_slug,
				),
			);
		}

		$query = new WP_Query( $args );

		$items = '';
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$items .= '<div class="ag-item">';
				$items .= '<div class="ag-item-overlay"></div>';

				if ( 'yes' === $settings['show_image'] && has_post_thumbnail() ) {
					$items .= '<a href="' . esc_url( get_permalink() ) . '" class="ag-thumb">';
					$items .= get_the_post_thumbnail( get_the_ID(), 'large' );
					$items .= '</a>';
				}

				$items .= '<div class="ag-body">';

				if ( 'yes' === $settings['show_title'] ) {
					$items .= '<h3 class="ag-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
				}

				if ( 'yes' === $settings['show_terms'] && ! empty( $settings['taxonomy'] ) ) {
					$post_terms = get_the_terms( get_the_ID(), $settings['taxonomy'] );
					if ( ! is_wp_error( $post_terms ) && ! empty( $post_terms ) ) {
						$term_names = wp_list_pluck( $post_terms, 'name' );
						$items .= '<p class="ag-terms">' . esc_html( implode( ', ', $term_names ) ) . '</p>';
					}
				}

				$items .= '</div>'; // close ag-body
				$items .= '</div>'; // close ag-item
			}
		} else {
			$items = '<p>' . __( 'No items found.', 'ag-masonry' ) . '</p>';
		}

		$pagination = '';
		if ( $query->max_num_pages > 1 ) {
			$type = isset( $settings['pagination_type'] ) ? $settings['pagination_type'] : 'both';

			if ( in_array( $type, array( 'prev_next', 'both' ) ) && $paged > 1 ) {
				$pagination .= '<li><a href="#" class="ag-page-link" data-paged="' . ( $paged - 1 ) . '">&laquo;</a></li>';
			}

			if ( in_array( $type, array( 'numbers', 'both' ) ) ) {
				for ( $i = 1; $i <= $query->max_num_pages; $i++ ) {
					if ( $i == $paged ) {
						$pagination .= '<li><span class="ag-page-current">' . $i . '</span></li>';
					} else {
						$pagination .= '<li><a href="#" class="ag-page-link" data-paged="' . $i . '">' . $i . '</a></li>';
					}
				}
			}

			if ( in_array( $type, array( 'prev_next', 'both' ) ) && $paged < $query->max_num_pages ) {
				$pagination .= '<li><a href="#" class="ag-page-link" data-paged="' . ( $paged + 1 ) . '">&raquo;</a></li>';
			}
		}

		wp_reset_postdata();

		return array(
			'items' => $items,
			'pagination' => $pagination,
		);
	}
}

// 4. AJAX Callback
if ( ! function_exists( 'ag_masonry_grid_ajax_handler' ) ) {
	function ag_masonry_grid_ajax_handler() {
		check_ajax_referer( 'ag_masonry_nonce', 'nonce' );

		$settings = isset( $_POST['settings'] ) ? json_decode( stripslashes( $_POST['settings'] ), true ) : array();
		$paged = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$term_slug = isset( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$per_page_override = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 0;

		if ( $per_page_override > 0 ) {
			$settings['posts_per_page'] = $per_page_override;
		}

		$results = ag_masonry_get_render_output( $settings, $paged, $term_slug );

		wp_send_json_success( array(
			'html_items' => $results['items'],
			'html_pagination' => $results['pagination'],
		) );
	}
}

// 5. Register AJAX handlers — required for both logged-in and logged-out users
// FIX: These two lines were missing in v1.0.0, causing all AJAX calls to fail silently
add_action( 'wp_ajax_ag_masonry_grid', 'ag_masonry_grid_ajax_handler' );
add_action( 'wp_ajax_nopriv_ag_masonry_grid', 'ag_masonry_grid_ajax_handler' );
