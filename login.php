<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistema Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .card {
            width: 1000px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 0;
        }

        .login-row {
            display: flex;
            margin: 0;
        }

        .login-image {
            flex: 1.2;
            background: linear-gradient(135deg, #3498db, #2980b9);
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .login-form-container {
            flex: 1;
            padding: 3.5rem;
        }

        .login-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title h3 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
        }

        .login-title p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .form-floating {
            margin-bottom: 1.2rem;
        }

        .form-floating .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 1rem 0.75rem;
        }

        .form-floating .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            color: white;
            font-weight: 500;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2980b9, #2573a7);
            transform: translateY(-2px);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-footer a {
            color: #3498db;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #2980b9;
        }

        @media (max-width: 1024px) {
            .card {
                width: 90%;
                max-width: 800px;
            }
        }

        @media (max-width: 768px) {
            .card {
                width: 90%;
                max-width: 400px;
            }

            .login-image {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .card {
                width: 90%;
                margin: 0 auto;
            }

            .card-body {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <div class="login-row">
                <div class="login-image">
                    <img src="assets/img/finance.svg" alt="Finanças" style="max-width: 80%; height: auto;">
                </div>
                <div class="login-form-container">
                    <div class="login-title">
                        <h3>Login</h3>
                        <p>Sistema Financeiro Pessoal</p>
                    </div>

                    <form action="index.php" method="post">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                            <label for="email">E-mail</label>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                            <label for="senha">Senha</label>
                        </div>

                        <button type="submit" name="login" class="btn btn-login">
                            Entrar <i class="bi bi-arrow-right-short"></i>
                        </button>

                        <div class="login-footer">
                            <a href="recuperar.php">Esqueceu sua senha?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>