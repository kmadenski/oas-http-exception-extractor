<?php
require_once "vendor/autoload.php";

$extractor = new \OasHttpExceptionExtractor\ExceptionExtractor(
    [
        '/app/examples/2-multiple-method-controller.php',
        '/app/examples/1-invokable-single-method-controller.php'
    ]);
$result = $extractor->extract();
$x = 0;