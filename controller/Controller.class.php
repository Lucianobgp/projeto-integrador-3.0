<?php
//classe de controle
class Controller
{

    //==============================REDIRECIONAR PÁGINA==============================

    public function redirecionar($pagina)
    {
        //iniciar sessao
        session_start();
        //incluir menu
        $menu = $this->menu();
        //incluir a view
        require_once 'view/' . $pagina . '.php';
    }

    //==============================VALIDAR LOGIN==============================

    public function validar_login($email, $senha)
    {
        //instanciar a classe Usuário
        $objUsuario = new Usuario();
        //validar usuario
        if ($objUsuario->validarLogin($email, $senha) == true) {
            //iniciar sessao
            session_start();
            //iniciar variaves de sessao
            $_SESSION['email'] = $email;
            // $_SESSION['perfil'] = $objUsuario->perfilUsuario($email);
            //menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/principal.php';
        } else {
            include_once 'login.php';
            $this->mostrarMensagem("Login ou senha inválidos!");
        }
    }

    //==============================VALIDAR SESSÃO==============================

    public function validarSessao()
    {
        //verificar variaveis de sessao
        if (! isset($_SESSION['email']) and ! isset($_SESSION['perfil'])) {
            //acesso negado
            header("location: login.php");
        }
    }

    //==============================RECUPERAR SENHA==============================

    public function recuperarSenha($email)
    {
        //instanciar a classe Usuário
        $objUsuario = new Usuario();
        //verificar se email existe
        if ($objUsuario->validarEmail($email) == true) {
            //gerar nova senha
            $senha = md5('12345678');
            //$senha = md5(substr(md5(date("YmdHis")), 1, 6));

            //atualizar senha
            $objUsuario->alterarSenha($email, $senha);

            //definir o servidor
            define('HOST', 'smtp.gmail.com');
            define('PORT', '587');
            define('USERNAME', 'senacdf.operadormicro@gmail.com');
            define('PASSWORD', 'uetz ezsn jjuy klyo');
            define('FROM', 'senacdf.operadormicro@gmail.com');

            //dados do envio
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "TLS";
            $mail->Host       = HOST;
            $mail->Port       = PORT;
            $mail->Username   = USERNAME;
            $mail->Password   = PASSWORD;

            //dados do remetente
            $mail->setFrom(FROM, 'SISTEMA SGL');
            $mail->addAddress($email);

            //dados do email
            $mail->IsHTML(true);
            $mail->Subject = ('Recuperação de Senha - SGL');
            $mail->Body    = ('<br>Sua nova senha é: <b>12345678</b>');
            $mail->Charset = 'UTF-8';

            //eviar email
            if (! $mail->Send()) {
                include_once 'recuperar.php';
                $this->mostrarMensagem("Erro ao enviar e-mail! $mail->ErrorInfo");
            } else {
                include_once 'login.php';
                $this->mostrarMensagem("A nova senha foi enviada para o e-mail informado!");
            }

        } else {
            include_once 'recuperar.php';
            $this->mostrarMensagem("E-mail não cadastrado!");
        }
    }

    //==============================CONSULTAR GERAL==============================

    public function consultar_geral($palavra)
    {
        //instanciar a classe Livro
        $objLivro = new Livro();
        //iniciar sessao
        session_start();
        //invocar o método
        $_SESSION['resultado'] = $objLivro->consultarGeral($palavra);
        header("location: livro.php");
    }

    //==============================GERAR PDF==============================

    public function gerar_pdf()
    {
        //iniciar sessao
        session_start();

        //pegar dos dados para inserir no pdf
        foreach ($_SESSION['resultado'] as $key => $valor) {
            $titulo .= '' . $valor->titulo;
        }

        //montar o relatorio
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(70, 10, 'Relatorio de Livros');
        $data = date('d/m/Y');
        $pdf->Cell(10, 35, $data);
        $pdf->Cell(70, 50, $titulo);
        $pdf->Output();
    }

    //==============================CONVERTER DATA==============================

    public function dataBrasil($data)
    {
        if ($data == null) {
            return null;
        } else {
            $data_nova = str_replace("/", "-", $data);
            return date('d/m/Y', strtotime($data_nova));
        }
    }

    //==============================CONVERTER MOEDA==============================

    public function moedaBrasil($preco)
    {
        $preco_novo = 'R$ ' . number_format($preco, 2, ',', '.');
        return $preco_novo;
    }

    //==============================MOSTRAR MENU==============================

