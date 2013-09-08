<?php
namespace Podlove\Constraint;

use \Podlove\Model;
use \Podlove\Settings;

class PlayerHasAssets extends Constraint {

	const SCOPE = 'podcast';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('The webplayer needs to be configured.', 'podlove');
	}

	public function the_description() {
		$url = admin_url('admin.php?page=' . Settings\WebPlayer::$menu_slug);

		?>
		<p>
			<?php echo __('You need to assign assets to the web player so it knows which files to use.', 'podlove') ?>
		</p>
		<p>
			<a href="<?php echo $url ?>">
				<?php echo __('Configure player', 'podlove') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {

		foreach ( get_option( 'podlove_webplayer_formats', array() ) as $_ => $media_types )
			foreach ( $media_types as $extension => $asset_id )
				if ( $asset_id && Model\EpisodeAsset::find_by_id( $asset_id ) )
					return true;

		return false;
	}
}