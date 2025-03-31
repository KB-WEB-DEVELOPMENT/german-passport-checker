<?php

require __DIR__ . '/../vendor/autoload.php';

use Kbarut\TravelClearing\PassportChecker;

$passportChecker = new PassportChecker();

var_dump($passportChecker->validate('L01X00T471','27-01-2025'); // bool(true)
