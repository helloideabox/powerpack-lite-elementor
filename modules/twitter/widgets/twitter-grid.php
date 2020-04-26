<?php
namespace PowerpackElementsLite\Modules\Twitter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Twitter Grid Widget
 */
class Twitter_Grid extends Powerpack_Widget {

	public function get_name() {
		return 'pp-twitter-grid';
	}

	public function get_title() {
		return __( 'Twitter Grid', 'powerpack' );
	}

    /**
	 * Retrieve the list of categories the twitter embedded grid widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

	public function get_icon() {
		return 'fa fa-twitter power-pack-admin-icon';
	}

    /**
	 * Retrieve the list of scripts the twitter embedded grid widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'pp-jquery-plugin',
            'jquery-cookie',
			'twitter-widgets',
			'pp-scripts'
        ];
    }

	protected function _register_controls() {
		$this->start_controls_section(
			'section_grid',
			[
				'label' => __( 'Grid', 'powerpack' ),
			]
		);

		

		$this->add_control(
			'url',
			[
				'label'                 => __( 'Collection URL', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
			]
		);

		$this->add_control(
			'footer',
			[
				'label' => __( 'Show Footer?', 'powerpack' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'powerpack' ),
				'label_off' => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
            'width',
            [
                'label'                 => __( 'Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
					'unit'	=> 'px',
					'size'	=> ''
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
            ]
        );

		$this->add_control(
            'tweet_limit',
            [
                'label'                 => __( 'Tweet Limit', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '',
            ]
        );

		$this->end_controls_section();
		
		if ( !is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
			 */
			$this->start_controls_section(
				'section_upgrade_powerpack',
				[
					'label'                 => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'					=> Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				[
					'label'                 => '',
					'type'					=> Controls_Manager::RAW_HTML,
					'raw'					=> apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes'		=> 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				]
			);

			$this->end_controls_section();
		}

	}

	protected function render() {
		$settings = $this->get_settings();

		$attrs = array();
		$attr = ' ';

		$url = esc_url( $settings['url'] );

		$attrs['data-limit'] 		= ( ! empty( $settings['tweet_limit'] ) ) ? $settings['tweet_limit'] : '';
		$attrs['data-chrome'] 		= ( 'yes' != $settings['footer'] ) ? 'nofooter' : '';
		$attrs['data-width'] 		= $settings['width']['size'];

		foreach ( $attrs as $key => $value ) {
			$attr .= $key;
			if ( ! empty( $value ) ) {
				$attr .= '="' . $value . '"';
			}

			$attr .= ' ';
		}

		?>
		<div class="pp-twitter-grid" <?php echo $attr; ?>>
			<a class="twitter-grid" href="<?php echo $url; ?>?ref_src=twsrc%5Etfw" <?php echo $attr; ?>></a>
		</div>
		<?php
	}
}