
<!doctype html>
<html lang="pt-br">
<head>
    <title>Consulta de Bandeiras de Cartões</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="static/style.css">
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

        .table thead th {
                background: #f8f9fa !important;
                border-bottom: 2px solid #e0e5ec !important;
                color: #7c3aed !important;
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
                background: rgba(124, 58, 237, 0.07) !important;
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
            
        /* Garantir que a tabela seja exibida imediatamente */
        #listar-bandeira {
            display: table !important;
            width: 100% !important;
        }
        
        /* Estilo para os 5 primeiros registros - mais visível */
        .sticky-row {
            background-color: #ede7f6 !important;
            border-left: 4px solid #7c3aed !important;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-credit-card-2-front"></i> Consulta de Bandeiras de Cartões</h4>
            </div>

            <!-- Área de Pesquisa -->
            <div class="search-box">
                <form method="post" action="index.php" class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <input type="text" name="nome_band" class="form-control" 
                               placeholder="Pesquisar bandeiras de cartões...">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="consultar_bandeira" class="btn btn-pesquisar w-100">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="listar-bandeira" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nome da Bandeira</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($resultado as $bandeira): ?>
                        <tr>
                            <td class="text-center"><?= $bandeira->id_cad_band ?></td>
                            <td class="text-center"><?= $bandeira->nome_band ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#alterar_bandeira<?= $bandeira->id_cad_band ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#excluir_bandeira<?= $bandeira->id_cad_band ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php 
                            $this->modal_alterar_bandeira($bandeira->id_cad_band, $bandeira->nome_band);
                            $this->modal_excluir_bandeira($bandeira->id_cad_band, $bandeira->nome_band);
                        ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializa a tabela com configurações para mostrar 5 registros por padrão
            var table = $('#listar-bandeira').DataTable({
                // Define para mostrar 5 registros por página
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                
                // Desabilita ordenação inicial para manter a ordem do PHP
                order: [],
                
                // Tradução para português
                language: {
                    emptyTable: "Nenhum registro encontrado",
                    info: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 até 0 de 0 registros",
                    infoFiltered: "(Filtrados de _MAX_ registros)",
                    infoThousands: ".",
                    loadingRecords: "Carregando...",
                    processing: "Processando...",
                    zeroRecords: "Nenhum registro encontrado",
                    search: "Pesquisar",
                    paginate: {
                        next: "Próximo",
                        previous: "Anterior",
                        first: "Primeiro",
                        last: "Último"
                    },
                    lengthMenu: "Exibir _MENU_ resultados por página"
                },
                
                // Configurações que ajudam na performance e exibição imediata
                deferRender: false,
                
                // Função que é chamada quando a tabela é desenhada
                drawCallback: function(settings) {
                    // Destaca os primeiros 5 registros
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    $(rows).removeClass('sticky-row');
                    $(rows).slice(0, 5).addClass('sticky-row');
                },
                
                // Inicializa na primeira página
                displayStart: 0,
                
                // Força o redesenho completo para garantir que os dados apareçam
                destroy: true
            });
            
            // Força a tabela a mostrar a primeira página
            table.page(0).draw('page');
        });
    </script>
</body>
</html>