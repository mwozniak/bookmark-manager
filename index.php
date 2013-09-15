<?php

date_default_timezone_set('Europe/Berlin');
require('inc/func.php');

$f3=require('lib/base.php');
$f3->config('config/config.ini');
$f3->config('config/routes.ini');
$f3->run();






