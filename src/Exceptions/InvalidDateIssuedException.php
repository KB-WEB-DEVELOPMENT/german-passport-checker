<?php

namespace Kbarut\TravelClearing\Exceptions;

class InvalidDateIssuedException extends Exception
{
    public string $error = "The '$dateIssued' string method param date must lie between '01-11-2007' included and today included.";
	
    public function printErrorMessage(): string
    {
	return $this->error;	
    }
}
