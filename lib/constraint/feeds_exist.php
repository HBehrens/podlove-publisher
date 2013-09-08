<?php
namespace Podlove\Constraint;

use \Podlove\Model;

class FeedsExist extends Constraint {

	const SCOPE = 'podcast';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('No subscription feed is configured.', 'podlove');
	}

	public function the_description() {
		$url = admin_url('admin.php?page=podlove_feeds_settings_handle&action=new');

		?>
		<p>
			<?php echo __('You need to configure at least one subscription feed.', 'podlove') ?>
		</p>
		<p>
			<a href="<?php echo $url ?>">
				<?php echo __('Create feeds', 'podlove') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {
		return Model\Feed::count() > 0;
	}
}