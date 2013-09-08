<?php
namespace Podlove\Constraint;

use \Podlove\Http\Curl;
use \Podlove\Model;

class FeedIsReachable extends Constraint {

	const SCOPE = 'feed';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;

	public function the_title() {
		echo __('Feed is not reachable.', 'podlove');
	}

	public function the_description() {
		$subscribe_url = $this->resource->get_subscribe_url();
		$edit_url = admin_url('admin.php?page=podlove_feeds_settings_handle&action=edit&feed=' . $this->resource->id);
		
		?>
		<p>
			<?php echo sprintf(
				__('The feed %s seems to be unavailable.', 'podlove'),
				'<a href="' . $subscribe_url . '">' . $subscribe_url . '</a>'
			);
			?>
		</p>
		<p>
			<a href="<?php echo $edit_url ?>">
				<?php echo __('Verify feed settings', 'podlove') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {

		$url = $this->resource->get_subscribe_url();

		$curl = new Curl;
		$curl->request( $url, array( 'method' => 'HEAD' ) );

		return $curl->isSuccessful();
	}

}