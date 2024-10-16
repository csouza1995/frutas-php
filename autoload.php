<?php

$to_load = [
    // Traits
    'app/Traits/Validate.php',
    'app/Traits/DataParsers.php',
    'app/Traits/DataManipulate.php',

    // Database
    'app/Database.php',

    // Models
    'app/Model.php',
    'app/Frutas.php',
];

foreach ($to_load as $file) {
    require_once __DIR__ . '/' . $file;
}

require_once __DIR__ . '/dump.php';
