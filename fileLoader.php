<?php

function loadFile($path) {
    $file = require __DIR__ . "/" . $path;
    return $file;
}
