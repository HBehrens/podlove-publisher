<?php
namespace Podlove\Constraint;

interface Validatable
{
	/**
	 * Validate all registered constraints.
	 */
	public function validate();

	/**
	 * Register a constraint for this class.
	 */
	public static function constraint($constraintClassName);
}