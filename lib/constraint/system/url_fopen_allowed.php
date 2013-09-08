<?php
namespace Podlove\Constraint\System;

use Podlove\Constraint\Constraint;
use Podlove\Model;

class UrlFopenAllowed extends Constraint {

	const SCOPE = 'system';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('The PHP setting `allow_url_fopen` is not activated.', 'podlove');
	}

	public function the_description() {
		?>
		<p>
			<?php
			echo __('You need to allow `allow_url_fopen`. This is usually done in the php.ini. If you don\'t know how to do this, please contact your hoster.', 'podlove');
			?>
		</p>
		<p>
			<?php echo __('Helpful resources:', 'podlove') ?>
			<ul>
				<li><a href="http://php.net/manual/en/filesystem.configuration.php">PHP Runtime Configuration</a> [php.net]</li>
				<li><a href="http://stackoverflow.com/questions/1724511/how-to-check-where-apache-is-looking-for-a-php-ini-file">How to check where Apache is looking for a php.ini file?</a> [stackoverflow.com]</li>
			</ul>
		</p>
		<?php
	}

	public function isValid() {
		return ini_get( 'allow_url_fopen' );
	}
}