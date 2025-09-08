<!doctype html>
<html lang="pt-br">
<head>
    <title>Cadastro de Usuário</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/style.css">
    <style>
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .form-container {
            max-width: 800px;
            margin-top: 150px;
            width: 100%;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            border-radius: 15px;
            background: white;
        }
        .card-header {
            background: #7c3aed;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
            text-align: center;
        }
        .form-content {
            padding: 2rem;
            background: rgba(124, 58, 237, 0.07);
            border-radius: 10px;
            margin: 1.5rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #e0e5ec;
            background: white;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #0061f2;
            box-shadow: 0 0 0 0.2rem rgba(0, 97, 242, 0.25);
        }
        label.form-label {
            color: #7c3aed;
            font-weight: 500;
        }
        .section-title {
            color: #7c3aed;
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
    </style>
</head>
<body class="bg-light">
    <div class="main-content">
        <div class="container form-container">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus" style="color: #ffd700;"></i>
                    <span style="color: #ffd700; font-weight: bold;">Cadastro de Usuário</span>
                </h4>
            </div>
            <form method="post" action="index.php">
                <div class="form-content">
                    <h5 class="section-title">Informações do Usuário</h5>
                    <div class="mb-3">
                        <label for="nome_usuario" class="form-label">Nome</label>
                        <input type="text" required name="nome_usuario" class="form-control" id="nome_usuario" placeholder="Digite o nome completo...">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" required name="email" class="form-control" id="email" placeholder="Digite o e-mail...">
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" required name="senha" class="form-control" id="senha" placeholder="Digite a senha...">
                    </div>
                    <div class="text-center mt-4">
                        <button type="reset" class="btn btn-outline-secondary btn-action">
                            <i class="bi bi-eraser"></i> Limpar
                        </button>
                        <button type="submit" name="inserir_usuario" class="btn btn-save btn-action">
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
