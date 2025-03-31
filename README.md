 [1. Project Description](#project-description)
 
 [2. Installation](#installation)
 
 [3. Usage](#usage)
 
 [4. License](#license)
 
# Project Description

This PHP 8.0 package determines if a PHP variable of the data type "string" matches the current "**serial number part**" present on all German ID cards and German passports.

The serial number part format follows the ICAO (International Civil Aviation Organization) guidelines and makes it MRZ (machine-readable zone) compliant.

Note that since November 1, 2007, the serial number part contains **9 characters** and **one checksum digit** (10 characters in total).

![image1](https://i.ibb.co/5WpBWVNv/image1.png)

![image2](https://i.ibb.co/67DypCDH/image2.png)

<ins>**A) Initial constraints**</ins>

![image3](https://i.ibb.co/5WSM47jg/image3.png)

To deal with all the changes over time, my code reflects the three time segments I worked with:


Time segment #1: 01.11.2007 - 31.10.2021

Time segment #2: 01.11.2021 - 31.10.2023 

Time segment #3: 01.11.2023 - ongoing ...

<ins>**B) Additional constraint**</ins>

You can read about the ICAO MRZ Check Digit algorithm here:

https://planetcalc.com/9535/ 

# Installation

Use composer to install the package: `composer require KB-WEB-DEVELOPMENT/german-passport-checker`

Install the dependencies: `composer install` 

You can run the Pest tests in the 'tests' directory with the command: `./vendor/bin/pest`


# Usage

In your examples\index.php file: 

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Kbarut\TravelClearing\PassportChecker;

$passportID = 'L01X00T471';
$dateIssued = '22-02-2025';

$passportChecker = new PassportChecker();

$res = $passportChecker->validate($passportID,$dateIssued);

 ```

# License 

The MIT License (MIT)

Copyright (c) <2025> Kâmi Barut-Wanayo

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
