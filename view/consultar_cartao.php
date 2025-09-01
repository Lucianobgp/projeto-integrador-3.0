
<!doctype html>
<html lang="pt-br">
<head>
    <title>Consulta de Cartões</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="static/style.css">
    
    <!-- Pré-carregar scripts para melhor performance -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js" defer></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.min.js" defer></script>
    <style>
        body {
            background: #f0f2f5;
        }
        /* Layout flexbox para centralizar o conteúdo considerando sidebar fixa */
        #wrapper {
            display: flex;
        }
        .sidebar {
            width: 250px;
            flex-shrink: 0;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .content-wrapper {
            margin-top: 20px;
            max-width: 1400px;
            width: 100%;
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
        #listar-cartao {
            display: table !important;
            width: 100% !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Estilo para os 5 primeiros registros - mais visível */
        .sticky-row {
            background-color: #ede7f6 !important;
            border-left: 4px solid #7c3aed !important;
            font-weight: 500;
        }
        
        /* Otimizações de performance */
        .dataTables_wrapper {
            opacity: 1 !important;
        }
        
        /* Reduzir animações para melhorar performance */
        .table-responsive {
            contain: content;
            will-change: transform;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="content-wrapper">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-credit-card"></i> Consulta de Cartões</h4>
            </div>

            <!-- Área de Pesquisa -->
            <div class="search-box">
                <form method="post" action="index.php" class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <input type="text" name="nome_cartao" class="form-control" 
                               placeholder="Pesquisar cartões...">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="consultar_cartao" class="btn btn-pesquisar w-100">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="listar-cartao" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Bandeira do Cartão</th>
                            <th class="text-center">Nome do Cartão</th>
                            <th class="text-center">Número do Cartão</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($resultado as $cartao): ?>
                        <tr>
                            <td class="text-center"><?= $cartao->id_cad_cartao ?></td>
                            <td class="text-center"><?= $cartao->nome_band ?></td>
                            <td class="text-center"><?= $cartao->nome_cartao ?></td>
                            <td class="text-center"><?= $cartao->num_cartao ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#alterar_cartao<?= $cartao->id_cad_cartao ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#excluir_cartao<?= $cartao->id_cad_cartao ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php 
                            $this->modal_alterar_cartao($cartao->id_cad_cartao, $cartao->id_cad_band, $cartao->nome_cartao, $cartao->num_cartao);
                            $this->modal_excluir_cartao($cartao->id_cad_cartao, $cartao->nome_cartao);
                        ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap bundle precisa estar no final do body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aplicar estilo diretamente nas linhas antes do DataTables inicializar
        $(document).ready(function() {
            // Primeiramente, aplicar a classe aos primeiros 5 registros diretamente
            $('#listar-cartao tbody tr').slice(0, 5).addClass('sticky-row');
            
            // Inicializar DataTables com configurações otimizadas para performance
            var table = $('#listar-cartao').DataTable({
                // Configurações básicas
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                order: [],
                
                // Opções de performance
                processing: true,
                deferRender: true,
                paging: true,
                
                // Desativa recursos pesados inicialmente
                searching: true,
                stateSave: false,
                
                // Tradução
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
                
                // Função simplificada para o callback
                drawCallback: function() {
                    // Garantir que os primeiros 5 registros na página atual tenham a classe
                    $('#listar-cartao tbody tr').removeClass('sticky-row');
                    $('#listar-cartao tbody tr').slice(0, 5).addClass('sticky-row');
                },
                
                // Otimização para exibição imediata
                initComplete: function() {
                    // Garantir que a tabela seja totalmente visível após carregar
                    $(this).show();
                }
            });
            
            // Mostrar a tabela explicitamente
            $('#listar-cartao').css('opacity', '1');
            
            // Forçar exibição da primeira página sem animações complexas
            table.page(0).draw(false);
        });
    </script>
</body>
</html>