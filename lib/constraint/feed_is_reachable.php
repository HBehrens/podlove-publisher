<?php
namespace Podlove\Constraint;

use \Podlove\Http\Curl;
use \Podlove\Model;

class FeedIsReachable extends Constraint {

	const SCOPE = 'feed';
	const SEVERITY = 'critical';

	public function __construct($feed) {
		$this->setResource($feed);
	}

	/**
	 * Violation description.
	 * @return string
	 */
	public function description() {
		return __('Feed is not reachable.', 'podlove');
	}

	public function isValid() {
		
		$url = $this->resource->get_subscribe_url();

		$curl = new Curl;
		$curl->request( $url, array( 'method' => 'HEAD' ) );

		return $curl->isSuccessful();
	}

}