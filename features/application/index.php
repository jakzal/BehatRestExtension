<?php

$path = $_SERVER['REQUEST_URI'];

if ('/debug' === $path) {
    $headers = array();

    foreach ($_SERVER as $name => $value) {
        if (0 === strpos($name, 'HTTP_') && false !== strpos($name, 'X_')) {
            $headerName = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($name, 5)))));
            $headers[$headerName] = $value;
        }
    }

    $response = array(
        'headers' => $headers,
        'content' => json_decode(file_get_contents('php://input'))
    );

    echo json_encode($response);
} else {
    $resourceFile = sys_get_temp_dir().$path;

    echo file_get_contents($resourceFile);
}
