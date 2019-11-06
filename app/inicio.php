<?php  
ini_set('display_errors', 1);
ini_set('SMTP', 'smtp.iescierva.net');

// Constantes
define('ENCRIPTKEY', 'elperrodesanroque');
define('URL', '/var/www/shopmvc');
define('ROOT', DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('VIEWS', URL . APP . 'views/');

require_once('libs/MySQLdb.php');
require_once('libs/Controller.php');
require_once('libs/Application.php');
require_once('libs/Session.php');
?>