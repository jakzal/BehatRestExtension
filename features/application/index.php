<?php

$path = substr($_SERVER['REQUEST_URI'], 1);
$resourceFile = sys_get_temp_dir().'/'.$path;

echo file_get_contents($resourceFile);
