<?php
include('./Controller/Auto.php');
/*----- Auto class -----*/
use \Controller\Auto;

/*----- define endpoints -----*/
$endpoints = ["auto"];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

/*----- find endpoint -----*/
$params = array_search('api.php', $uri);
$params = array_slice($uri, $params);

/*----- verify endpoints -----*/
if(count($params) > 1) {
    if(!(in_array($params[1], $endpoints))) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    if($params[1] === $endpoints[0]) {
        /*----- Auto endpoint -----*/
        $auto = new Auto();
    }
    
    
}


?>