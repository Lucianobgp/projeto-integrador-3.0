<!doctype html>
<html lang="pt-br">
<head>
    <title>Consulta de Lançamentos</title>
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
            max-width: 1400px;
            padding: 0 1rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            background: white;
        }

        .card-header { 
            background: #7c3aed !important; 
            color: #ffd700 !important; 
            border-radius: 15px 15px 0 0 !important; 
            padding: 1.5rem; 
            text-align: center; 
        }

        .table-responsive {
            padding: 1rem;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .search-box { 
            padding: 1rem; 
            background: rgba(124, 58, 237, 0.07) !important; 
            border-radius: 10px; 
            margin: 1rem; 
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e0e5ec;
            color: #2c3e50;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Ocultar colunas específicas */
        .column-hide {
            display: none;
        }

        /* Status de lançamento */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        /* Valor */
        .value-positive {
            color: #28a745;
            font-weight: 600;
        }

        .value-negative {
            color: #dc3545;
            font-weight: 600;
        }

        /* Ações */
        .btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            margin: 0 0.2rem;
            transition: all 0.3s;
        }

        .table thead th { 
            background: #f8f9fa !important; 
            border-bottom: 2px solid #e0e5ec !important; 
            color: #7c3aed !important; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            padding: 1rem; 
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
        /* Botão Pesquisar estilo roxo/amarelo */
        .btn-pesquisar {
            background: #6f42c1 !important;
            color: #fff !important;
            border: none !important;
            transition: background 0.3s, color 0.3s;
        }
        .btn-pesquisar:hover, .btn-pesquisar:focus {
            background: #ffd600 !important;
            color: #6f42c1 !important;
            border: none !important;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header text-center">
                    <h4 class="mb-0"> 
                        <i class="bi bi-list-check" style="color: #ffd700;"></i> 
                        <span style="color: #ffd700; font-weight: bold;">Consulta de Lançamentos</span> 
                    </h4>
            </div>

            <!-- Área de Pesquisa -->
            <div class="search-box">
                <form method="post" action="index.php" class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <input type="text" name="desc_lanc" class="form-control" 
                               placeholder="Pesquisar lançamentos...">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="consultar_lancamento" class="btn btn-pesquisar w-100">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="column-hide">Código</th>
                            <th>Tipo</th>
                            <th>Plano</th>
                            <th>Descrição</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Forma</th>
                            <th>Banco</th>
                            <th class="column-hide">Cartão</th>
                            <th>Pagamento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($resultado as $lancamento): ?>
                        <tr>
                            <td class="column-hide"><?= $lancamento->id_lanc ?></td>
                            <td><?= $lancamento->desc_tipo ?></td>
                            <td><?= $lancamento->desc_plano ?></td>
                            <td><?= $lancamento->desc_lanc ?></td>
                            <td><?= $this->dataBrasil($lancamento->data_venc) ?></td>
                            <td class="<?= $lancamento->desc_tipo == 'Receita' ? 'value-positive' : 'value-negative' ?>">
                                <?= $this->moedaBrasil($lancamento->valor_lanc) ?>
                            </td>
                            <td><?= $lancamento->desc_forma ?></td>
                            <td><?= $lancamento->nome_banco ?></td>
                            <td class="column-hide"><?= $lancamento->nome_cartao ?></td>
                            <td>
                                <span class="status-badge <?= empty($lancamento->data_rec_pag) ? 'status-pending' : 'status-paid' ?>">
                                    <?= empty($lancamento->data_rec_pag) ? 'Pendente' : $this->dataBrasil($lancamento->data_rec_pag) ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#alterar_lancamento<?= $lancamento->id_lanc ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#excluir_lancamento<?= $lancamento->id_lanc ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php 
                            $this->modal_alterar_lancamento(
                                $lancamento->id_lanc,
                                $lancamento->id_cad_tipo,
                                $lancamento->id_cad_plano,
                                $lancamento->desc_lanc,
                                $lancamento->data_venc,
                                $lancamento->valor_lanc,
                                $lancamento->id_cad_forma,
                                $lancamento->id_cad_banco,
                                $lancamento->id_cad_cartao,
                                $lancamento->data_rec_pag
                            );
                            $this->modal_excluir_lancamento($lancamento->id_lanc, $lancamento->desc_lanc);
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