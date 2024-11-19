<?php
namespace Packages\YimoEx;

use Packages\YimoEx\Calculator;

define('PRIORITY_ROOT', 0); //+ - 
define('PRIORITY_PS', 1); //+ - 
define('PRIORITY_MD', 2); //x / % 
define('PRIORITY_POW', 3); // ^ 
define('PRIORITY_VAL', 4); // VALUE

include 'calculator.php';
include 'node.php';

$cal = new Calculator();
$rec = $cal -> run('5*9/2+4+1*2^2%1');
var_dump($rec);
