<?php
require_once('init.php');
$f3->config('app/config/config.ini');
$f3->set('AUTOLOAD','app/;app/models/');
$f3->route('GET /@controller/@action','Controllers\@controller->@action');
$f3->set('DEBUG',3);
//run the app
$f3->run();