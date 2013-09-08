<?php 
namespace Podlove\Modules\PodloveWebPlayer;

class Podlove_Web_Player extends \Podlove\Modules\Base {

	protected $module_name = 'Podlove Web Player';
	protected $module_description = 'An audio player for the web. Let users listen to your podcast right on your website';
	protected $module_group = 'web publishing';

	public function load() {

		add_filter( 'the_content', array( $this, 'autoinsert_into_content' ) );

		if ( !defined( 'PODLOVEWEBPLAYER_DIR' ) ) {
			include_once 'player/podlove-web-player/podlove-web-player.php';
		}
	}

	public function autoinsert_into_content( $content ) {

		if ( get_post_type() !== 'podcast' || post_password_required() )
			return $content;

		if ( self::there_is_a_player_in_the_content( $content ) )
			return $content;

		$inject = \Podlove\get_webplayer_setting( 'inject' );

		if ( $inject == 'beginning' ) {
			$content = '[podlove-web-player]' . $content;
		} elseif ( $inject == 'end' ) {
			$content = $content . '[podlove-web-player]';
		}

		return $content;
	}

	public static function there_is_a_player_in_the_content( $content ) {
		return (
			stripos( $content, '[podloveaudio' ) !== false OR 
			stripos( $content, '[podlovevideo' ) !== false OR
			stripos( $content, '[audio' ) !== false OR 
			stripos( $content, '[video' ) !== false OR
			stripos( $content, '[podlove-web-player' ) !== false OR
			stripos( $content, '[podlove-template' ) !== false
		);
	}

}

