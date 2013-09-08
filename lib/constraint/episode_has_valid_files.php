<?php
namespace Podlove\Constraint;

use \Podlove\Http\Curl;
use \Podlove\Model;

class EpisodeHasValidFiles extends Constraint {

	const SCOPE = 'feed';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;

	public function the_title() {
		echo __('Episode does not have any valid files.', 'podlove');
	}

	public function the_description() {
		$episode = $this->resource;
		$edit_url = admin_url('post.php?post=' . $episode->post_id . 'action=edit');

		?>
		<p>
			<?php echo sprintf(
				__('The episode %s does not have any valid files.', 'podlove'),
				'<a href="' . $edit_url . '">' . $episode->full_title() . '</a>'
			);
			?>
		</p>
		<p>
			<a href="<?php echo $edit_url ?>">
				<?php echo __('Verify episode settings', 'podlove') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {

		$episode = $this->resource;

		// ignore unpublished episodes
		if (get_post_status( $episode->post_id ) !== 'publish')
			return true;

		$media_files = array_filter($episode->media_files(), function ($file) {
			return $file->size > 0;
		});

		return count($media_files) > 0;
	}

}