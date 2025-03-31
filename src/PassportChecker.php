<?php

namespace Kbarut\TravelClearing;

use Kbarut\TravelClearing\Exceptions\DateIssuedFormatException;
use Kbarut\TravelClearing\Exceptions\InvalidDateIssuedException;
	
class PassportChecker
{
	const CRITICAL_DATE1 = '01-11-2007';
	const CRITICAL_DATE2 = '01-11-2021';
	const CRITICAL_DATE3 = '01-11-2023';
	
	/**
	* returns true if the input string matches the format of the "serial number" part of a german passport ID,
	* false otherwise
	*
	*  (***) --> means that an Exception Error could also be created and used 
	*
	* @param string $input
	* @param string $dateIssued (format: dd-mm-yyyy)	
	* @return bool
	*/	
	public function validate(string $input,string $dateIssued): bool
    {		
		$check = $this->validateDate($dateIssued); // Error Exception created
			
		if ($this->emptyInput($input))
			return false; // (***)
		
		$cleaned = $this->clean($input);
		
		if ((strlen($cleaned) != 10)  or (!ctype_alnum($cleaned)))
			return false; // (***)
				
		$upper_alpha_num = strtoupper($cleaned);
		
		$res2 = $this->firstCharacterCheck($upper_alpha_num);
		
		if (!$res2)
		   return false; // (***)
	   
		$res3 = $this->minDigitsCountCheck(substr($upper_alpha_num,0,9),$dateIssued);
	   
	    if (!$res3)
		   return false; // (***)
	   
	   $dt1 = strtotime(DateTime::createFromFormat('d-m-Y',self::CRITICAL_DATE1));
	   $dt2 = strtotime(DateTime::createFromFormat('d-m-Y',self::CRITICAL_DATE2));
	   $dt3 = strtotime(DateTime::createFromFormat('d-m-Y',self::CRITICAL_DATE3));
	   
	   $user_dt = strtotime(DateTime::createFromFormat('d-m-Y',$dateIssued));
	   	   			
		if (($user_dt >= $dt1) and ($user_dt < $dt2)) {

			if  ( ($this->validateSegment1($upper_alpha_num)) or 
			      ($this->validateSegment2and3($upper_alpha_num)) or
				  ($this->validateOnlyLetters($upper_alpha_num))
				) {
				
				return true;
		
			} else {
				
			   return false;		
			}	
		
		}
		
		if (($user_dt >= $dt2) and ($user_dt < $dt3)) {
 
			if  (  ($this->validateSegment2and3($upper_alpha_num)) or
				   ($this->validateOnlyLetters($upper_alpha_num))
				) {
				
				return true;
		
			} else {
				
				return false,	
			}	
		
		}

		if ($user_dt >= $dt3) {
 
			if  ($this->validateSegment2and3($upper_alpha_num)) {
				
				return true;
		
			} else {
				
				return false,	
			}	
		}			
										
    }

	/**
	* returns true if the issued date (a) matches the expected format (b) is within bounds, 
	* false otherwise
	*	
	* @param string $dateIssued (format: dd-mm-yyyy)	
	* @return bool
	*/	
	public function validateDate(string $dateIssued): bool
    {
		$check = DateTime::createFromFormat('d-m-Y',$dateIssued);
				
		if (!$check) {	
			echo (new DateIssuedFormatException())->printErrorMessage();	
		}	
		  			
		$dt1 = strtotime(DateTime::createFromFormat('d-m-Y',self::CRITICAL_DATE1));
		$dt2 = strtotime(DateTime::createFromFormat('d-m-Y',$dateIssued));
		$timestamp = time();
		$dt3 = gmdate('d-m-Y',$timestamp);
		
		if (($dt2 < $dt1) or ($dt2 > $dt3)) {
			echo (new InvalidDateIssuedException())->printErrorMessage();
		}	
		
		return  true;
    }
	
	/**
	* returns true if the string is empty, false otherwise 
	*	
	* @param string $input	
	* @return bool
	*/		
	public function emptyInput(string $input): bool
    {
		return (strlen($input) === 0);
    }	

	/**
	* removes all spaces and special characters empty spaces from the string
	*	
	* @param string $input	
	* @return string
	*/
	public function clean(string $input): string
	{
		$cleaned = preg_replace('/\s+/','',$input);
	
		return $cleaned;
	}

	/**
	* Checks that the string first character is one of the allowed letters, returns true
	* if it is the case, false otherwise.
	*	
	* @param string $input	
	* @return bool
	*/	
	public function firstCharacterCheck(string $input): bool
	{
	    $allowed = [];
	    $allowed = ['C','F','G','H','J','K','L','M','N','P','R','T','V','','X','Y','Z'];
	
	    $first =  substr($input,0,1); 	
	
		return in_array($first,$allowed);
	}	

