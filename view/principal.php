<!doctype html>
<html lang="pt-br">
    <head>
        <title>Tela principal</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.3.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- CSS -->
        <link rel="stylesheet" href="static/style.css">
        <style>
    /* Adicionar um efeito de vidro ao fundo das divs */
    .glass-effect {
        background: rgba(255, 255, 255, 0.2); /* Fundo semi-transparente */
        backdrop-filter: blur(8px); /* Aplica o desfoque ao fundo */
        -webkit-backdrop-filter: blur(8px); /* Compatibilidade com navegadores WebKit */
        border: 1px solid rgba(255, 255, 255, 0.3); /* Borda semi-transparente */
        border-radius: 10px; /* Bordas arredondadas */
    }

    /* Ajustar fundo da página para destacar o efeito */
    body {
        background: url('https://via.placeholder.com/1920x1080') no-repeat center center fixed;
        background-size: cover;
    }
</style>
    </head>

    <body class="vh-100" style="background: url('images/finanças_pessoais.jpeg') no-repeat center center; background-size: cover;">
        <header>
            <!-- place navbar here -->
            <?php echo $menu ?>
        </header>
        <main>
        <div class="container my-5">
        <div class="row text-center text-white">
            <!-- Div 1 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="p-4 border border-primary border-2 rounded shadow glass-effect">
                    <h3><?php echo $this->viewReceita(); ?></h3>
                </div>
            </div>
            <!-- Div 2 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="p-4 border border-danger border-2 rounded shadow glass-effect">
                    <h3><?php echo $this->viewDespesa(); ?></h3>
                </div>
            </div>
            <!-- Div 3 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="p-4 border border-success border-2 rounded shadow glass-effect">
                    <h3><?php echo $this->viewSaldo(); ?></h3>
                </div>
            </div>
        </div>
    </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>