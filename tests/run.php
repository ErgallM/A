<?php
//Test debug run
/*$_SERVER['argv'][] = '--configuration';*/
$_SERVER['argv'][] = 'A';

chdir(dirname(__FILE__));
require_once("phpunit.php");