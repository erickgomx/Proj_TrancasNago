<?php
// perfil.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '123', 'trancas_nago');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$nomeUsuario = $_SESSION['usuario_nome'];

// Busca serviços e dias disponíveis do banco de dados
$sqlServicos = "SELECT id, nome, preco, imagem FROM servicos";
$resultServicos = $conn->query($sqlServicos);
$servicos = [];
if ($resultServicos->num_rows > 0) {
    while ($row = $resultServicos->fetch_assoc()) {
        $servicos[] = $row;
    }
}

$sqlHorarios = "SELECT dia, hora, status FROM horarios ORDER BY dia, hora";
$resultHorarios = $conn->query($sqlHorarios);
$horarios = [];
if ($resultHorarios->num_rows > 0) {
    while ($row = $resultHorarios->fetch_assoc()) {
        $horarios[$row['dia']][] = ['hora' => $row['hora'], 'status' => $row['status']];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Trancas Nagô</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdf2f8;
            color: #333;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            text-align: center;
            color: #d63384;
            margin-bottom: 20px;
            font-size: 2.5rem;
        }
        h2 {
            text-align: center;
            color: #d63384;
            margin-top: 40px;
            font-size: 2rem;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .service {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fde8f0;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .service img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .calendar .day {
            margin-bottom: 20px;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .calendar button {
            margin: 5px 5px 0 0;
        }
        .btn-pink {
            background-color: #d63384;
            color: #fff;
            transition: all 0.3s ease;
        }
        .btn-pink:hover {
            background-color: #c22270;
        }
        .logout-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: #d63384;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .logout-btn:hover {
            background: #c22270;
        }
        .text-center button {
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1 class="fw-bold">AGENDA - Trancas Nagô</h1>
    <div class="container">
        <!-- Lista de serviços -->
        <div>
            <?php foreach ($servicos as $servico): ?>
                <div class="service">
                    <img src="../img/<?php echo htmlspecialchars($servico['imagem']); ?>" alt="<?php echo htmlspecialchars($servico['nome']); ?>">
                    <div class="text-center">
                        <strong><?php echo htmlspecialchars($servico['nome']); ?></strong>
                        <p class="mb-0">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
                    </div>
                    <button class="btn btn-success selecionar-btn" data-id="<?php echo $servico['id']; ?>">Selecionar</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
        <!-- Calendário dinâmico -->
        <h2 class="fw-bold">SELECIONE O DIA E HORÁRIO</h2>
        <div class="calendar">
            <?php foreach ($horarios as $dia => $horas): ?>
                <div class="day">
                    <h5 class="fw-bold text-pink mb-2">Dia <?php echo htmlspecialchars($dia); ?></h5>
                    <?php foreach ($horas as $hora): ?>
                        <button class="btn <?php echo $hora['status'] === 'Lotado' ? 'btn-danger' : 'btn-success'; ?> hora-btn" 
                                data-dia="<?php echo $dia; ?>" data-hora="<?php echo $hora['hora']; ?>"
                                <?php echo $hora['status'] === 'Lotado' ? 'disabled' : ''; ?>>
                            <?php echo htmlspecialchars($hora['hora']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <a href="../php/remover_sessao.php" class="logout-btn">Sair</a>
    <div class="text-center">
        <button class="btn btn-secondary" onclick="window.history.back();">Voltar</button>
        <button id="confirmar-btn" class="btn btn-pink">Confirmar</button>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let selectedService = null;
    let selectedDay = null;
    let selectedHour = null;

    document.querySelectorAll('.selecionar-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.selecionar-btn').forEach(btn => {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-success');
                btn.innerText = 'Selecionar';
            });
            button.classList.remove('btn-success');
            button.classList.add('btn-secondary');
            button.innerText = 'Selecionado';
            selectedService = button.dataset.id;
        });
    });

    document.querySelectorAll('.hora-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.hora-btn').forEach(btn => btn.classList.remove('btn-warning'));
            button.classList.add('btn-warning');
            selectedDay = button.dataset.dia;
            selectedHour = button.dataset.hora;
        });
    });

    document.getElementById('confirmar-btn').addEventListener('click', () => {
        if (selectedService && selectedDay && selectedHour) {
            window.location.href = `../checkout.html?servico=${selectedService}&dia=${selectedDay}&hora=${selectedHour}`;
        } else {
            alert('Por favor, selecione um serviço, dia e horário antes de confirmar.');
        }
    });
</script>
</body>
</html>
