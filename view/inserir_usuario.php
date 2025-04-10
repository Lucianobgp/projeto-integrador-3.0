<!doctype html>
<html lang="pt-br">
    <head>
        <title>Cadastro de usuarios</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.3.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <div class="container d-flex justify-content-center mt-5">
                <form class="row p-3 m-3 border shadow-lg" method="post" action="index.php">
                    <div class="container text-center pb-2">
                        <h6>CADASTRO DE USUÁRIOS</h6>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="nome_usuario" class="form-label">Nome:</label>
                            <input type="text" required name="nome_usuario" class="form-control" id="nome_usuario" placeholder="Nome completo...">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail:</label>
                            <input type="email" required name="email" class="form-control" id="email" placeholder="Seu melhor e-mail...">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" required name="senha" class="form-control" id="senha" placeholder="Senha de acesso...">
                        </div>
                        <!-- <div class="mb-3">
                            <label for="senha" class="form-label">Confirme sua senha:</label>
                            <input type="password" required name="senha" class="form-control" id="senha" placeholder="Confirmação de senha...">
                        </div>  -->
                    </div>
                    <div>
                        <button type="reset" class="btn btn-danger"><i class="bi bi-x-circle"></i> Apagar</button>
                        <button type="submit" name="inserir_usuario" class="btn btn-success"><i class="bi bi-floppy"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
