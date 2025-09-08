<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha - Sistema Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/style.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #7c3aed;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }

        .card {
            max-width: 1100px;
            width: 100%;
            min-height: 530px;
            /* altura aumentada para igualar ao login */
            margin: 2rem auto;
            display: flex;
            border: none;
            border-radius: 24px;
            box-shadow: 0 12px 40px rgba(124, 58, 237, 0.18), 0 2px 8px rgba(0, 0, 0, 0.04);
            background: white;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 24px 60px rgba(124, 58, 237, 0.22), 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            width: 100%;
            display: flex;
            flex-direction: row;
            padding: 0;
        }

        .login-row {
            display: flex;
            width: 100%;
            margin: 0;
        }

        .login-image {
            flex: 1.2;
            background: none;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
        }

        .login-image img {
            position: relative;
            z-index: 1;
            box-shadow: none;
            border-radius: 0;
            background: none !important;
            width: 100%;
            height: auto;
        }

        .login-form-container {
            flex: 1;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            text-align: center;
            margin-bottom: 2.2rem;
        }

        .login-title h3 {
            color: #7c3aed;
            font-weight: 800;
            margin-bottom: 0.2rem;
            font-size: 2.3rem;
            letter-spacing: 1px;
        }

        .login-title p {
            color: #a78bfa;
            font-size: 1.08rem;
            margin-bottom: 1.2rem;
        }

        .form-floating .form-control:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.25rem rgba(124, 58, 237, 0.15);
        }

        .form-floating .form-control {
            font-size: 1.08rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            border-radius: 10px;
            background: linear-gradient(90deg, #ffd600 0%, #ffeb3b 100%);
            border: none;
            color: #6d28d9;
            font-weight: 700;
            font-size: 1.08rem;
            margin-top: 1.2rem;
            box-shadow: 0 2px 8px rgba(124, 58, 237, 0.10);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #ffeb3b 0%, #ffd600 100%);
            color: #4b1fa6;
            transform: translateY(-2px);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.7rem;
        }

        .login-footer a {
            color: #7c3aed;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #ffd600;
        }

        @media (max-width: 1024px) {
            .card {
                max-width: 700px;
            }

            .login-form-container {
                padding: 2rem 1rem;
            }
        }

        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                max-width: 95vw;
            }

            .card-body,
            .login-row {
                flex-direction: column;
            }

            .login-image {
                min-height: 140px;
                padding: 1rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .card {
                max-width: 99vw;
                margin: 0 auto;
            }

            .login-form-container {
                padding: 1rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <div class="login-row">
                <div class="login-image">
                    <img src="images/logo.png" alt="Finanças" style="max-width: 80%; height: auto;">
                </div>
                <div class="login-form-container">
                    <div class="login-title">
                        <h3>Recuperar Senha</h3>
                        <p>Para redefinir sua senha, digite seu endereço de e-mail abaixo. Enviaremos um link para você redefinir sua senha.</p>
                    </div>
                    <form action="index.php" method="post">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                            <label for="email">E-mail</label>
                        </div>
                        <button class="btn btn-login" name="recuperar_senha" type="submit">
                            Enviar Link de Recuperação <i class="bi bi-send"></i>
                        </button>
                        <div class="login-footer">
                            <a href="login.php">Já tem uma conta? Faça login!</a>
                            <br>
                            <a href="inserir_usuario.php">Cadastrar usuário</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>