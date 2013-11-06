<?php

/**
 * Sert de dispatcheur de pages.
 *
 * @author Nicolas Beurion
 */

define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('SERVER_URL', 'http://'.$_SERVER['HTTP_HOST'].WEBROOT);


require(ROOT.'header.php');

require(ROOT.'controls/main.php');

//$requestParams = array();
if (isset($_GET['p']) && (!empty($_GET['p'])))
{
    $requestParams = explode('/', $_GET['p']);
}
else
{
    $requestParams = array('inscription', 'formulaire', 'organisme');
}


$controllerRequest = $requestParams[0];

$actionRequest = $requestParams[1];
    
array_shift($requestParams);
array_shift($requestParams);


$controllerName = 'services_'.$controllerRequest.'.php';
require(ROOT.'controls/'.$controllerName);

$firstClassLetter = strtoupper(substr($controllerRequest, 0, 1));
$controllerClassName = 'Services'.substr_replace($controllerRequest, $firstClassLetter, 0, 1);

$controller = new $controllerClassName();



if (method_exists($controller, $actionRequest))
{
    call_user_func_array(array($controller, $actionRequest), array($requestParams));
}
else
{
    echo 'erreur 404';
}

// Le footer est déjà inclus dans le template
//include('footer.php');

?>
