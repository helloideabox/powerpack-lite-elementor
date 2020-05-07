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
 * Twitter Timeline Widget
 */
class Twitter_Timeline extends Powerpack_Widget {

	public function get_name() {
		return 'pp-twitter-timeline';
	}

	public function get_title() {
		return __( 'Twitter Timeline', 'powerpack' );
	}

    /**
	 * Retrieve the list of categories the twitter timeline widget belongs to.
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
		return 'ppicon-twitter-timeline power-pack-admin-icon';
	}

    /**
	 * Retrieve the list of scripts the twitter timeline widget depended on.
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
			'powerpack-frontend'
        ];
    }

	protected function _register_controls() {
		$this->start_controls_section(
			'section_timeline',
			[
				'label' => __( 'Timeline', 'powerpack' ),
			]
		);

		$this->add_control(
            'username',
            [
                'label'                 => __( 'User Name', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '',
            ]
        );

		$this->add_control(
			'theme',
			[
				'label'                 => __( 'Theme', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'light',
				'options'               => [
					'light'		=> __( 'Light', 'powerpack' ),
					'dark' 		=> __( 'Dark', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'show_replies',
			[
				'label' => __( 'Show Replies', 'powerpack' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'powerpack' ),
				'label_off' => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'layout',
			[
				'label'                 => __( 'Layout', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT2,
				'default'               => '',
				'options'               => [
					'noheader'		=> __( 'No Header', 'powerpack' ),
					'nofooter' 		=> __( 'No Footer', 'powerpack' ),
					'noborders' 	=> __( 'No Borders', 'powerpack' ),
					'transparent' 	=> __( 'Transparent', 'powerpack' ),
					'noscrollbar' 	=> __( 'No Scroll Bar', 'powerpack' ),
				],
				'multiple'	=> true
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
            'height',
            [
                'label'                 => __( 'Height', 'powerpack' ),
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

		$this->add_control(
			'link_color',
			[
				'label'                 => __( 'Link Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'                 => __( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();

		$attrs = array();
		$attr = ' ';

		$user = $settings['username'];

		$attrs['data-theme'] 			= $settings['theme'];
		$attrs['data-show-replies'] 	= ( 'yes' == $settings['show_replies'] ) ? 'true' : 'false';

		if ( ! empty( $settings['width'] ) ) {
			$attrs['data-width'] = $settings['width']['size'];
		}
		if ( ! empty( $settings['height'] ) ) {
			$attrs['data-height'] = $settings['height']['size'];
		}
		if ( isset( $settings['layout'] ) && ! empty( $settings['layout'] ) ) {
			$attrs['data-chrome'] = implode( ' ', $settings['layout'] );
		}
		if ( ! empty( $settings['tweet_limit'] ) && absint( $settings['tweet_limit'] ) ) {
			$attrs['data-tweet-limit'] = absint( $settings['tweet_limit'] );
		}
		if ( ! empty( $settings['link_color'] ) ) {
			$attrs['data-link-color'] 		= $settings['link_color'];
		}
		if ( ! empty( $settings['border_color'] ) ) {
			$attrs['data-border-color'] 	= $settings['border_color'];
		}

		foreach ( $attrs as $key => $value ) {
			$attr .= $key;
			if ( ! empty( $value ) ) {
				$attr .= '="' . $value . '"';
			}

			$attr .= ' ';
		}

		?>
		<div class="pp-twitter-timeline" <?php echo $attr; ?>>
			<a class="twitter-timeline" href="https://twitter.com/<?php echo $user; ?>" <?php echo $attr; ?>>Tweets by <?php echo $user; ?></a>
		</div>
		<?php
	}
}