<?php namespace Spoob\UnotisLaravel\Exceptions;

use Exception;

class ProjectTokenNotSpecified extends Exception
{
    protected $message = 'You didn\'t specify project token in config/unotis.php';
}
