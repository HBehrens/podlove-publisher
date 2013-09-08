<?php
namespace Podlove\Constraint\Podcast;

use Podlove\Constraint\Constraint;
use Podlove\Model;
use Podlove\Settings;

class PodcastHasUploadLocation extends Constraint {

	const SCOPE = 'podcast';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('The podcast has no upload location.', 'podlove');
	}

	public function the_description() {
		$url = admin_url('admin.php?page=' . Settings\Podcast::$menu_slug);
		?>
		<p>
			<?php
			echo __('Podlove expects all your assets (media files) to be in the same place. You need to tell the Publisher the base URL of this upload location.', 'podlove');
			?>
		</p>
		<p>
			<a href="<?php echo $url ?>">
				<?php echo __('Enter upload location', 'podcast') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {
		return strlen(trim(Model\Podcast::get_instance()->media_file_base_uri)) > 0;
	}
}