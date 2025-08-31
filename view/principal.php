<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - SFP-GZ</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .dashboard-card {
                background: #f3e8ff;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(111,66,193,0.08);
                padding: 1.5rem;
                margin-bottom: 2rem;
                position: relative;
                z-index: 2;
            }
            .dashboard-title {
                color: #7c3aed;
                font-weight: bold;
                letter-spacing: 2px;
            }

            /* Adicionar um efeito de vidro ao fundo das divs */
            .glass-effect {
                background: rgba(255, 255, 255, 0.85); /* Menos transparência para não sumir atrás do fundo */
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 10px;
                position: relative;
                z-index: 2;
            }

            /* Ajustar fundo da página para destacar o efeito */
            body {
                background: url('https://via.placeholder.com/1920x1080') no-repeat center center fixed;
                background-size: cover;
            }
        </style>
    </head>
    <body class="vh-100" style="background: url('images/finanças_pessoais.jpeg') no-repeat center center; background-size: cover;">
        <?php
        require_once dirname(__DIR__) . '/model/Lancamento.class.php';
        $lancamento = new Lancamento();
        $receitasPorMes = $lancamento->getReceitasPorMesAnoAtual();
        $despesasPorMes = $lancamento->getDespesasPorMesAnoAtual();
        $receitas = array_fill(0, 12, 0);
        $despesas = array_fill(0, 12, 0);
        if ($receitasPorMes) {
            foreach ($receitasPorMes as $item) {
                $receitas[$item->mes - 1] = (float)$item->total;
            }
        }
        if ($despesasPorMes) {
            foreach ($despesasPorMes as $item) {
                $despesas[$item->mes - 1] = (float)$item->total;
            }
        }
        // Buscar composição das despesas por categoria/plano
        $composicaoDespesas = [];
        try {
            $bd = $lancamento->conectar();
            $sql = "SELECT tp.desc_plano as categoria, SUM(valor_lanc) as total FROM db_financaspi.tb_lancamento tl LEFT JOIN db_financaspi.tb_cad_plano tp ON tp.id_cad_plano = tl.id_cad_plano WHERE tl.id_cad_tipo = 2 AND YEAR(tl.data_venc) = YEAR(CURDATE()) GROUP BY tp.desc_plano ORDER BY total DESC";
            $query = $bd->prepare($sql);
            $query->execute();
            $composicaoDespesas = $query->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            $composicaoDespesas = [];
        }
        $categoriasDespesas = array_map(function($item) { return $item->categoria; }, $composicaoDespesas);
        $valoresDespesas = array_map(function($item) { return (float)$item->total; }, $composicaoDespesas);
        ?>
        <header>
            <!-- place navbar here -->
            <?php echo $menu ?>
        </header>
        <main>
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <!-- Título removido -->
                            <p class="fs-3 text-success"><?php echo $this->viewReceita(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <!-- Título removido -->
                            <p class="fs-3 text-danger"><?php echo $this->viewDespesa(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card glass-effect text-center">
                            <!-- Título removido -->
                            <p class="fs-3 text-primary">
                                <?php 
                                require_once dirname(__DIR__) . '/controller/Controller.class.php';
                                $controller = new Controller();
                                echo $controller->viewSaldo(); 
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Card moderno: Composição das Despesas -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="dashboard-card glass-effect text-center">
                            <h5 class="mb-3">Composição das Despesas</h5>
                            <canvas id="graficoComposicaoDespesas" height="120"></canvas>
                        </div>
                    </div>
                </div>
    <div class="dashboard-card" style="width: 50%; margin-left: 0; margin-right: auto;">
            <h5 class="mb-3">Comparativo Mensal: Receitas x Despesas</h5>
            <canvas id="graficoFinanceiro" height="120"></canvas>
        </div>

    <!-- Card moderno: Saldo acumulado de cada mês -->
    <div class="dashboard-card" style="width: 50%; margin-left: 0; margin-right: auto;">
        <h5 class="mb-3">Saldo Acumulado por Mês</h5>
        <canvas id="graficoSaldoAcumulado" height="120"></canvas>

    </div>
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
            const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            const receitas = <?php echo json_encode($receitas); ?>;
            const despesas = <?php echo json_encode($despesas); ?>;
            // Dados para gráfico de rosca (composição das despesas)
            const categoriasDespesas = <?php echo json_encode($categoriasDespesas); ?>;
            const valoresDespesas = <?php echo json_encode($valoresDespesas); ?>;
            const ctx = document.getElementById('graficoFinanceiro').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [
                        {
                            label: 'Receitas',
                            data: receitas,
                            backgroundColor: '#7c3aed',
                        },
                        {
                            label: 'Despesas',
                            data: despesas,
                            backgroundColor: '#e53e3e',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico de rosca para composição das despesas
            const ctxComposicao = document.getElementById('graficoComposicaoDespesas').getContext('2d');
            new Chart(ctxComposicao, {
                type: 'doughnut',
                data: {
                    labels: categoriasDespesas,
                    datasets: [{
                        label: 'Despesas',
                        data: valoresDespesas,
                        backgroundColor: [
                            '#7c3aed', '#e53e3e', '#38bdf8', '#fbbf24', '#22c55e', '#f472b6', '#818cf8', '#f59e42', '#10b981', '#a3e635', '#f43f5e', '#6366f1'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: false }
                    }
                }
            });

            // Saldo acumulado por mês
            const saldoAcumulado = meses.map((_, i) => {
                let saldo = 0;
                for (let j = 0; j <= i; j++) {
                    saldo += (receitas[j] || 0) - (despesas[j] || 0);
                }
                return saldo;
            });
            const ctxSaldo = document.getElementById('graficoSaldoAcumulado').getContext('2d');
            new Chart(ctxSaldo, {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Saldo Acumulado',
                        data: saldoAcumulado,
                        fill: true,
                        backgroundColor: 'rgba(124,58,237,0.15)',
                        borderColor: '#7c3aed',
                        tension: 0.4,
                        pointBackgroundColor: '#7c3aed',
                        pointBorderColor: '#fff',
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    </body>
</html>