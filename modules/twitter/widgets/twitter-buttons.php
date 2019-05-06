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
 * Twitter Buttons Widget
 */
class Twitter_Buttons extends Powerpack_Widget {

	public function get_name() {
		return 'pp-twitter-buttons';
	}

	public function get_title() {
		return __( 'Twitter Buttons', 'powerpack' );
	}

    /**
	 * Retrieve the list of categories the counter widget belongs to.
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
	 * Retrieve the list of scripts the logo carousel widget depended on.
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
			'section_buttons',
			[
				'label' => __( 'Buttons', 'powerpack' ),
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'                 => __( 'Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'share',
				'options'               => [
					'share'		=> __( 'Share', 'powerpack' ),
					'follow' 	=> __( 'Follow', 'powerpack' ),
					'mention' 	=> __( 'Mention', 'powerpack' ),
					'hashtag' 	=> __( 'Hashtag', 'powerpack' ),
					'message' 	=> __( 'Message', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'profile',
			[
				'label'                 => __( 'Profile URL or Username', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => ['follow', 'mention', 'message'],
				],
			]
		);

		$this->add_control(
			'recipient_id',
			[
				'label'                 => __( 'Recipient ID', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => 'message',
				],
			]
		);

		$this->add_control(
			'default_text',
			[
				'label'                 => __( 'Default Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => 'message',
				],
			]
		);

		$this->add_control(
			'hashtag_url',
			[
				'label'                 => __( 'Hashtag URL or #hashtag', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => 'hashtag',
				],
			]
		);

		$this->add_control(
			'via',
			[
				'label'                 => __( 'Via (twitter handler)', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => ['share', 'mention', 'hashtag'],
				],
			]
		);

		$this->add_control(
			'share_text',
			[
				'label'                 => __( 'Custom Share Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => ['share', 'mention', 'hashtag'],
				],
			]
		);

		$this->add_control(
			'share_url',
			[
				'label'                 => __( 'Custom Share URL', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'button_type'    => ['share', 'mention', 'hashtag'],
				],
			]
		);

		$this->add_control(
			'show_count',
			[
				'label' => __( 'Show Count', 'powerpack' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'powerpack' ),
				'label_off' => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition'             => [
					'button_type'    => 'follow',
				],
			]
		);

		$this->add_control(
			'large_button',
			[
				'label' => __( 'Large Button', 'powerpack' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'powerpack' ),
				'label_off' => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();

		$attrs = array();
		$attr = ' ';

		$type = $settings['button_type'];
		$profile = $settings['profile'];
		$hashtag = $settings['hashtag_url'];
		$recipient_id = $settings['recipient_id'];
		$default_text = ( isset( $settings['default_text'] ) && ! empty( $settings['default_text'] ) ) ? rawurlencode( $settings['default_text'] ) : '';

		$attrs['data-size'] 		= ( 'yes' == $settings['large_button'] ) ? 'large' : '';
		if ( 'share' == $type || 'mention' == $type || 'hashtag' == $type ) {
			$attrs['data-via'] 			= $settings['via'];
			$attrs['data-text'] 		= $settings['share_text'];
			$attrs['data-url'] 			= $settings['share_url'];
		}
		$attrs['data-lang'] 		= get_locale();

		if ( 'follow' == $type ) {
			$attrs['data-show-count'] 	= ( 'yes' == $settings['show_count'] ) ? 'true' : 'false';
		}

		if ( 'message' == $type ) {
			$attrs['data-screen-name'] 	= $profile;
		}

		foreach ( $attrs as $key => $value ) {
			$attr .= $key;
			if ( ! empty( $value ) ) {
				$attr .= '="' . $value . '"';
			}

			$attr .= ' ';
		}

		?>
		<div class="pp-twitter-buttons">
			<?php if ( 'share' == $type ) { ?>
				<a href="https://twitter.com/share" class="twitter-share-button" <?php echo $attr; ?>>Share</a>
			<?php } elseif ( 'follow' == $type ) { ?>
				<a href="https://twitter.com/<?php echo $profile; ?>" class="twitter-follow-button" <?php echo $attr; ?>>Follow</a>
			<?php } elseif ( 'mention' == $type ) { ?>
				<a href="https://twitter.com/intent/tweet?screen_name=<?php echo $profile; ?>" class="twitter-mention-button" <?php echo $attr; ?>>Mention</a>
			<?php } elseif ( 'hashtag' == $type ) { ?>
				<a href="https://twitter.com/intent/tweet?button_hashtag=<?php echo $hashtag; ?>" class="twitter-hashtag-button" <?php echo $attr; ?>>Hashtag</a>
			<?php } else { ?>
				<a href="https://twitter.com/messages/compose?recipient_id=<?php echo $recipient_id; ?><?php echo ! empty( $default_text ) ? '&text=' . $default_text : ''; ?>" class="twitter-dm-button" <?php echo $attr; ?>>Message</a>
			<?php } ?>
		</div>
		<?php
	}
}