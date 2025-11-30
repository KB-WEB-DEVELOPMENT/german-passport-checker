<?php

namespace Kbarut\TravelClearing\Exceptions;

class DateIssuedFormatException extends Exception
{
    public string $error = "The '$dateIssued' string method param is wrongly formatted. The correct date format is 'dd-mm-yyyy'. ";
	
    public function printErrorMessage(): string
    {		
 		return $this->error;
    }
}
