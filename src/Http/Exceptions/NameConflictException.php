<?php namespace Romby\Box\Http\Exceptions;

use Exception;

class NameConflictException extends Exception {

    /**
     * @var string
     */
    private $conflicts;

    public function __construct($conflicts)
    {
        $this->conflicts = $conflicts;
    }

    /**
     * @return string
     */
    public function getConflicts()
    {
        return $this->conflicts;
    }

}
