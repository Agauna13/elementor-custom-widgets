<?php
/**
 * Mobile Menu Accordion (Astra Child)
 * - Submenús anidados (recursivo)
 * - Listo para traducción con text domain: astra-child
 *
 * Clases HTML que expone (API):
 * - .mobile-menu-accordion
 * - .mobile-menu-item (nivel 1)
 * - .mobile-menu-sub-item (nivel 2+)
 * - .mobile-menu-toggle
 * - .mobile-menu-submenu
 * - .active
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Astra_Child_Mobile_Menu_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		// ID interno: que sea único para evitar colisiones
		return 'astra_child_mobile_menu';
	}

	public function get_title() {
		return esc_html__( 'Mobile Menu Accordion', 'astra-child' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		// Categoría estándar de Elementor
		return [ 'general' ];
	}

	public function get_style_depends() {
		// Debe existir un wp_register_style con este handle
		return [ 'mobile-menu-widget-style' ];
	}

	public function get_script_depends() {
		// Debe existir un wp_register_script con este handle
		return [ 'mobile-menu-widget-script' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'astra-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => esc_html__( 'Menu', 'astra-child' ),
					'type'         => \Elementor\Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					'description'  => sprintf(
						__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'astra-child' ),
						admin_url( 'nav-menus.php' )
					),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						__( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'astra-child' ),
						admin_url( 'nav-menus.php?action=edit&menu=0' )
					),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'icon_open',
			[
				'label'   => esc_html__( 'Open Icon', 'astra-child' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'icon_close',
			[
				'label'   => esc_html__( 'Close Icon', 'astra-child' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-xmark',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 6, 'max' => 50 ],
				],
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-toggle i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mobile-menu-toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		// ----------------------------------------------------------------
// SECCIÓN DE ESTILO DEL TOGGLE (ICONO / BOTÓN)
// ----------------------------------------------------------------
$this->start_controls_section(
  'section_style_toggle',
  [
    'label' => esc_html__( 'Toggle Style', 'astra-child' ),
    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
  ]
);

// Tamaño del área clicable (ancho/alto)
$this->add_responsive_control(
  'toggle_box_size',
  [
    'label' => esc_html__( 'Toggle Box Size', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::SLIDER,
    'range' => [
      'px' => [ 'min' => 24, 'max' => 80 ],
    ],
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
    ],
  ]
);

// Espacio entre texto y toggle
$this->add_responsive_control(
  'toggle_gap',
  [
    'label' => esc_html__( 'Text / Toggle Gap', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::SLIDER,
    'range' => [
      'px' => [ 'min' => 0, 'max' => 40 ],
    ],
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-item, {{WRAPPER}} .mobile-menu-sub-item' => 'column-gap: {{SIZE}}{{UNIT}};',
    ],
  ]
);

// Padding interno del botón
$this->add_responsive_control(
  'toggle_padding',
  [
    'label' => esc_html__( 'Toggle Padding', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => [ 'px', 'em', '%' ],
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    ],
  ]
);

// Radio (esquinas)
$this->add_responsive_control(
  'toggle_border_radius',
  [
    'label' => esc_html__( 'Border Radius', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => [ 'px', '%' ],
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    ],
  ]
);

// Borde (ancho/estilo/color)
$this->add_group_control(
  \Elementor\Group_Control_Border::get_type(),
  [
    'name' => 'toggle_border',
    'selector' => '{{WRAPPER}} .mobile-menu-toggle',
  ]
);

// Sombra
$this->add_group_control(
  \Elementor\Group_Control_Box_Shadow::get_type(),
  [
    'name' => 'toggle_shadow',
    'selector' => '{{WRAPPER}} .mobile-menu-toggle',
  ]
);

// Tabs: Normal / Hover / Active (colores del botón y del icono)
$this->start_controls_tabs( 'tabs_toggle_colors' );

// NORMAL
$this->start_controls_tab(
  'tab_toggle_normal',
  [ 'label' => esc_html__( 'Normal', 'astra-child' ) ]
);

$this->add_control(
  'toggle_icon_color',
  [
    'label' => esc_html__( 'Icon Color', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle' => 'color: {{VALUE}};',
      // por si algún svg no hereda color: (depende de librería)
      '{{WRAPPER}} .mobile-menu-toggle svg' => 'fill: currentColor;',
    ],
  ]
);

$this->add_control(
  'toggle_bg_color',
  [
    'label' => esc_html__( 'Background Color', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle' => 'background-color: {{VALUE}};',
    ],
  ]
);

$this->end_controls_tab();

// HOVER
$this->start_controls_tab(
  'tab_toggle_hover',
  [ 'label' => esc_html__( 'Hover', 'astra-child' ) ]
);

$this->add_control(
  'toggle_icon_color_hover',
  [
    'label' => esc_html__( 'Icon Color', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle:hover' => 'color: {{VALUE}};',
      '{{WRAPPER}} .mobile-menu-toggle:hover svg' => 'fill: currentColor;',
    ],
  ]
);

$this->add_control(
  'toggle_bg_color_hover',
  [
    'label' => esc_html__( 'Background Color', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-toggle:hover' => 'background-color: {{VALUE}};',
    ],
  ]
);

$this->end_controls_tab();

// ACTIVE
$this->start_controls_tab(
  'tab_toggle_active',
  [ 'label' => esc_html__( 'Active', 'astra-child' ) ]
);

$this->add_control(
  'toggle_icon_color_active',
  [
    'label' => esc_html__( 'Icon Color', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-item.active > .mobile-menu-toggle' => 'color: {{VALUE}};',
      '{{WRAPPER}} .mobile-menu-sub-item.active > .mobile-menu-toggle' => 'color: {{VALUE}};',
      '{{WRAPPER}} .mobile-menu-item.active > .mobile-menu-toggle svg' => 'fill: currentColor;',
      '{{WRAPPER}} .mobile-menu-sub-item.active > .mobile-menu-toggle svg' => 'fill: currentColor;',
    ],
  ]
);

$this->add_control(
  'toggle_bg_color_active',
  [
    'label' => esc_html__( 'Background Color', 'astra-child' ),
    'type'  => \Elementor\Controls_Manager::COLOR,
    'selectors' => [
      '{{WRAPPER}} .mobile-menu-item.active > .mobile-menu-toggle' => 'background-color: {{VALUE}};',
      '{{WRAPPER}} .mobile-menu-sub-item.active > .mobile-menu-toggle' => 'background-color: {{VALUE}};',
    ],
  ]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

		
		
		
		// Estilo
		$this->start_controls_section(
			'section_style_menu',
			[
				'label' => esc_html__( 'Menu Style', 'astra-child' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_typography',
				'label'    => esc_html__( 'Typography', 'astra-child' ),
				'selector' => '{{WRAPPER}} .mobile-menu-item > a, {{WRAPPER}} .mobile-menu-sub-item > a',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_style' );

		$this->start_controls_tab(
			'tab_menu_normal',
			[ 'label' => esc_html__( 'Normal', 'astra-child' ) ]
		);

		$this->add_control(
			'menu_text_color',
			[
				'label' => esc_html__( 'Text Color', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-item > a'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-sub-item > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
				'default' => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-item'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-sub-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_hover',
			[ 'label' => esc_html__( 'Hover', 'astra-child' ) ]
		);

		$this->add_control(
			'menu_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-item > a:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-sub-item > a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-item:hover'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-sub-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_active',
			[ 'label' => esc_html__( 'Active', 'astra-child' ) ]
		);

		$this->add_control(
			'menu_text_color_active',
			[
				'label' => esc_html__( 'Text Color', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-item.current-menu-item > a'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-item.current-menu-ancestor > a'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-sub-item.current-menu-item > a'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .mobile-menu-sub-item.current-menu-ancestor > a'  => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'divider',
			[ 'type' => \Elementor\Controls_Manager::DIVIDER ]
		);

		$this->add_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'astra-child' ),
				'type'  => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mobile-menu-item > a'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mobile-menu-sub-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_available_menus() {
		$menus   = wp_get_nav_menus();
		$options = [];
		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}
		return $options;
	}

	private function render_item_recursive( $item, $children, $settings, $level = 0 ) {
		$item_id      = (int) $item->ID;
		$has_children = isset( $children[ $item_id ] );
		$classes      = is_array( $item->classes ) ? implode( ' ', $item->classes ) : '';

		$item_class = ( $level === 0 ) ? 'mobile-menu-item' : 'mobile-menu-sub-item';

		echo '<div class="' . esc_attr( $item_class . ' ' . $classes . ( $has_children ? ' has-children' : '' ) ) . '" data-level="' . (int) $level . '">';

			echo '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';

			if ( $has_children ) {
				echo '<button type="button" class="mobile-menu-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Toggle submenu', 'astra-child' ) . '">';
					echo '<span class="mobile-menu-icon icon-open">';
						\Elementor\Icons_Manager::render_icon( $settings['icon_open'], [ 'aria-hidden' => 'true' ] );
					echo '</span>';
					echo '<span class="mobile-menu-icon icon-close">';
						\Elementor\Icons_Manager::render_icon( $settings['icon_close'], [ 'aria-hidden' => 'true' ] );
					echo '</span>';
				echo '</button>';

				echo '<div class="mobile-menu-submenu">';
					foreach ( $children[ $item_id ] as $child ) {
						$this->render_item_recursive( $child, $children, $settings, $level + 1 );
					}
				echo '</div>';
			}

		echo '</div>';
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$menu_slug = isset( $settings['menu'] ) ? $settings['menu'] : '';
		if ( ! $menu_slug ) return;

		$menu_obj = wp_get_nav_menu_object( $menu_slug );
		$menu_id  = $menu_obj ? (int) $menu_obj->term_id : $menu_slug;

		$menu_items = wp_get_nav_menu_items( $menu_id );
		if ( ! $menu_items ) return;

		$children = [];
		$roots    = [];

		foreach ( $menu_items as $item ) {
			$parent = (int) $item->menu_item_parent;
			if ( $parent === 0 ) {
				$roots[] = $item;
			} else {
				$children[ $parent ][] = $item;
			}
		}

		echo '<div class="mobile-menu-accordion">';
		foreach ( $roots as $root_item ) {
			$this->render_item_recursive( $root_item, $children, $settings, 0 );
		}
		echo '</div>';
	}
}
