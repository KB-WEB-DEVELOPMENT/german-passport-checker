<?php

namespace Kbarut\TravelClearing\Tests;

use Kbarut\TravelClearing\PassportChecker;

it('empty passport number input', function () {
	
    $passportChecker = Mockery::mock(PassportChecker::class);
	
    $res = $passportChecker->validate('','29-03-2025')
	
    expect($res)->toBeFalse();
});

/*
Create  and run similar tests based on PassportChecker methods outputss ...
*/
