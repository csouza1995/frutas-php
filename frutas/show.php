<?php

use app\Frutas;

require_once $_SERVER['DOCUMENT_ROOT'] . '/test-4events/autoload.php';

// get from POST data
$id = $_GET['id'] ?? null;

$objFruta = Frutas::find($id);

echo json_encode([
    'status' => 'success',
    'message' => 'Dados salvos',
    'data' => $objFruta->toArray()
]);
