<?php
namespace Podlove\Constraint\Podcast;

use Podlove\Constraint\Constraint;
use Podlove\Model;
use Podlove\Settings;

class PodcastHasTitle extends Constraint {

	const SCOPE = 'podcast';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('The podcast has no title.', 'podlove');
	}

	public function the_description() {
		$url = admin_url('admin.php?page=' . Settings\Podcast::$menu_slug);
		?>
		<p>
			<?php
			echo __('Thinking of a great name for a podcast is hard. However, you need to find one before the release of the first episode. Oh, you already know it? Great!', 'podlove');
			?>
		</p>
		<p>
			<a href="<?php echo $url ?>">
				<?php echo __('Create your podcast', 'podcast') ?>
			</a>
		</p>
		<?php
	}

	public function isValid() {
		return strlen(trim(Model\Podcast::get_instance()->title)) > 0;
	}
}