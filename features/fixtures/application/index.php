<?php

$path = $_SERVER['REQUEST_URI'];


if (preg_match('#/postcodes/(?P<postcode>[^/]+)#', $path, $matches)) {
    if ('SE50NP' === $matches['postcode']) {
        header('X-Powered-By: foo');
        header('Content-type: application/json');

        echo '{"status":200,"result":{"postcode":"SE5 0NP","quality":1,"eastings":532281,"northings":177715,"country":"England","nhs_ha":"London","longitude":-0.096364351028262,"latitude":51.4829280348576,"parliamentary_constituency":"Camberwell and Peckham","european_electoral_region":"London","primary_care_trust":"Southwark","region":"London","lsoa":"Southwark 017A","msoa":"Southwark 017","nuts":null,"incode":"0NP","outcode":"SE5","admin_district":"Southwark","parish":null,"admin_county":null,"admin_ward":"Camberwell Green","ccg":"NHS Southwark","codes":{"admin_district":"E09000028","admin_county":"E99999999","admin_ward":"E05000535","parish":"E43000218","ccg":"E38000171"}}}';

        exit;
    }
}

header('HTTP/1.1 404 Not Found');
header('X-Powered-By: foo');
header('Content-type: application/json');

echo '{"status":404,"error":"Postcode not found"}';