	/**
	* Based on the appropriate time segment, checks that the string contains at least one digit.
	* Returns true if that is the case, false otherwise.
	*
	* @param string $input	
	* @param string $dateIssued
	* @return bool
	*/	
	public function minDigitsCountCheck(string $input,string $dateIssued): bool
	{				
		/*
			temporary workaround for the exception case before it is properly taken care of by both the 
			validateOnlyLetters(string $input) and validate(string $input,string $dateIssued) methods later
		*/
				
		$dt1 = strtotime(DateTime::createFromFormat('d-m-Y',self::CRITICAL_DATE3));
		$dt2 = strtotime(DateTime::createFromFormat('d-m-Y',$dateIssued));
		
		if (($dt2<$dt1) and (ctype_alpha($input)))
			return true;	
		
		$split = [];
		$split = str_split($input);

		$before_allowed = [];
		$before_allowed = ['0','1','2','3','4','5','6','7','8','9'];

		$after_allowed = [];
		$after_allowed = ['1','2','3','4','5','6','7','8','9'];
		
		$dt3 = strtotime(DateTime::createFromFormat('d-m-Y',self::CRITICAL_DATE2));
		
		$count = 0;
		
		if ($dt2<$dt3) {
			foreach ($split as $char) {
				if (in_array($char,$before_allowed))
					$count++;			
			}			
		} else {
			foreach ($split as $char) {
				if (in_array($char,$after_allowed))
					$count++;
			}							
		}
		
		return ($count < 1) ? false : true;
	}

	/**
	* 
	* Validates the $input string against Time segment 1: 01.11.2007 - 01.11.2021 
	* Returns true if it matches it, false if it doesn't
	*
	* @param string $input	
	* @return bool
	*/	
	public function validateSegment1(string $input): bool
	{
		$res = true;
		
		$split = [];
		$split = str_split($input);

		$allowed_digits = [];
		$allowed_digits = ['0','1','2','3','4','5','6','7','8','9'];
		
		$allowed_letters = [];
	    $allowed_letters = ['C','F','G','H','J','K','L','M','N','P','R','T','V','W','X','Y','Z'];
	
		$merged = array_merge($allowed_digits,$allowed_letters);
	
		foreach ($split as $char) {
			if (!in_array($char,$merged)) {
				$res = false;	
				break;
			}
		}
		if (!$res)
			return false;
		
		$firstNine = substr($input,0,9); 
		$tenth = (int)substr($input,9,1);
		
		return $this->validateChecksum($firstNine,$tenth);
		
	}
	
	/**
	* 
	* Validates the $input string against both Time segment 2 (01.12.2021 - 01.11.2023)
    * and Time segment 3 (01.12.2023 - onwards ...)	
	* Returns true if it matches it, false if it doesn't
	*
	* @param string $input	
	* @return bool
	*/	
	public function validateSegment2and3(string $input): bool
	{
		$res = true;
		
		$split = [];
		$split = str_split($input);

		$allowed_digits = [];
		$allowed_digits = ['1','2','3','4','5','6','7','8','9'];
		
		$allowed_letters = [];
	    $allowed_letters = ['C','F','G','H','J','K','L','M','N','P','R','T','V','W','X','Y','Z'];
	
		$merged = array_merge($allowed_digits,$allowed_letters);
	
		foreach ($split as $char) {
			if (!in_array($char,$merged)) {
				$res = false;	
				break;
			}
		}
		if (!$res)
			return false;
		
		$firstNine = substr($input,0,9); 
		$tenth = (int)substr($input,9,1);
		
		return $this->validateChecksum($firstNine,$tenth);
	}
	
	/**
	* 
	* Deals with the special case (the passpord ID contains only allowed letters)
    * for the timespan 01.11.2007 - 01.11.2023	
	* Returns true if the $input string matches it, false if it doesn't
	*
	* @param string $input	
	* @return bool
	*/	
	public function validateOnlyLetters(string $input): bool
	{
		$res = true;
				
		$substr = substr($input,0,9);
		
		$split = [];
		$split = str_split($substr);
		
		$allowed_letters = [];
	    $allowed_letters = ['C','F','G','H','J','K','L','M','N','P','R','T','V','W','X','Y','Z'];
		
		foreach ($split as $char) {
			if (!in_array($char,$merged)) {
				$res = false;	
				break;
			}
		}
		if (!$res)
			return false;

		$firstNine = substr($input,0,9); 
		$tenth = (int)substr($input,9,1);
				
		return $this->validateChecksum($firstNine,$tenth); 	
	}
			
	/**
	* handles the ICAO (Civil Aviation Organization) MRZ (Machine Readable Zone) digit checksum  algorithm:
	* https://planetcalc.com/9535/ 
	*
	* @param string $firstNine
	* @param int $tenth	
	* @return bool
	*/	
	public function validateChecksum(string $firstNine,int $tenth): bool
	{
		$split = [];
		$split = str_split($firstNine);
		
		$indices = [];
		$num_values = [];
		
		$chars_num_values = []; 
		$chars_num_values = require('chars_num_values.php');
	
		$weights = [];
		$weights = [7,3,1,7,3,1,7,3,1];
		
		$sum = 0;
		
		foreach ($split as $char) {
			$indices[] = array_search($char,array_column($chars_num_values,'character')); 
		}
		
		foreach ($indices as $index) {
			$num_values[] = $chars_num_values[$index]['numerical_value'];  
		}
		
		foreach ($num_values as $i => $v) {
			$sum += $v*$weights[$i];	
		}
		
		$moded = $sum % 10; 
		
		return ($tenth === $moded);
	}		
}
