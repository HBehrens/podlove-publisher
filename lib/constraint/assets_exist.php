<?php
namespace Podlove\Constraint;

use \Podlove\Model;

class AssetsExist extends Constraint {

	const SCOPE = 'podcast';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('No assets are configured.', 'podlove');
	}

	public function the_description() {
		$url = admin_url('admin.php?page=podlove_episode_assets_settings_handle&action=new');

		?>
		<p>
			<?php echo __('You need to configure at least one asset.', 'podlove') ?>
		</p>
		<p>
			<a href="<?php echo $url ?>">
				<?php echo __('Create assets', 'podlove') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {
		return Model\EpisodeAsset::count() > 0;
	}
}