<!doctype html>
<html lang="pt-br">
<head>
    <title>Consulta de Bancos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
        }

        .content-wrapper {
            margin: 2rem auto;
            max-width: 1200px;
            padding: 0 1rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            background: white;
        }

        .card-header {
            background: linear-gradient(135deg, #0061f2 0%, #6900f2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .table-responsive {
            padding: 1rem;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e0e5ec;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e0e5ec;
            color: #2c3e50;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            margin: 0 0.2rem;
            transition: all 0.3s;
        }

        .search-box {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 1rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .btn-action {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-bank"></i> Consulta de Bancos</h4>
            </div>

            <!-- Área de Pesquisa -->
            <div class="search-box">
                <form method="post" action="index.php" class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <input type="text" name="nome_banco" class="form-control" 
                               placeholder="Pesquisar bancos...">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="consultar_banco" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Banco</th>
                            <th>Agência</th>
                            <th>Conta</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($resultado as $banco): ?>
                        <tr>
                            <td><?= $banco->nome_banco ?></td>
                            <td><?= $banco->num_agencia ?></td>
                            <td><?= $banco->num_conta ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#alterar_banco<?= $banco->id_cad_banco ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#excluir_banco<?= $banco->id_cad_banco ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php 
                            $this->modal_alterar_banco($banco->id_cad_banco, $banco->nome_banco, $banco->num_agencia, $banco->num_conta);
                            $this->modal_excluir_banco($banco->id_cad_banco, $banco->nome_banco);
                        ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>