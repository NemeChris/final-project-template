<?php

require '../app/core/Router.php';
require '../app/models/Model.php';
require '../app/controllers/Controller.php';
require '../app/controllers/MainController.php';
require '../app/controllers/PostController.php';
require '../app/controllers/AuthController.php';
require '../app/models/Post.php';
require '../app/models/User.php';
require '../app/core/AuthHelper.php';


$env = parse_ini_file('./.env');

define('DBNAME', $env['DBNAME']);
define('DBHOST', $env['DBHOST']);
define('DBUSER', $env['DBUSER']);
define('DBPASS', $env['DBPASS']);
define('KEY', $env['WYSIWYG']);

ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);