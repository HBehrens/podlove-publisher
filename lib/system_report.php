<?php
namespace Podlove;

class SystemReport {

	private $fields = array();

	public function __construct() {

		$this->fields = array(
			'site'        => array( 'title' => 'Website',           'callback' => function() { return get_site_url(); } ),
			'php_version' => array( 'title' => 'PHP Version',       'callback' => function() { return phpversion(); } ),
			'wp_version'  => array( 'title' => 'WordPress Version', 'callback' => function() { return get_bloginfo('version'); } ),
			'podlove_version' => array( 'title' => 'Publisher Version', 'callback' => function() { return \Podlove\get_plugin_header( 'Version' ); } ),
			'player_version'  => array( 'title' => 'Web Player Version', 'callback' => function() {

				if ( ! defined( 'PODLOVEWEBPLAYER_DIR' ) )
					return 'no web player found';

				$pwp_file = PODLOVEWEBPLAYER_DIR . 'podlove-web-player.php';
				if ( ! is_readable( $pwp_file ) )
					return 'not readable';

				$plugin_data = \get_plugin_data( $pwp_file );

				return $plugin_data['Version'];
			} ),
			'max_execution_time'  => array( 'callback' => function() { return ini_get( 'max_execution_time' ); } ),
			'upload_max_filesize' => array( 'callback' => function() { return ini_get( 'upload_max_filesize' ); } ),
			'memory_limit'        => array( 'callback' => function() { return ini_get( 'memory_limit' ); } ),
			'disable_classes'     => array( 'callback' => function() { return ini_get( 'disable_classes' ); } ),
			'disable_functions'   => array( 'callback' => function() { return ini_get( 'disable_functions' ); } ),
			'permalinks' => array( 'callback' => function() {

				if ( \Podlove\get_setting( 'website', 'use_post_permastruct' ) == 'on' )
					return 'ok';

				if ( stristr( \Podlove\get_setting( 'website', 'custom_episode_slug' ), '%podcast%' ) === FALSE ) {
					$website_options = get_option( 'podlove_website' );
					$website_options['use_post_permastruct'] = 'on';
					update_option( 'podlove_website', $website_options );
				}

				return 'ok';
			} )
		);

		$this->run();
	}

	public function run() {
		foreach ( $this->fields as $field_key => $field )
			$this->fields[ $field_key ]['value'] = call_user_func( $field['callback'] );
	}

	public function render() {

		$rfill = function ( $string, $length, $fillchar = ' ' ) {
			while ( strlen( $string ) < $length ) {
				$string .= $fillchar;
			}
			return $string;
		};

		$fill_length = 1 + max( array_map( function($k) { return strlen($k); }, array_keys( $this->fields ) ) );

		$out = '';

		foreach ( $this->fields as $field_key => $field ) {
			$title = isset( $field['title'] ) ? $field['title'] : $field_key;
			$out .= $rfill( $title, $fill_length ) . $field['value'] . "\n";
		}

		return $out;
	}

}
