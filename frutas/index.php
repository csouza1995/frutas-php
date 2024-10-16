<?php

use app\Frutas;

require_once $_SERVER['DOCUMENT_ROOT'] . '/test-4events/autoload.php';

// get search
$search = $_GET['search'] ?? null;
$onlyActive = (int)($_GET['only_active'] ?? false);

$wheres = [
    ['nome', 'LIKE', "%$search%"]
];

if (!$onlyActive) {
    $wheres[] = ['removido_em', 'IS', null];
}

$data = Frutas::get($wheres);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test 4Events</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="/test-4events/assets/app.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>

<body class="container">
    <h1>Frutas</h1>

    <!-- search -->
    <div class="mb-3">
        <form action="/test-4events/frutas/index.php" method="GET">
            <input type="hidden" id="only_active" name="only_active" value="<?= (int)$onlyActive ?>">

            <div class="row">
                <div class="input-group col-6">
                    <input class="form-control" type="text" name="search" placeholder="Buscar fruta" value="<?= $search ?>">

                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">Buscar</button>
                    </div>
                </div>

                <div class="col-6 text-right">
                    <button class="btn btn-outline-primary" type="button" onclick="toggle('#only_active')">
                        <?= !$onlyActive ? 'Mostrar Removidos' : 'Apenas Ativos' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- table -->
    <table class="table">
        <tr>
            <th>Nome</th>
            <th class="text-center">Valor</th>
            <th class="text-center">Criado em</th>
            <th class="text-center">Removido em</th>
            <th class="text-center">Ação</th>
        </tr>

        <?php foreach ($data as $fruta) { ?>
            <tr>
                <td><?= $fruta->nome ?></td>
                <td class="text-center">R$ <?= number_format($fruta->valor, 2, ',', '.') ?></td>
                <td class="text-center"><?= $fruta->criado_em->format('d/m/Y H:i:s') ?></td>
                <td class="text-center"><?= $fruta->removido_em?->format('d/m/Y H:i:s') ?></td>
                <td class="text-center text-nowrap" width="1%">
                    <button class="btn btn-secondary" onclick="editar(<?= $fruta->id ?>)">Editar</button>

                    <?php if ($fruta->removido_em) { ?>
                        <button class="btn btn-outline-success" onclick="restaurar(<?= $fruta->id ?>)">Restaurar</button>
                    <?php } else { ?>
                        <button class="btn btn-outline-warning" onclick="remover(<?= $fruta->id ?>)">Remover</button>
                    <?php } ?>

                    <button class="btn btn-outline-danger" onclick="deletar(<?= $fruta->id ?>)">Excluir</button>
                </td>
            </tr>
        <?php } ?>

        <tr>
            <td colspan="4">Total de frutas: <?= count($data) ?></td>
            <td class="text-center">
                <button class="btn btn-success" onclick="adicionar()">
                    Adicionar
                </button>
            </td>
        </tr>
    </table>

    <!-- modal form -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/test-4events/frutas/store.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFormLabel">Formulário de Frutas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id" id="id">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input class="form-control" type="text" name="nome" id="nome" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input class="form-control" type="number" name="valor" id="valor" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" onclick="submitModal()">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/test-4events/assets/app.js"></script>

    <script>
        function reload() {
            location.reload();
        }

        function toggle(selector) {
            const input = $(selector);
            const value = input.val() === '1' ? '0' : '1';

            input.val(value);
            input.closest('form').submit();
        }

        function adicionar() {
            $('#modalForm #id').val('');
            $('#modalForm #nome').val('');
            $('#modalForm #valor').val(0);

            $('#modalForm').modal('show');
        }

        function editar(id) {
            $.ajax({
                url: '/test-4events/frutas/show.php',
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.status !== 'success') {
                        console.log(data.message);
                        alert('Erro ao buscar dados');
                        return;
                    }

                    $('#modalForm #id').val(response.data.id);
                    $('#modalForm #nome').val(response.data.nome);
                    $('#modalForm #valor').val(response.data.valor);

                    $('#modalForm').modal('show');
                },
                error: function(response) {
                    alert('Erro ao buscar fruta');
                    console.log(response);
                }
            })
        }

        function remover(id) {
            $.ajax({
                url: '/test-4events/frutas/remove.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    reload();
                },
                error: function(response) {
                    alert('Erro ao remover fruta');
                    console.log(response);
                }
            });
        }

        function restaurar(id) {
            $.ajax({
                url: '/test-4events/frutas/restore.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    reload();
                },
                error: function(response) {
                    alert('Erro ao restaurar fruta');
                    console.log(response);
                }
            });
        }

        function deletar(id) {
            $.ajax({
                url: '/test-4events/frutas/delete.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    reload();
                },
                error: function(response) {
                    alert('Erro ao deletar fruta');
                    console.log(response);
                }
            });
        }

        function submitModal() {
            const form = $('#modalForm form');

            const data = {
                id: form.find('#id').val(),
                nome: form.find('#nome').val(),
                valor: form.find('#valor').val(),
            };

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: data,
                success: function(response) {
                    reload();
                },
                error: function(response) {
                    alert('Erro ao salvar fruta');
                    console.log(response);
                }
            });
        }
    </script>
</body>

</html>