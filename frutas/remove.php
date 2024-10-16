<?php

use app\Frutas;

require_once $_SERVER['DOCUMENT_ROOT'] . '/test-4events/autoload.php';

// get from POST data
$id = $_POST['id'] ?? null;

// check if id is null
if (is_null($id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID não informado',
    ]);
    exit;
}

// get fruta
$objFruta = Frutas::find($id);
$objFruta->remove();

echo $objFruta->save();
