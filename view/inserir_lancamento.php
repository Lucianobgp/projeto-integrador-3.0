<!doctype html>
<html lang="pt-br">
<head>
    <title>Lançamentos Financeiros</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/style.css">
    <style>
        body {
            background: #f8f9fa !important;
            min-height: 100vh;
        }
        .form-container {
            max-width: 900px;
            margin: 80px auto 0 auto; /* Espaço para navbar fixa */
        }
        .card {
            border: none;
            box-shadow: 0 4px 32px rgba(124, 58, 237, 0.15);
            border-radius: 18px;
            background: white;
        }
        .card-header {
            background: #7c3aed !important;
            color: #ffd700 !important;
            border-radius: 18px 18px 0 0 !important;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            text-align: center;
        }
        .form-columns {
            display: flex;
            gap: 2rem;
            padding: 2rem;
        }
        .form-column {
            flex: 1;
            padding: 1rem;
            background: rgba(124, 58, 237, 0.07);
            border-radius: 12px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1.5px solid #7c3aed;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #ffd700;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.15);
        }
        .section-title {
            color: #7c3aed !important;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e5ec;
            text-align: left;
        }
        .btn-action {
            min-width: 120px;
            font-weight: 500;
            border-radius: 8px;
            margin: 0 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .btn-save {
            background: #7c3aed !important;
            color: #fff !important;
            border: none;
            transition: background 0.2s, color 0.2s;
        }
        .btn-save:hover, .btn-save:focus {
            background: #ffd700 !important;
            color: #7c3aed !important;
        }
        .btn-outline-secondary {
            border: 1.5px solid #ffd700;
            color: #fff;
            background: #ffd700;
        }
        .btn-outline-secondary:hover {
            background: #7c3aed;
            color: #ffd700;
            border-color: #7c3aed;
        }
        label.form-label {
            color: #7c3aed;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .form-columns {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container form-container">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-journal-plus" style="color: #ffd700;"></i>
                    <span style="color: #ffd700; font-weight: bold;">Novo Lançamento</span>
                </h4>
            </div>
            
            <form method="post" action="index.php">
                <div class="form-columns">
                    <!-- Coluna da Esquerda -->
                    <div class="form-column">
                        <h5 class="section-title">Informações Principais</h5>
                        
                        <div class="mb-3">
                            <?php $this->selectTipo(); ?>
                        </div>
                        <div class="mb-3">
                            <?php $this->selectPlano(); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição do lançamento</label>
                            <input type="text" required name="desc_lanc" class="form-control" 
                                   placeholder="Digite a descrição...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data de vencimento</label>
                            <input type="date" required name="data_venc" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Valor</label>
                            <input type="text" required name="valor_lanc" class="form-control" 
                                   placeholder="R$ 0,00">
                        </div>
                    </div>

                    <!-- Coluna da Direita -->
                    <div class="form-column">
                        <h5 class="section-title">Forma de Pagamento ou Recebi</h5>
                        
                        <div class="mb-3">
                            <?php $this->selectForma(); ?>
                        </div>
                        <div class="mb-3">
                            <?php $this->selectBanco(); ?>
                        </div>
                        <div class="mb-3">
                            <?php $this->selectCartao(); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data de Pagamento</label>
                            <input type="date" name="data_rec_pag" class="form-control">
                        </div>

                        <!-- Botões movidos para dentro da coluna -->
                        <div class="text-center mt-4">
                            <button type="reset" class="btn btn-outline-secondary btn-action">
                                <i class="bi bi-eraser"></i> Limpar
                            </button>
                            <button type="submit" name="inserir_lancamento" class="btn btn-save btn-action">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
