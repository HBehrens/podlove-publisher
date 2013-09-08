<?php
namespace Podlove\Constraint\System;

use Podlove\Constraint\Constraint;
use Podlove\Model;

class CurlAvailable extends Constraint {

	const SCOPE = 'system';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;	

	public function the_title() {
		echo __('The PHP cURL extension must be installed.', 'podlove');
	}

	public function the_description() {
		?>
		<p>
			<?php
			echo __('The PHP cURL extension must be installed on your server. If you don\'t know how to do this, please contact your hoster.', 'podlove');
			if ($this->isFunctionDisabled())
				echo __('It looks like the required function `curl_exec` has been explicitly deactivated in your php.ini. Look for the `disable_functions` key and remove `curl_exec`.', 'podlove')
			?>
		</p>
		<p>
			<?php echo __('Helpful resources:', 'podlove') ?>
			<ul>
				<li><a href="http://www.php.net/manual/en/book.curl.php">curl book</a> [php.net]</li>
				<li><a href="http://stackoverflow.com/questions/1347146/how-to-enable-curl-in-php-xampp">How to enable cURL in PHP / XAMPP</a> [stackoverflow.com]</li>
				<li><a href="http://stackoverflow.com/questions/1724511/how-to-check-where-apache-is-looking-for-a-php-ini-file">How to check where Apache is looking for a php.ini file?</a> [stackoverflow.com]</li>
			</ul>
		</p>
		<?php
	}

	public function isValid() {
		return $this->isModuleLoaded() && !$this->isFunctionDisabled();
	}

	private function isModuleLoaded() {
		return in_array( 'curl', get_loaded_extensions() );
	}

	private function isFunctionDisabled() {
		return stripos( ini_get( 'disable_functions' ), 'curl_exec' ) !== false;
	}
}