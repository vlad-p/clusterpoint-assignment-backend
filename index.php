<?php

//print_r($_GET);
//print_r($_POST);

$requestBody = file_get_contents('php://input');
$requestData = json_decode($requestBody);

//echo $requestBody;
//print_r($requestData);
//
//die();

error_reporting(E_ALL ^ E_NOTICE);

$params = explode('/', rtrim($_GET['api'], '/'));
$resourceName = null;
$resourceId = null;
$searchQuery = $_GET['query'];

if (count($params) === 1) {
    $resourceName = $params[0];
} elseif (count($params) === 2) {
    $resourceName = $params[0];
    $resourceId = $params[1];
} else {
    die('Resource does not exist');
}

if ($resourceId === null) {
    if ($resourceName === 'categories') {
        echo '[{"id":1,"name":"Job and business","numOfAdverts":5},{"id":2,"name":"Transport","numOfAdverts":7},{"id":3,"name":"Real estate","numOfAdverts":12},{"id":4,"name":"Construction","numOfAdverts":3},{"id":5,"name":"Electronics","numOfAdverts":6},{"id":6,"name":"Clothes","numOfAdverts":9},{"id":7,"name":"For children","numOfAdverts":1},{"id":8,"name":"Animals","numOfAdverts":7},{"id":9,"name":"Entertainment","numOfAdverts":4},{"id":10,"name":"Agriculture","numOfAdverts":2}]';
    } elseif ($resourceName === 'adverts') {
        if ($searchQuery) {
            echo '[{"id":1,"title":"test advert 1","text":"test text 1","price":10,"categoryId":3},{"id":2,"title":"test advert 2","text":"test text 2","price":20,"categoryId":2},{"id":3,"title":"test advert 3","text":"test text 3","price":30,"categoryId":2},{"id":4,"title":"test advert 4","text":"test text 4","price":40,"categoryId":2},{"id":5,"title":"test advert 5","text":"test text 5","price":50,"categoryId":6}]';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $requestBody) {
            echo '{"id":18,"title":"Lorem ipsum","text":"Ut lobortis dui eget sem dignissim, maximus tincidunt eros pretium. Cras commodo vulputate nisl, in maximus sem eleifend in. Morbi elit diam, varius a dapibus ut, tincidunt ac dui. Nulla in sollicitudin ipsum, sed faucibus nunc. Aenean sollicitudin placerat rhoncus. Nullam lobortis arcu et quam dignissim, quis eleifend est vulputate. Mauris nec sodales odio, eget mollis dui. Praesent finibus porttitor dolor id ultricies. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fringilla varius vestibulum. Vestibulum placerat viverra rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Integer sollicitudin aliquet varius. Nulla lectus est, rhoncus non urna eget, rhoncus laoreet libero.","price":"118.99","categoryId":5}';
        } else {
            echo '[{"id":1,"title":"test advert 1","text":"test text 1","price":10,"categoryId":3},{"id":2,"title":"test advert 2","text":"test text 2","price":20,"categoryId":2},{"id":3,"title":"test advert 3","text":"test text 3","price":30,"categoryId":2},{"id":4,"title":"test advert 4","text":"test text 4","price":40,"categoryId":2},{"id":5,"title":"test advert 5","text":"test text 5","price":50,"categoryId":6}]';
        }
    }
} else {
    if ($resourceName === 'categories') {
        echo '{"id":2,"name":"Transport","adverts":[{"id":2,"title":"test advert 2","text":"test text 2","price":20,"categoryId":2},{"id":3,"title":"test advert 3","text":"test text 3","price":30,"categoryId":2},{"id":4,"title":"test advert 4","text":"test text 4","price":40,"categoryId":2}]}';
    } elseif ($resourceName === 'adverts') {
        echo '{"id":18,"title":"Lorem ipsum","text":"Ut lobortis dui eget sem dignissim, maximus tincidunt eros pretium. Cras commodo vulputate nisl, in maximus sem eleifend in. Morbi elit diam, varius a dapibus ut, tincidunt ac dui. Nulla in sollicitudin ipsum, sed faucibus nunc. Aenean sollicitudin placerat rhoncus. Nullam lobortis arcu et quam dignissim, quis eleifend est vulputate. Mauris nec sodales odio, eget mollis dui. Praesent finibus porttitor dolor id ultricies. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fringilla varius vestibulum. Vestibulum placerat viverra rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Integer sollicitudin aliquet varius. Nulla lectus est, rhoncus non urna eget, rhoncus laoreet libero.","price":"118.99","categoryId":5}';
    }
}
