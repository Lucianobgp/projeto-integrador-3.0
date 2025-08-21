<!doctype html>
<html lang="pt-br">
<head>
    <title>Cadastro de Cartão</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/style.css">
    <style>
        body {
            min-height: 100vh;
            background: #f0f2f5;
        }

        .main-content {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .form-container {
            max-width: 800px;
            width: 100%;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            border-radius: 15px;
            background: white;
        }
        .card-header {
            background: linear-gradient(135deg, #0061f2 0%, #6900f2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .form-content {
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 1.5rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #e0e5ec;
            background: white;
            margin-bottom: 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0061f2;
            box-shadow: 0 0 0 0.2rem rgba(0, 97, 242, 0.25);
        }
        .section-title {
            color: #0061f2;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e5ec;
        }
        .btn-action {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            margin: 0 0.5rem;
            transition: all 0.3s;
        }
        .btn-save {
            background: linear-gradient(135deg, #0061f2 0%, #6900f2 100%);
            color: white;
            border: none;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="main-content">
        <div class="container form-container">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="bi bi-credit-card-2-front"></i> Cadastro de Cartão</h4>
                </div>
                
                <form method="post" action="index.php">
                    <div class="form-content">
                        <h5 class="section-title">Informações do Cartão</h5>
                        
                        <div class="mb-3">
                            <?php $this->selectBandeira(); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nome do Cartão</label>
                            <input type="text" required name="nome_cartao" class="form-control" 
                                   placeholder="Digite o nome do cartão...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Número do Cartão</label>
                            <input type="text" required name="num_cartao" class="form-control" 
                                   placeholder="Digite o número do cartão...">
                        </div>

                        <div class="text-center mt-4">
                            <button type="reset" class="btn btn-outline-secondary btn-action">
                                <i class="bi bi-eraser"></i> Limpar
                            </button>
                            <button type="submit" name="inserir_cartao" class="btn btn-save btn-action">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
