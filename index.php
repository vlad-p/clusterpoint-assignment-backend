<?php

$requestBody = file_get_contents('php://input');
$requestData = json_decode($requestBody);

error_reporting(E_ALL ^ E_NOTICE);

$params = explode('/', rtrim($_GET['api'], '/'));
$resourceName = null;
$resourceId = null;
$searchQuery = $_GET['query'];

$login = 'vladimirs.puzanovs@gmail.com';
$password = 'i50jHYA6krNw';
$accountId = 2096;
$databaseName = 'adverts';
$host = 'https://api-eu.clusterpoint.com';

$apiUrl = $host . '/' . $accountId . '/' . $databaseName;
$commandName = null;

$apiRequestHandle = curl_init();
curl_setopt($apiRequestHandle, CURLOPT_USERPWD, $login . ':' . $password);
curl_setopt($apiRequestHandle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($apiRequestHandle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($apiRequestHandle, CURLOPT_POST, 1);

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
        $commandName = '_search.json';
        $requestPayload = new stdClass();
        $requestPayload->query = '*';
        $requestPayload->aggregate = 'categoryId AS id, categoryName AS name, COUNT(categoryName) AS numOfAdverts GROUP BY categoryName ORDER BY categoryName DESC';

        curl_setopt($apiRequestHandle, CURLOPT_POSTFIELDS, json_encode($requestPayload));
        curl_setopt($apiRequestHandle, CURLOPT_URL, $apiUrl . '/' . $commandName);
        $result = json_decode(curl_exec($apiRequestHandle));
        curl_close($apiRequestHandle);

        echo json_encode($result->aggregate[0]->data);
    } elseif ($resourceName === 'adverts') {
        $requestPayload = new stdClass();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $requestBody) {
            $commandName = '.json';
            $requestPayload = $requestData;
            $requestPayload->id = uniqid();
        } else {
            $commandName = '_search.json';
            if ($searchQuery) {
                $requestPayload->query = '<text>' . $searchQuery . '</text>';
            } else {
                $requestPayload->query = '*';
            }
        }

        curl_setopt($apiRequestHandle, CURLOPT_POSTFIELDS, json_encode($requestPayload));
        curl_setopt($apiRequestHandle, CURLOPT_URL, $apiUrl . '/' . $commandName);
        $result = json_decode(curl_exec($apiRequestHandle));
        curl_close($apiRequestHandle);

        echo $commandName === '_search.json' ? json_encode($result->documents) : json_encode($result->documents[0]);
    }
} else {
    if ($resourceName === 'categories') {
        $commandName = '_search.json';
        $requestPayload = new stdClass();
        $requestPayload->query = '<categoryId>' . $resourceId . '</categoryId>';

        curl_setopt($apiRequestHandle, CURLOPT_POSTFIELDS, json_encode($requestPayload));
        curl_setopt($apiRequestHandle, CURLOPT_URL, $apiUrl . '/' . $commandName);
        $result = json_decode(curl_exec($apiRequestHandle));
        curl_close($apiRequestHandle);

        $category = new stdClass();
        $category->id = $resourceId;
        $category->name = $result->documents[0]->categoryName;
        $category->adverts = $result->documents;

        echo json_encode($category);
    } elseif ($resourceName === 'adverts') {
        $commandName = '_retrieve.json';
        $requestPayload = new stdClass();
        $requestPayload->id = $resourceId;

        curl_setopt($apiRequestHandle, CURLOPT_POSTFIELDS, json_encode($requestPayload));
        curl_setopt($apiRequestHandle, CURLOPT_URL, $apiUrl . '/' . $commandName);
        $result = json_decode(curl_exec($apiRequestHandle));
        curl_close($apiRequestHandle);

        echo json_encode($result->documents[0]);
    }
}

exit();
