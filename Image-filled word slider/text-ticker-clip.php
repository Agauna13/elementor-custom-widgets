<?php
namespace TextTickerClip;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Text Ticker Clip Widget.
 *
 * @since 1.0
 */
class Text_Ticker_Clip_Widget extends Widget_Base {

	public function get_name() {
		return 'text-ticker-clip';
	}

	public function get_title() {
		return esc_html__( 'Text Ticker Clip', 'text-ticker-clip-widget' );
	}

	public function get_icon() {
		return 'eicon-slide';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		return [ 'swiper' ];
	}

	public function get_style_depends() {
		return [ 'swiper', 'text-ticker-clip-css' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_tab',
			[
				'label' => esc_html__( 'Content', 'text-ticker-clip-widget' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title', [
				'label'       => esc_html__( 'Title (H2)', 'text-ticker-clip-widget' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Ticker Item', 'text-ticker-clip-widget' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'bg_image',
			[
				'label' => esc_html__( 'Background Image', 'text-ticker-clip-widget' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Items', 'text-ticker-clip-widget' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => esc_html__( 'Design', 'text-ticker-clip-widget' ),
					],
					[
						'title' => esc_html__( 'Development', 'text-ticker-clip-widget' ),
					],
					[
						'title' => esc_html__( 'Branding', 'text-ticker-clip-widget' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings_tab',
			[
				'label' => esc_html__( 'Settings', 'text-ticker-clip-widget' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Speed (ms)', 'text-ticker-clip-widget' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'min' => 1000,
				'max' => 20000,
				'step' => 100,
			]
		);

		$this->add_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between (px)', 'text-ticker-clip-widget' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 50,
				'min' => 0,
				'max' => 200,
			]
		);

		$this->add_control(
			'reverse_direction',
			[
				'label' => esc_html__( 'Reverse Direction', 'text-ticker-clip-widget' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'text-ticker-clip-widget' ),
				'label_off' => esc_html__( 'No', 'text-ticker-clip-widget' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_tab',
			[
				'label' => esc_html__( 'Style', 'text-ticker-clip-widget' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'text-ticker-clip-widget' ),
				'selector' => '{{WRAPPER}} .ttc-ticker-item h2',
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'text-ticker-clip-widget' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ttc-ticker-item h2' => 'line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$id = 'ttc-ticker-' . $this->get_id();
		$speed = $settings['speed'];
		$space = $settings['space_between'];
		$reverse = $settings['reverse_direction'] === 'yes' ? 'true' : 'false';

		?>
		<div class="ttc-ticker-wrapper" id="<?php echo esc_attr( $id ); ?>">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php 
					// Duplicate items to ensure smooth infinite loop
					$loop_items = array_merge($settings['items'], $settings['items'], $settings['items']);
					foreach ( $loop_items as $item ) : 
						$bg_image = $item['bg_image']['url'];
						$title = $item['title'];
					?>
					<div class="swiper-slide ttc-ticker-item">
						<h2 style="background-image: url('<?php echo esc_url($bg_image); ?>');">
							<?php echo esc_html($title); ?>
						</h2>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			new Swiper('#<?php echo esc_attr( $id ); ?> .swiper-container', {
				spaceBetween: <?php echo esc_js( $space ); ?>,
				centeredSlides: false,
				speed: <?php echo esc_js( $speed ); ?>,
				autoplay: {
					delay: 0,
					reverseDirection: <?php echo esc_js( $reverse ); ?>,
					disableOnInteraction: false,
				},
				loop: true,
				loopedSlides: <?php echo count($settings['items']) * 2; ?>,
				slidesPerView: 'auto',
				allowTouchMove: false,
				disableOnInteraction: true 
			});
		});
		</script>
		<?php
	}
}
