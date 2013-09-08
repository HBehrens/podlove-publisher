<?php
namespace Podlove\Constraint\System;

use Podlove\Constraint\Constraint;
use Podlove\Model;

class IconvAvailable extends Constraint {

	const SCOPE = 'system';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('The PHP iconv extension must be installed.', 'podlove');
	}

	public function the_description() {
		?>
		<p>
			<?php
			echo __('The PHP iconv extension must be installed on your server. If you don\'t know how to do this, please contact your hoster.', 'podlove');
			?>
		</p>
		<p>
			<?php echo __('Helpful resources:') ?>
			<ul>
				<li><a href="http://stackoverflow.com/a/8068208/72448">iconv installation instructions for some distributions</a> [stackoverflow.com]</li>
				<li><a href="http://php.net/manual/en/book.iconv.php">iconv book</a> [php.net]</li>
				<li><a href="http://stackoverflow.com/questions/1724511/how-to-check-where-apache-is-looking-for-a-php-ini-file">How to check where Apache is looking for a php.ini file?</a> [stackoverflow.com]</li>
			</ul>
		</p>
		<?php
	}

	public function isValid() {
		return function_exists( 'iconv' );
	}
}