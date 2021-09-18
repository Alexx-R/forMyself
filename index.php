<?php
require __DIR__ . '/vendor/autoload.php';




$gener_text = new \sc\app\Generate(__DIR__ . '/app/file/file_1.txt', ['111.png','222.png', '333.png']);

$gener_text->saveFiles();


