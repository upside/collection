<?php
require __DIR__ . '/../vendor/autoload.php';

use Upside\Collection\Collection;


$collection = new Collection([
    ['test' => 3], ['test' => 1], ['test' => 2],
]);

$t = $collection->chunk(1);


dd($t);