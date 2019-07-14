<?php
declare(strict_types = 1);

namespace Exception;

use RuntimeException;

/**
 * Suppose to be used for ignoring the line.
 * For example, if line format is wrong or carrier/sizes are unrecognized.
 */
class IgnorableItemException extends RuntimeException
{

}
