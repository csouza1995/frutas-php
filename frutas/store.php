<?php

use app\Frutas;

require_once $_SERVER['DOCUMENT_ROOT'] . '/test-4events/autoload.php';

// get from POST data
$id = $_POST['id'] ?? null;
dump($id);
$nome = $_POST['nome'];
$valor = $_POST['valor'];

$objFruta = isset($id) && !empty($id) ? Frutas::find($id) : new Frutas();
$objFruta->nome = $nome;
$objFruta->valor = $valor;

// dump($objFruta);

echo $objFruta->save();
