<?php

require __DIR__ . '/vendor/autoload.php';

use App\ZenClient;

$zenClient = new App\ZenClient();

$result = $zenClient->getTransaction("id");

echo $result;


