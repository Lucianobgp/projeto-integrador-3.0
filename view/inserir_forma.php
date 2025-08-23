<!doctype html>
<html lang="pt-br">
<head>
    <title>Cadastro de Forma de Pagamento</title>
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
            margin: 0 auto;
            display: flex;
            align-items: center;
            min-height: 80vh;
        }
        .card {
            border: none;
            box-shadow: 0 4px 32px rgba(124, 58, 237, 0.15);
            border-radius: 18px;
            background: white;
            width: 100%;
        }
        .card-header {
            background: #7c3aed;
            color: white;
            border-radius: 18px 18px 0 0 !important;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            text-align: center;
        }
        .form-content {
            padding: 2rem;
            background: rgba(124, 58, 237, 0.07);
            border-radius: 12px;
            margin: 1.5rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1.5px solid #7c3aed;
            background: white;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #ffd700;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.15);
        }
        .section-title {
            color: #7c3aed;
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
            background: #7c3aed;
            color: #fff;
            border: none;
        }
        .btn-save:hover {
            background: #ffd700;
            color: #7c3aed;
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
            .form-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="main-content">
        <div class="container form-container">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-cash-stack" style="color: #ffd700;"></i>
                        <span style="color: #ffd700; font-weight: bold;">Cadastro de Forma de Pagamento</span>
                    </h4>
                </div>
                
                <form method="post" action="index.php">
                    <div class="form-content">
                        <h5 class="section-title">Informações da Forma de Pagamento</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" required name="desc_forma" class="form-control" 
                                   placeholder="Digite a descrição da forma de pagamento...">
                        </div>

                        <div class="text-center mt-4">
                            <button type="reset" class="btn btn-outline-secondary btn-action">
                                <i class="bi bi-eraser"></i> Limpar
                            </button>
                            <button type="submit" name="inserir_forma" class="btn btn-save btn-action">
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
