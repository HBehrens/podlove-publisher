<?php
namespace Podlove\Constraint;

use \Podlove\Http\Curl;
use \Podlove\Model;

class EpisodeHasValidFiles extends Constraint {

	const SCOPE = 'feed';
	const SEVERITY = Constraint::SEVERITY_CRITICAL;

	/**
	 * Violation description.
	 * @return string
	 */
	public function description() {
		return __('An episode does not have any valid files.', 'podlove');
	}

	public function isValid() {

		$episode = $this->resource;
		$media_files = array_filter($episode->media_files(), function ($file) {
			return $file->size > 0;
		});

		return count($media_files) > 0;
	}

}