    public function menu()
    {

        $mesAtual = date("n"); // 1-12
        $anoAtual = date("Y");

    // Sidebar container
    echo '<div class="sidebar custom-sidebar d-flex flex-column p-3">';
    echo '  <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none fs-4 fw-bold logo-title">SFP-GZ</a>';
        echo '  <hr>';
        echo '  <ul class="nav nav-pills flex-column mb-auto fs-5">';
        // Dashboard
        echo '    <li class="nav-item"><a href="index.php?principal" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>';
        // Lançamentos
        echo '    <li><a href="index.php?inserir_lancamento" class="nav-link"><i class="bi bi-pencil-square me-2"></i>Lançamentos</a></li>';
        // Cadastros dropdown
        echo '    <li class="nav-item">';
        echo '      <a class="nav-link dropdown-toggle" href="#" id="sidebarDropdownCad" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-folder-plus me-2"></i>Cadastros</a>';
        echo '      <ul class="dropdown-menu" aria-labelledby="sidebarDropdownCad">';
        echo '        <li><a href="index.php?inserir_banco" class="dropdown-item">Bancos</a></li>';
        echo '        <li><a href="index.php?inserir_bandeira" class="dropdown-item">Bandeiras de cartões</a></li>';
        echo '        <li><a href="index.php?inserir_cartao" class="dropdown-item">Cartões</a></li>';
        echo '        <li><a href="index.php?inserir_forma" class="dropdown-item">Formas de rec/pag</a></li>';
        echo '        <li><a href="index.php?inserir_plano" class="dropdown-item">Plano de contas</a></li>';
        echo '      </ul>';
        echo '    </li>';
        // Consultas dropdown
        echo '    <li class="nav-item">';
        echo '      <a class="nav-link dropdown-toggle" href="#" id="sidebarDropdownCons" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-search me-2"></i>Consultas</a>';
        echo '      <ul class="dropdown-menu" aria-labelledby="sidebarDropdownCons">';
        echo '        <li><a href="index.php?consultar_lancamento" class="dropdown-item">Lançamentos</a></li>';
        echo '        <hr class="text-white border border-2">';
        echo '        <li><a href="index.php?consultar_banco" class="dropdown-item">Bancos</a></li>';
        echo '        <li><a href="index.php?consultar_bandeira" class="dropdown-item">Bandeiras de cartões</a></li>';
        echo '        <li><a href="index.php?consultar_cartao" class="dropdown-item">Cartões</a></li>';
        echo '        <li><a href="index.php?consultar_forma" class="dropdown-item">Formas de rec/pag</a></li>';
        echo '        <li><a href="index.php?consultar_plano" class="dropdown-item">Plano de contas</a></li>';
        echo '      </ul>';
        echo '    </li>';
        // Relatórios dropdown
        echo '    <li class="nav-item">';
        echo '      <a class="nav-link dropdown-toggle" href="#" id="sidebarDropdownRel" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-bar-chart-fill me-2"></i>Relatórios</a>';
        echo '      <ul class="dropdown-menu" aria-labelledby="sidebarDropdownRel">';
        echo '        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalReceitasMes">Recebimentos</a></li>';
        echo '        <hr class="text-white border border-2">';
        echo '        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalDespesasMes">Pagamentos</a></li>';
        echo '        <hr class="text-white border border-2">';
        echo '        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalSaldoMes">Saldo</a></li>';
        echo '      </ul>';
        echo '    </li>';
        echo '  </ul>';
        echo '  <hr>';
        // Usuário
        echo '  <div class="dropdown">';
        echo '    <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="sidebarDropdownUser" data-bs-toggle="dropdown" aria-expanded="false">';
        echo '      <i class="bi bi-person-fill"></i> ' . $_SESSION['email'] . '';
        echo '    </a>';
        echo '    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarDropdownUser">';
        echo '      <li><a class="dropdown-item" href="login.php?sair"><i class="bi bi-box-arrow-right"></i> Sair</a></li>';
        echo '    </ul>';
        echo '  </div>';
        echo '</div>';

        // ...modais e scripts existentes...
        // MODAL RECEBIMENTO
        echo '<div class="modal fade" id="modalReceitasMes" tabindex="-1" aria-labelledby="modalReceitasMesLabel" aria-hidden="true">';
        echo '  <div class="modal-dialog">';
        echo '    <div class="modal-content modal-purple">';
        echo '      <div class="modal-header">';
        echo '        <h5 class="modal-title" id="modalReceitasMesLabel">Recebimentos por mês e ano</h5>';
        echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '        <form method="GET" action="relatorio_receitas.php" target="_blank" id="formReceitas">';
        echo '          <input type="hidden" name="consultar_editora" value="1">';
        echo '          <div class="form-group">';
        echo '            <label for="mes_receitas">Mês</label>';
        echo '            <select class="form-control" name="mes" id="mes_receitas">';
        $nomesMeses = [1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro"];
        foreach ($nomesMeses as $num => $nome) {
            $selected = ($num == $mesAtual) ? "selected" : "";
            echo "<option value=\"$num\" $selected>$nome</option>";
        }
        echo '            </select>';
        echo '          </div>';
        echo '          <div class="form-group">';
        echo '            <label for="ano_receitas">Ano</label>';
        echo '            <input type="number" class="form-control" name="ano" id="ano_receitas" value="' . $anoAtual . '" required>';
        echo '          </div>';
        echo '          <div class="modal-footer">';
        echo '            <button type="submit" class="btn btn-primary">Consultar</button>';
        echo '            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
        echo '          </div>';
        echo '        </form>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
        // CSS direto
        echo '<style>
            .custom-sidebar {
                background-color: #4B2673; /* cor sólida igual ao restante do sistema */
                color: #fff;
                width: 260px;
                min-height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1040;
                transition: width 0.3s;
            }
            .custom-sidebar .logo-title {
                color: #6f42c1;
                letter-spacing: 2px;
            }
            .custom-sidebar .nav-link {
                color: #fff;
                border-radius: 8px;
                margin-bottom: 4px;
                transition: background 0.2s, color 0.2s;
            }
            .custom-sidebar .nav-link.active,
            .custom-sidebar .nav-link:hover {
                background-color: #ede7f6;
                color: #7c3aed;
            }
            .custom-sidebar .dropdown-menu {
                background-color: #f3e8ff;
                border: none;
                box-shadow: 0 2px 8px rgba(111,66,193,0.08);
            }
            .custom-sidebar .dropdown-item:hover {
                background-color: #ede7f6;
                color: #6f42c1;
            }
            .custom-sidebar .dropdown-toggle {
                color: #fff;
            }
            @media (max-width: 991px) {
                .custom-sidebar {
                    position: fixed;
                    width: 100vw;
                    min-height: 60px;
                    height: auto;
                    flex-direction: row;
                    align-items: center;
                    padding: 0.5rem 1rem;
                }
                .custom-sidebar .logo-title {
                    font-size: 1.5rem;
                    margin-right: 1rem;
                }
                .custom-sidebar ul.nav {
                    flex-direction: row;
                    margin-bottom: 0;
                }
                .custom-sidebar .nav-item {
                    margin-right: 1rem;
                }
                .custom-sidebar .dropdown-menu {
                    position: absolute !important;
                }
            }
            @media (max-width: 575px) {
                .custom-sidebar {
                    flex-direction: column;
                    width: 100vw;
                    min-height: 60px;
                    padding: 0.5rem 0.5rem;
                }
                .custom-sidebar ul.nav {
                    flex-direction: column;
                }
                .custom-sidebar .nav-item {
                    margin-right: 0;
                }
            }
            .modal-content.modal-purple {
                background-color: #f3e8ff !important;
            }
            .modal-content.modal-purple .modal-header {
                background-color: #6f42c1 !important;
                color: #fff !important;
            }
            .modal-content.modal-purple .modal-footer {
                background-color: #ede7f6 !important;
            }
        </style>';
        // ...modais de despesas e saldo, scripts de limpeza, etc...
        // MODAL PAGAMENTO
        echo '<div class="modal fade" id="modalDespesasMes" tabindex="-1" aria-labelledby="modalDespesasMesLabel" aria-hidden="true">';
        echo '  <div class="modal-dialog">';
        echo '    <div class="modal-content modal-purple">';
        echo '      <div class="modal-header">';
        echo '        <h5 class="modal-title" id="modalDespesasMesLabel">Pagamentos por mês e ano</h5>';
        echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '        <form method="GET" action="relatorio_despesas.php" target="_blank" id="formDespesas">';
        echo '          <input type="hidden" name="consultar_editora" value="1">';
        echo '          <div class="form-group">';
        echo '            <label for="mes_despesas">Mês</label>';
        echo '            <select class="form-control" name="mes" id="mes_despesas">';
        foreach ($nomesMeses as $num => $nome) {
            $selected = ($num == $mesAtual) ? "selected" : "";
            echo "<option value=\"$num\" $selected>$nome</option>";
        }
        echo '            </select>';
        echo '          </div>';
        echo '          <div class="form-group">';
        echo '            <label for="ano_despesas">Ano</label>';
        echo '            <input type="number" class="form-control" name="ano" id="ano_despesas" value="' . $anoAtual . '" required>';
        echo '          </div>';
        echo '          <div class="modal-footer">';
        echo '            <button type="submit" class="btn btn-primary">Consultar</button>';
        echo '            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
        echo '          </div>';
        echo '        </form>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
        // MODAL SALDO
        echo '<div class="modal fade" id="modalSaldoMes" tabindex="-1" aria-labelledby="modalSaldoMesLabel" aria-hidden="true">';
        echo '  <div class="modal-dialog">';
        echo '    <div class="modal-content modal-purple">';
        echo '      <div class="modal-header">';
        echo '        <h5 class="modal-title" id="modalSaldoMesLabel">Saldo por mês e ano</h5>';
        echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '        <form method="GET" action="relatorio_saldo.php" target="_blank" id="formSaldo">';
        echo '          <input type="hidden" name="consultar_editora" value="1">';
        echo '          <div class="form-group">';
        echo '            <label for="mes_saldo">Mês</label>';
        echo '            <select class="form-control" name="mes" id="mes_saldo">';
        foreach ($nomesMeses as $num => $nome) {
            $selected = ($num == $mesAtual) ? "selected" : "";
            echo "<option value=\"$num\" $selected>$nome</option>";
        }
        echo '            </select>';
        echo '          </div>';
        echo '          <div class="form-group">';
        echo '            <label for="ano_saldo">Ano</label>';
        echo '            <input type="number" class="form-control" name="ano" id="ano_saldo" value="' . $anoAtual . '" required>';
        echo '          </div>';
        echo '          <div class="modal-footer">';
        echo '            <button type="submit" class="btn btn-primary">Consultar</button>';
        echo '            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
        echo '          </div>';
        echo '        </form>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
        // Script para limpar modais
        echo '<script>var modalReceitas = document.getElementById("modalReceitasMes");modalReceitas.addEventListener("hidden.bs.modal", function () {document.getElementById("mes_receitas").value = "' . $mesAtual . '";document.getElementById("ano_receitas").value = "' . $anoAtual . '";});var modalDespesas = document.getElementById("modalDespesasMes");modalDespesas.addEventListener("hidden.bs.modal", function () {document.getElementById("mes_despesas").value = "' . $mesAtual . '";document.getElementById("ano_despesas").value = "' . $anoAtual . '";});var modalSaldo = document.getElementById("modalSaldoMes");modalSaldo.addEventListener("hidden.bs.modal", function () {document.getElementById("mes_saldo").value = "' . $mesAtual . '";document.getElementById("ano_saldo").value = "' . $anoAtual . '";});</script>';

    //==============================MODAL RECEBIMENTO==============================
    // ---- MODAL Receitas/mês ----
    echo '<div class="modal fade" id="modalReceitasMes" tabindex="-1" aria-labelledby="modalReceitasMesLabel" aria-hidden="true">';
    echo '  <div class="modal-dialog">';
    echo '    <div class="modal-content modal-purple">';
    echo '      <div class="modal-header">';
    echo '        <h5 class="modal-title" id="modalReceitasMesLabel">Recebimentos por mês e ano</h5>';
    echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '      </div>';
    echo '      <div class="modal-body">';
    echo '        <form method="GET" action="relatorio_receitas.php" target="_blank" id="formReceitas">';
    echo '          <input type="hidden" name="consultar_editora" value="1">';

    // Select Meses
    echo '          <div class="form-group">';
    echo '            <label for="mes_receitas">Mês</label>';
    echo '            <select class="form-control" name="mes" id="mes_receitas">';
    $nomesMeses = [
        1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril",
        5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto",
        9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro",
    ];
    foreach ($nomesMeses as $num => $nome) {
        $selected = ($num == $mesAtual) ? "selected" : "";
        echo "<option value=\"$num\" $selected>$nome</option>";
    }
    echo '            </select>';
    echo '          </div>';

    // Input Ano
    echo '          <div class="form-group">';
    echo '            <label for="ano_receitas">Ano</label>';
    echo '            <input type="number" class="form-control" name="ano" id="ano_receitas" value="' . $anoAtual . '" required>';
    echo '          </div>';

    echo '          <div class="modal-footer">';
    echo '            <button type="submit" class="btn btn-primary">Consultar</button>';
    echo '            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
    echo '          </div>';
    echo '        </form>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';

    // CSS direto
    echo '<style>
        .modal-content.modal-purple {
            background-color: #f3e8ff !important; /* roxo clarinho */
        }
        .modal-content.modal-purple .modal-header {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }
        .modal-content.modal-purple .modal-footer {
            background-color: #ede7f6 !important;
        }
    </style>';

    //==============================MODAL PAGAMENTO==============================
    // ---- MODAL Despesas/mês ----
    echo '<div class="modal fade" id="modalDespesasMes" tabindex="-1" aria-labelledby="modalDespesasMesLabel" aria-hidden="true">';
    echo '  <div class="modal-dialog">';
    echo '    <div class="modal-content modal-purple">';
    echo '      <div class="modal-header">';
    echo '        <h5 class="modal-title" id="modalDespesasMesLabel">Pagamentos por mês e ano</h5>';
    echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '      </div>';
    echo '      <div class="modal-body">';
    echo '        <form method="GET" action="relatorio_despesas.php" target="_blank" id="formDespesas">';
    echo '          <input type="hidden" name="consultar_editora" value="1">';

    // Select Meses
    echo '          <div class="form-group">';
    echo '            <label for="mes_despesas">Mês</label>';
    echo '            <select class="form-control" name="mes" id="mes_despesas">';
    $nomesMeses = [
        1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril",
        5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto",
        9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro",
    ];
    foreach ($nomesMeses as $num => $nome) {
        $selected = ($num == $mesAtual) ? "selected" : "";
        echo "<option value=\"$num\" $selected>$nome</option>";
    }
    echo '            </select>';
    echo '          </div>';

    // Input Ano
    echo '          <div class="form-group">';
    echo '            <label for="ano_despesas">Ano</label>';
    echo '            <input type="number" class="form-control" name="ano" id="ano_despesas" value="' . $anoAtual . '" required>';
    echo '          </div>';

    echo '          <div class="modal-footer">';
    echo '            <button type="submit" class="btn btn-primary">Consultar</button>';
    echo '            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
    echo '          </div>';
    echo '        </form>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';

    // CSS direto
    echo '<style>
        .modal-content.modal-purple {
            background-color: #f3e8ff !important; /* roxo clarinho */
        }
        .modal-content.modal-purple .modal-header {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }
        .modal-content.modal-purple .modal-footer {
            background-color: #ede7f6 !important;
        }
    </style>';
    
    //==============================MODAL SALDO==============================
    // ---- MODAL Saldo/mês ----
    echo '<div class="modal fade" id="modalSaldoMes" tabindex="-1" aria-labelledby="modalSaldoMesLabel" aria-hidden="true">';
    echo '  <div class="modal-dialog">';
    echo '    <div class="modal-content modal-purple">';
    echo '      <div class="modal-header">';
    echo '        <h5 class="modal-title" id="modalSaldoMesLabel">Saldo por mês e ano</h5>';
    echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '      </div>';
    echo '      <div class="modal-body">';
    echo '        <form method="GET" action="relatorio_saldo.php" target="_blank" id="formSaldo">';
    echo '          <input type="hidden" name="consultar_editora" value="1">';

    // Select Meses
    echo '          <div class="form-group">';
    echo '            <label for="mes_saldo">Mês</label>';
    echo '            <select class="form-control" name="mes" id="mes_saldo">';
    $nomesMeses = [
        1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril",
        5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto",
        9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro",
    ];
    foreach ($nomesMeses as $num => $nome) {
        $selected = ($num == $mesAtual) ? "selected" : "";
        echo "<option value=\"$num\" $selected>$nome</option>";
    }
    echo '            </select>';
    echo '          </div>';

    // Input Ano
    echo '          <div class="form-group">';
    echo '            <label for="ano_saldo">Ano</label>';
    echo '            <input type="number" class="form-control" name="ano" id="ano_saldo" value="' . $anoAtual . '" required>';
    echo '          </div>';

    echo '          <div class="modal-footer">';
    echo '            <button type="submit" class="btn btn-primary">Consultar</button>';
    echo '            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
    echo '          </div>';
    echo '        </form>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';

    // CSS direto
    echo '<style>
        .modal-content.modal-purple {
            background-color: #f3e8ff !important; /* roxo clarinho */
        }
        .modal-content.modal-purple .modal-header {
            background-color: #6f42c1 !important;
            color: #fff !important;
        }
        .modal-content.modal-purple .modal-footer {
            background-color: #ede7f6 !important;
        }
    </style>';

    // ============================= SCRIPT PARA LIMPAR MODAIS AO FECHAR =============================
    echo '<script>
        // Limpa os campos do formulário do modal de Recebimentos quando ele for fechado
        var modalReceitas = document.getElementById("modalReceitasMes");
        modalReceitas.addEventListener("hidden.bs.modal", function () {
            document.getElementById("mes_receitas").value = "' . $mesAtual . '";
            document.getElementById("ano_receitas").value = "' . $anoAtual . '";
        });

        // Limpa os campos do formulário do modal de Pagamentos quando ele for fechado
        var modalDespesas = document.getElementById("modalDespesasMes");
        modalDespesas.addEventListener("hidden.bs.modal", function () {
            document.getElementById("mes_despesas").value = "' . $mesAtual . '";
            document.getElementById("ano_despesas").value = "' . $anoAtual . '";
        });

        // Limpa os campos do formulário do modal de Saldo quando ele for fechado
        var modalSaldo = document.getElementById("modalSaldoMes");
        modalSaldo.addEventListener("hidden.bs.modal", function () {
            document.getElementById("mes_saldo").value = "' . $mesAtual . '";
            document.getElementById("ano_saldo").value = "' . $anoAtual . '";
        });
    </script>';
    }

    //==============================MOSTRAR MENSAGEM==============================

    public function mostrarMensagem($mensagem)
    {
        //<!-- Modal -->
        echo '<div class="modal fade" id="mensagem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo '  <div class="modal-dialog">';
        echo '    <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '        <h5 class="modal-title" id="exampleModalLabel">Informação</h5>';
        echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '        <div class="alert alert-warning" role="alert">';
        echo $mensagem;
        echo '        </div>';
        echo '      </div>';
        echo '      <div class="modal-footer">';
        echo '        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';

        //JS
        echo '<script>';
        echo '    document.addEventListener("DOMContentLoaded", function() {';
        echo '    var myModal = new bootstrap.Modal(document.getElementById("mensagem"));';
        echo '    myModal.show();});';
        echo '</script>';
    }

    //==============================BANCO==============================

    //inserir Banco
    public function inserir_banco($nome_banco, $num_agencia, $num_conta)
    {
        //instanciar a classe Banco
        $objBanco = new Banco();
        //invocar o método
        if ($objBanco->inserirBanco($nome_banco, $num_agencia, $num_conta) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objBanco->consultarBanco(null);
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Banco inserido com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir banco!");
        }
    }

    //consultar banco
    public function consultar_banco($nome_banco)
    {
        //instanciar a classe Autor
        $objBanco = new Banco();
        //invocar o método
        if ($objBanco->consultarBanco($nome_banco) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar banco!");
        } else {
            //iniciar sessao
            session_start();
            //resultado da consulta
            $resultado = $objBanco->consultarBanco($nome_banco);
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_banco.php';
        }
    }

    //alterar banco
    public function alterar_banco($id_cad_banco, $nome_banco, $num_agencia, $num_conta)
    {
        //instanciar a classe plano
        $objBanco = new Banco();
        //invocar o método
        if ($objBanco->alterarBanco($id_cad_banco, $nome_banco, $num_agencia, $num_conta) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objBanco->consultarBanco(null);
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Banco alterado com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar Banco!");
        }
    }

    //excluir banco
    public function excluir_banco($id_cad_banco)
    {
        //instanciar a classe Plano
        $objBanco = new Banco();
        //invocar o método
        if ($objBanco->excluirBanco($id_cad_banco) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Banco excluído com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_banco.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir Banco!");
        }
    }

    //select de banco
    public function selectBanco($id_cad_banco = null)
    {
        //instanciar a classe Banco
        $objBanco = new Banco();
        //invocar o método
        $resultado = $objBanco->consultarBanco(null);
        //montar o select dinamicamente
        echo '<label for="id_cad_banco" class="form-label">Banco:</label>';
        echo '<select name="id_cad_banco" class="form-select mb-3" aria-label="Default select example" required>';
        echo '    <option value="" selected>Selecione o banco</option>';
        foreach ($resultado as $key => $valor) {
            if ($valor->id_cad_banco == $id_cad_banco) {
                echo '<option selected value="' . $valor->id_cad_banco . '">' . $valor->nome_banco . '</option>';
            } else {
                echo '<option value="' . $valor->id_cad_banco . '">' . $valor->nome_banco . '</option>';
            }
        }
        echo '</select>';
    }

    public function modal_alterar_banco($id_cad_banco, $nome_banco, $num_agencia, $num_conta)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_banco' . $id_cad_banco . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar Banco</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        echo '     <label for="nome_banco" class="form-label">Banco:</label>';
        echo '     <input type="text" class="form-control mb-3" name="nome_banco" value="' . $nome_banco . '">';
        echo '     <label for="num_agencia" class="form-label">Agência:</label>';
        echo '     <input type="text" class="form-control mb-3" name="num_agencia" value="' . $num_agencia . '">';
        echo '     <label for="num_conta" class="form-label">Conta:</label>';
        echo '     <input type="text" class="form-control" name="num_conta" value="' . $num_conta . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_banco" value="' . $id_cad_banco . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_banco" class="btn btn-outline-info">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_banco($id_cad_banco, $nome_banco)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_banco' . $id_cad_banco . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir Banco</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo 'Deseja realmente excluir: ' . $nome_banco . ' ?';
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_banco" value="' . $id_cad_banco . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_banco" class="btn btn-outline-info">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    //==============================BANDEIRA==============================

    //inserir Bandeira
    public function inserir_bandeira($nome_bandeira)
    {
        //instanciar a classe Bandeira
        $objBandeira = new Bandeira();
        //invocar o método
        if ($objBandeira->inserirBandeira($nome_bandeira) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objBandeira->consultarBandeira(null);
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Bandeira inserida com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir bandeira!");
        }
    }

    //consultar bandeira
    public function consultar_bandeira($nome_bandeira)
    {
        //instanciar a classe Autor
        $objBandeira = new Bandeira();
        //invocar o método
        if ($objBandeira->consultarBandeira($nome_bandeira) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            //iniciar sessao
            session_start();
            //resultado da consulta
            $resultado = $objBandeira->consultarBandeira($nome_bandeira);
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_bandeira.php';
        }
    }

    //alterar bandeira
    public function alterar_bandeira($id_cad_band, $nome_band)
    {
        //instanciar a classe bandeira
        $objBandeira = new Bandeira();
        //invocar o método
        if ($objBandeira->alterarBandeira($id_cad_band, $nome_band) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objBandeira->consultarBandeira(null);
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Bandeira alterada com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar Bandeira!");
        }
    }

    //excluir bandeira
    public function excluir_bandeira($id_cad_band)
    {
        //instanciar a classe Bandeira
        $objBandeira = new Bandeira();
        //invocar o método
        if ($objBandeira->excluirBandeira($id_cad_band) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Bandeira excluída com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_bandeira.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir Bandeira!");
        }
    }

    //select de bandeira
    public function selectBandeira($id_cad_band = null)
    {
        //instanciar a classe Bandeira
        $objBandeira = new Bandeira();
        //invocar o método
        $resultado = $objBandeira->consultarBandeira(null);
        //montar o select dinamicamente
        echo '<label for="id_cad_band" class="form-label">Bandeira do cartão:</label>';
        echo '<select name="id_cad_band" class="form-select" aria-label="Default select example" required>';
        echo '    <option value="" selected>Selecione a bandeira do cartão</option>';
        foreach ($resultado as $key => $valor) {
            if ($valor->id_cad_band == $id_cad_band) {
                echo '<option selected value="' . $valor->id_cad_band . '">' . $valor->nome_band . '</option>';
            } else {
                echo '<option value="' . $valor->id_cad_band . '">' . $valor->nome_band . '</option>';
            }
        }
        echo '</select>';
    }

    public function modal_alterar_bandeira($id_cad_band, $nome_band)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_bandeira' . $id_cad_band . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar Bandeira</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        echo '       <label for="nome_band" class="form-label">Bandeira do cartão:</label>';
        echo '      <input type="text" class="form-control" name="nome_band" value="' . $nome_band . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_band" value="' . $id_cad_band . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_bandeira" class="btn btn-outline-info">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_bandeira($id_cad_band, $nome_band)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_bandeira' . $id_cad_band . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir Bandeira</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo 'Deseja realmente excluir: ' . $nome_band . ' ?';
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_band" value="' . $id_cad_band . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_bandeira" class="btn btn-outline-info">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    //==============================CARTÃO==============================

    //inserir cartão
    public function inserir_cartao($id_cad_band, $nome_cartao, $num_cartao)
    {
        //instanciar a classe Cartão
        $objCartao = new Cartao();
        //invocar o método
        if ($objCartao->inserirCartao($id_cad_band, $nome_cartao, $num_cartao) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objCartao->consultarCartao(null);
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Cartão inserido com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir cartão!");
        }
    }

    //consultar cartão
    public function consultar_cartao($nome_cartao)
    {
        //instanciar a classe Cartão
        $objCartao = new Cartao();
        //invocar o método
        if ($objCartao->consultarCartao($nome_cartao) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //resultado da consulta
            $resultado = $objCartao->consultarCartao($nome_cartao);
            //incluir a view
            include_once 'view/consultar_cartao.php';
        }
    }

    //alterar cartão
    public function alterar_cartao($id_cad_cartao, $id_cad_band, $nome_cartao, $num_cartao)
    {
        //instanciar a classe Cartão
        $objCartao = new Cartao();
        //invocar o método
        if ($objCartao->alterarCartao($id_cad_cartao, $id_cad_band, $nome_cartao, $num_cartao) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objCartao->consultarCartao(null);
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Cartão alterado com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar cartão!");
        }
    }

    //excluir cartão
    public function excluir_cartao($id_cad_cartao)
    {
        //instanciar a classe Cartão
        $objCartao = new Cartao();
        //invocar o método
        if ($objCartao->excluirCartao($id_cad_cartao) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Cartão excluído com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_cartao.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir cartão!");
        }
    }

    //select de cartão
    public function selectCartao($id_cad_cartao = null)
    {
        //instanciar a classe Cartão
        $objCartao = new Cartao();
        //invocar o método
        $resultado = $objCartao->consultarCartao(null);
        //montar o select dinamicamente
        echo '<label for="id_cad_cartao" class="form-label">Cartão:</label>';
        echo '<select name="id_cad_cartao" class="form-select mb-3" aria-label="Default select example" required>';
        echo '    <option value="" selected>Selecione o cartão</option>';
        foreach ($resultado as $key => $valor) {
            if ($valor->id_cad_cartao == $id_cad_cartao) {
                echo '<option selected value="' . $valor->id_cad_cartao . '">' . $valor->nome_cartao . '</option>';
            } else {
                echo '<option value="' . $valor->id_cad_cartao . '">' . $valor->nome_cartao . '</option>';
            }
        }
        echo '</select>';
    }

    public function modal_alterar_cartao($id_cad_cartao, $id_cad_band, $nome_cartao, $num_cartao)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_cartao' . $id_cad_cartao . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar Cartão</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        $this->selectBandeira($id_cad_band);
        echo '     <label for="nome_cartao" class="form-label mt-3">Nome do cartão:</label>';
        echo '     <input type="text" class="form-control mb-3" name="nome_cartao" value="' . $nome_cartao . '">';
        echo '     <label for="num_cartao" class="form-label">Número do cartão:</label>';
        echo '     <input type="text" class="form-control mb-3" name="num_cartao" value="' . $num_cartao . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_cartao" value="' . $id_cad_cartao . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_cartao" class="btn btn-outline-info">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_cartao($id_cad_cartao, $nome_cartao)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_cartao' . $id_cad_cartao . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir Cartão</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo 'Deseja realmente excluir: ' . $nome_cartao . ' ?';
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_cartao" value="' . $id_cad_cartao . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_cartao" class="btn btn-outline-info">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    //==============================FORMA==============================

    //inserir forma
    public function inserir_forma($desc_forma)
    {
        //instanciar a classe forma
        $objForma = new Forma();
        //invocar o método
        if ($objForma->inserirForma($desc_forma) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objForma->consultarForma(null);
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Forma inserida com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir forma!");
        }
    }

    //consultar forma
    public function consultar_forma($desc_forma)
    {
        //instanciar a classe Autor
        $objForma = new Forma();
        //invocar o método
        if ($objForma->consultarForma($desc_forma) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar forma!");
        } else {
            //iniciar sessao
            session_start();
            //resultado da consulta
            $resultado = $objForma->consultarForma($desc_forma);
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_forma.php';
        }
    }

    //alterar forma
    public function alterar_forma($id_cad_forma, $desc_forma)
    {
        //instanciar a classe forma
        $objForma = new Forma();
        //invocar o método
        if ($objForma->alterarForma($id_cad_forma, $desc_forma) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objForma->consultarForma(null);
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Forma alterada com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar forma!");
        }
    }

    //excluir forma
    public function excluir_forma($id_cad_forma)
    {
        //instanciar a classe Bandeira
        $objForma = new Forma();
        //invocar o método
        if ($objForma->excluirForma($id_cad_forma) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Forma excluída com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_forma.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir forma!");
        }
    }

    //select de forma
    public function selectForma($id_cad_forma = null)
    {
        //instanciar a classe Forma
        $objForma = new Forma();
        //invocar o método
        $resultado = $objForma->consultarForma(null);
        //montar o select dinamicamente
        echo '<label for="id_cad_forma" class="form-label">Forma de Rec/Pag:</label>';
        echo '<select name="id_cad_forma" class="form-select mb-3" aria-label="Default select example" required>';
        echo '    <option value="" selected>Selecione a forma de rec/pag</option>';
        foreach ($resultado as $key => $valor) {
            if ($valor->id_cad_forma == $id_cad_forma) {
                echo '<option selected value="' . $valor->id_cad_forma . '">' . $valor->desc_forma . '</option>';
            } else {
                echo '<option value="' . $valor->id_cad_forma . '">' . $valor->desc_forma . '</option>';
            }
        }
        echo '</select>';
    }

    public function modal_alterar_forma($id_cad_forma, $desc_forma)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_forma' . $id_cad_forma . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar Forma</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        echo '     <label for="desc_forma" class="form-label mt-3">Forma de Rec/Pag:</label>';
        echo '     <input type="text" class="form-control" name="desc_forma" value="' . $desc_forma . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_forma" value="' . $id_cad_forma . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_forma" class="btn btn-outline-info">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_forma($id_cad_forma, $desc_forma)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_forma' . $id_cad_forma . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir Forma</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo 'Deseja realmente excluir: ' . $desc_forma . ' ?';
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_forma" value="' . $id_cad_forma . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_forma" class="btn btn-outline-info">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    //==============================LANÇAMENTO==============================

    //inserir lançamento
    public function inserir_lancamento($id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag)
    {
        //instanciar a classe Cartão
        $objLancamento = new Lancamento();
        //invocar o método
        if ($objLancamento->inserirLancamento($id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objLancamento->consultarLancamento(null);
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Lançamento inserido com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir lançamento!");
        }
    }

    //consultar lançamento
    public function consultar_lancamento($desc_lanc)
    {
        //instanciar a classe Cartão
        $objLancamento = new Lancamento();
        //invocar o método
        if ($objLancamento->consultarLancamento($desc_lanc) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //resultado da consulta
            $resultado = $objLancamento->consultarLancamento($desc_lanc);
            //incluir a view
            include_once 'view/consultar_lancamento.php';
        }
    }

    //alterar lançamento
    public function alterar_lancamento($id_lanc, $id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag)
    {
        //instanciar a classe lançamento
        $objLancamento = new Lancamento();
        //invocar o método
        if ($objLancamento->alterarLancamento($id_lanc, $id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objLancamento->consultarLancamento(null);
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Lançamento alterado com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar lançamento!");
        }
    }

    //excluir lancamento
    public function excluir_lancamento($id_lanc)
    {
        //instanciar a classe lançamento
        $objLancamento = new Lancamento();
        //invocar o método
        if ($objLancamento->excluirLancamento($id_lanc) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Lançamento excluído com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_lancamento.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir lançamento!");
        }
    }

    public function modal_alterar_lancamento($id_lanc, $id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_lancamento' . $id_lanc . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar lançamento</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        $this->selectTipo($id_cad_tipo);
        $this->selectPlano($id_cad_plano);
        echo '      <label for="desc_lanc" class="form-label">Descrição do lançamento:</label>';
        echo '      <input type="text" class="form-control mb-3" name="desc_lanc" value="' . $desc_lanc . '">';
        echo '      <label for="data_venc" class="form-label">Data de vencimento:</label>';
        echo '      <input type="date" class="form-control mb-3" name="data_venc" value="' . $data_venc . '">';
        echo '      <label for="valor_lanc" class="form-label">Valor:</label>';
        echo '      <input type="text" step="any" class="form-control mb-3" name="valor_lanc" value="' . $valor_lanc . '">';
        $this->selectForma($id_cad_forma);
        $this->selectBanco($id_cad_banco);
        $this->selectCartao($id_cad_cartao);
        echo '      <label for="data_rec_pag" class="form-label">Data rec/pag:</label>';
        echo '      <input type="date" class="form-control" name="data_rec_pag" value="' . $data_rec_pag . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_lanc" value="' . $id_lanc . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_lancamento" class="btn btn-outline-info">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_lancamento($id_lanc, $desc_lanc)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_lancamento' . $id_lanc . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir lançamento</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo 'Deseja realmente excluir: ' . $desc_lanc . ' ?';
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_lanc" value="' . $id_lanc . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_lancamento" class="btn btn-outline-info">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    //==============================PLANO==============================

    //inserir plano
    public function inserir_plano($desc_plano)
    {
        //instanciar a classe forma
        $objPlano = new Plano();
        //invocar o método
        if ($objPlano->inserirPlano($desc_plano) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objPlano->consultarPlano(null);
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Plano de contas inserido com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir plano de contas!");
        }
    }

    //consultar plano
    public function consultar_plano($desc_plano)
    {
        //instanciar a classe plano
        $objPlano = new Plano();
        //invocar o método
        if ($objPlano->consultarPlano($desc_plano) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar plano de contas!");
        } else {
            //iniciar sessao
            session_start();
            //resultado da consulta
            $resultado = $objPlano->consultarPlano($desc_plano);
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_plano.php';
        }
    }

    //alterar plano
    public function alterar_plano($id_cad_plano, $desc_plano)
    {
        //instanciar a classe plano
        $objPlano = new Plano();
        //invocar o método
        if ($objPlano->alterarPlano($id_cad_plano, $desc_plano) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objPlano->consultarPlano(null);
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Plano de contas alterado com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar plano de contas!");
        }
    }

    //excluir plano
    public function excluir_plano($id_cad_plano)
    {
        //instanciar a classe Plano
        $objPlano = new Plano();
        //invocar o método
        if ($objPlano->excluirPlano($id_cad_plano) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Plano de contas excluído com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_plano.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir plano de contas!");
        }
    }

    //select de plano
    public function selectPlano($id_cad_plano = null)
    {
        //instanciar a classe Plano
        $objPlano = new Plano();
        //invocar o método
        $resultado = $objPlano->consultarPlano(null);
        //montar o select dinamicamente
        echo '<label for="id_cad_plano" class="form-label">Plano de contas:</label>';
        echo '<select name="id_cad_plano" class="form-select mb-3" aria-label="Default select example" required>';
        echo '    <option value="" selected>Selecione o plano de contas</option>';
        foreach ($resultado as $key => $valor) {
            if ($valor->id_cad_plano == $id_cad_plano) {
                echo '<option selected value="' . $valor->id_cad_plano . '">' . $valor->desc_plano . '</option>';
            } else {
                echo '<option value="' . $valor->id_cad_plano . '">' . $valor->desc_plano . '</option>';
            }
        }
        echo '</select>';
    }

    public function modal_alterar_plano($id_cad_plano, $desc_plano)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_plano' . $id_cad_plano . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar Plano</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        echo '     <label for="desc_plano" class="form-label mt-3">Plano de contas:</label>';
        echo '     <input type="text" class="form-control" name="desc_plano" value="' . $desc_plano . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_plano" value="' . $id_cad_plano . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_plano" class="btn btn-outline-info">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_plano($id_cad_plano, $desc_plano)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_plano' . $id_cad_plano . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir Plano</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo 'Deseja realmente excluir: ' . $desc_plano . ' ?';
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_plano" value="' . $id_cad_plano . '">';
        echo '    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_plano" class="btn btn-outline-info">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    //==============================TIPO==============================

    //select de tipo
    public function selectTipo($id_cad_tipo = null)
    {
        //instanciar a classe Tipo
        $objTipo = new Tipo();
        //invocar o método
        $resultado = $objTipo->consultarTipo(null);
        //montar o select dinamicamente
        echo '<label for="id_cad_tipo" class="form-label">Tipo: </label>';
        echo '<select name="id_cad_tipo" class="form-select mb-3" aria-label="Default select example" required>';
        echo '<option value="" selected >Selecione o tipo</option>';
        foreach ($resultado as $key => $valor) {
            if ($valor->id_cad_tipo == $id_cad_tipo) {
                echo '<option selected value="' . $valor->id_cad_tipo . '">' . $valor->desc_tipo . '</option>';
            } else {
                echo '<option value="' . $valor->id_cad_tipo . '">' . $valor->desc_tipo . '</option>';
            }
        }
        echo '</select>';
    }

    //==============================USUÁRIO==============================

    //inserir usuário
    public function inserir_usuario($nome_usuario, $email, $senha)
    {
        //instanciar a classe Autor
        $objUsuario = new Usuario();
        //invocar o método
        if ($objUsuario->inserirUsuario($nome_usuario, $email, $senha) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objUsuario->consultarUsuario(null);
            //incluir a view
            include_once 'view/consultar_usuario.php';
            //mostrar mensagem
            $this->mostrarMensagem("Usuário inserido com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_usuario.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao inserir Usuário!");
        }
    }

    //consultar usuario
    public function consultar_usuario($nome_usuario)
    {
        //instanciar a classe Usuario
        $objUsuario = new Usuario();
        //invocar o método
        if ($objUsuario->consultarUsuario($nome_usuario) == false) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_usuario.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao consultar banco!");
        } else {
            //iniciar sessao
            session_start();
            //resultado da consulta
            $resultado = $objUsuario->consultarUsuario($nome_usuario);
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_usuario.php';
        }
    }

    //alterar usuario
    public function alterar_usuario($id_cad_usuario, $nome_usuario, $email, $senha)
    {
        //instanciar a classe usuario
        $objUsuario = new Usuario();
        //invocar o método
        if ($objUsuario->alterarUsuario($id_cad_usuario, $nome_usuario, $email, $senha) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu      = $this->menu();
            $resultado = $objUsuario->consultarUsuario(null);
            //incluir a view
            include_once 'view/consultar_usuario.php';
            //mostrar mensagem
            $this->mostrarMensagem("Usuario alterado com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_usuariophp';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao alterar Usuario!");
        }
    }

    //excluir usuario
    public function excluir_usuario($id_cad_usuario)
    {
        //instanciar a classe Plano
        $objUsuario = new Usuario();
        //invocar o método
        if ($objUsuario->excluirUsuario($id_cad_usuario) == true) {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_usuario.php';
            //mostrar mensagem
            $this->mostrarMensagem("Usuário excluído com sucesso!");
        } else {
            //iniciar sessao
            session_start();
            //inserir menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/consultar_usuario.php';
            //mostrar mensagem
            $this->mostrarMensagem("Erro ao excluir usuário!");
        }
    }

    public function modal_alterar_usuario($id_cad_usuario, $nome_usuario, $email, $senha)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="alterar_usuario' . $id_cad_usuario . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Alterar Usuário</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '<form method="post" action="index.php">';
        echo '  <div class="modal-body">';
        echo '     <label for="nome_usuario" class="form-label">Usuário:</label>';
        echo '     <input type="text" class="form-control" name="nome_usuario" value="' . $nome_usuario . '">';
        echo '     <label for="email" class="form-label">E-mail:</label>';
        echo '     <input type="email" class="form-control" name="email" value="' . $email . '">';
        echo '     <label for="senha" class="form-label">Senha:</label>';
        echo '     <input type="password" class="form-control" name="password" value="' . $senha . '">';
        echo '  </div>';
        echo '  <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_usuario" value="' . $id_cad_usuario . '">';
        echo '    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="alterar_usuario" class="btn btn-primary">Alterar</button>';
        echo '  </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function modal_excluir_usuario($id_cad_usuario, $nome_usuario)
    {
        echo '<!-- Modal -->';
        echo '<div class="modal fade" id="excluir_usuario' . $id_cad_usuario . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo ' <div class="modal-dialog">';
        echo '     <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '         <h5 class="modal-title" id="exampleModalLabel">Excluir Usuario</h5>';
        echo '         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '  <div class="modal-body">';
        echo $nome_usuario;
        echo '  </div>';
        echo '<form method="post" action="index.php">';
        echo ' <div class="modal-footer">';
        echo '    <input type="hidden" name="id_cad_usuario" value="' . $id_cad_usuario . '">';
        echo '    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
        echo '    <button type="submit" name="excluir_usuario" class="btn btn-danger">Excluir</button>';
        echo ' </div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function viewReceita()
    {
        //instanciar a classe Saldo
        $objLancamento = new Lancamento();
        //invocar o método
        $resultado = $objLancamento->viewReceita();
        foreach ($resultado as $total_recebimento) {
            $recebimentoMes = 'Receitas Mês Atual <br><br> R$ ' . number_format($total_recebimento->total_recebimento, 2, ',', '.');
        }

        return $recebimentoMes;
    }

        public function viewDespesa()
    {
        //instanciar a classe Saldo
        $objLancamento = new Lancamento();
        //invocar o método
        $resultado = $objLancamento->viewDespesa();
        foreach ($resultado as $total_pagamento) {
            $pagamentoMes = 'Despesas Mês Atual <br><br> R$ ' . number_format($total_pagamento->total_pagamento, 2, ',', '.');
        }

        return $pagamentoMes;
    }

    public function viewSaldo()
    {
        //instanciar a classe Saldo
        $objLancamento = new Lancamento();
        //invocar o método
        $resultado = $objLancamento->viewSaldo();
        foreach ($resultado as $saldo) {
            $saldoMes = 'Saldo Mês Atual <br><br> R$ ' . number_format($saldo->saldo, 2, ',', '.');
        }

        return $saldoMes;
    }
}
