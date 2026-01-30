<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Scroll_Menu_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scroll_menu';
	}

	public function get_title() {
		return esc_html__( 'Scroll Menu', 'textdomain' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		return [ 'scroll-menu-js' ];
	}

	public function get_style_depends() {
		return [ 'scroll-menu-css' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label' => esc_html__( 'Menu', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'after',
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'textdomain' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => '<strong>' . esc_html__( 'There are no menus in your site.', 'textdomain' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'textdomain' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'selector' => '{{WRAPPER}} .scroll-menu-widget .menu-item a',
			]
		);

		$this->add_responsive_control(
			'item_spacing',
			[
				'label' => esc_html__( 'Space Between Items', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .scroll-menu-widget .menu' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .scroll-menu-widget .menu' => 'justify-content: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'dropdown_style_section',
			[
				'label' => esc_html__( 'Dropdown', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dropdown_background_color',
			[
				'label' => esc_html__( 'Background Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-menu-widget .sub-menu' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_text_color',
			[
				'label' => esc_html__( 'Text Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-menu-widget .sub-menu .menu-item a' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'dropdown_text_hover_color',
			[
				'label' => esc_html__( 'Text Hover Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-menu-widget .sub-menu .menu-item a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .scroll-menu-widget .sub-menu .menu-item a',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_border',
				'selector' => '{{WRAPPER}} .scroll-menu-widget .sub-menu',
			]
		);

        $this->add_control(
			'show_indicators',
			[
				'label' => esc_html__( 'Show Submenu Indicators', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'scroll_effects_section',
			[
				'label' => esc_html__( 'Scroll Effects', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'default_color',
			[
				'label' => esc_html__( 'Default Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}}' => '--scroll-menu-default-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'scroll_color',
			[
				'label' => esc_html__( 'Scroll Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ff0000',
				'selectors' => [
					'{{WRAPPER}}' => '--scroll-menu-scroll-color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'scroll_offset',
			[
				'label' => esc_html__( 'Scroll Offset (px)', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 1,
				'default' => 50,
                'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'echo' => false,
			'menu' => $settings['menu'],
			'menu_class' => 'menu',
			'menu_id' => 'menu-' . $this->get_id(),
			'container' => false,
		];

		$menu_html = wp_nav_menu( $args );

		if ( empty( $menu_html ) ) {
			return;
		}

        $wrapper_classes = 'scroll-menu-widget';
        if ( 'yes' !== $settings['show_indicators'] ) {
            $wrapper_classes .= ' hide-indicators';
        }

		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>" data-scroll-offset="<?php echo esc_attr( $settings['scroll_offset'] ); ?>">
			<?php echo $menu_html; ?>
		</div>
		<?php
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}
}
