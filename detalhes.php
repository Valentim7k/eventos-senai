<?php
require_once 'init.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || !isset($_SESSION['eventos'][$id])) {
    $erro = 'Evento não encontrado.';
} else {
    // Garante que eventos antigos tenham a lista de inscritos
    if (!isset($_SESSION['eventos'][$id]['inscritos'])) {
        $_SESSION['eventos'][$id]['inscritos'] = [];
    }

     // Garante que eventos antigos tenham capacidade
    if (!isset($_SESSION['eventos'][$id]['capacidade'])) {
    $_SESSION['eventos'][$id]['capacidade'] = 30;
}
    $evento = $_SESSION['eventos'][$id];

    $erros = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validação do nome
        if ($nome === '') {
            $erros[] = 'O nome é obrigatório.';
        }

        // Validação do e-mail
        if ($email === '') {
            $erros[] = 'O e-mail é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        }

        // Verifica e-mail duplicado neste evento
        foreach ($_SESSION['eventos'][$id]['inscritos'] as $inscrito) {
            if (strcasecmp($inscrito['email'], $email) === 0) {
                $erros[] = 'Este e-mail já está inscrito neste evento.';
                break;
            }
        }

        // Se não houver erros, salva a inscrição
        if (empty($erros)) {

            $_SESSION['eventos'][$id]['inscritos'][] = [
                'nome' => $nome,
                'email' => $email
            ];

            header('Location: detalhes.php?id=' . $id);
            exit;
        }

        // Atualiza o evento depois da tentativa de inscrição
        $evento = $_SESSION['eventos'][$id];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do evento</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <div class="container">
        <h1>Detalhes do evento</h1>
        <?php require 'menu.php'; ?>
    </div>
</header>

<main class="container">

    <?php if (isset($erro)): ?>

        <div class="error">
            <?= e($erro) ?>
        </div>

        <a class="btn" href="index.php">Voltar</a>

    <?php else: ?>

        <section class="card">

            <h2><?= e($evento['titulo']) ?></h2>

            <dl class="details">

                <dt>ID</dt>
                <dd><?= e($evento['id']) ?></dd>

                <dt>Descrição</dt>
                <dd><?= nl2br(e($evento['descricao'])) ?></dd>

                <dt>Área</dt>
                <dd><?= e($evento['area']) ?></dd>

                <dt>Data</dt>
                <dd><?= e($evento['data']) ?></dd>

                <dt>Início</dt>
                <dd><?= e($evento['inicio']) ?></dd>

                <dt>Fim</dt>
                <dd><?= e($evento['fim']) ?></dd>

                <dt>Local</dt>
                <dd><?= e($evento['local']) ?></dd>

                <dt>Responsável</dt>
                <dd><?= e($evento['responsavel']) ?></dd>

                <dt>Capacidade</dt>
                <dd><?= e ($evento['capacidade']) ?></dd>

                <dt>Vagas Disponíveis</dt>
                <dd><?= e($evento['capacidade'] - count($evento['inscritos'])) ?></dd>

            </dl>

        </section>


        <!-- FORMULÁRIO DE INSCRIÇÃO -->

        <section class="card">

            <h2>Inscrição no evento</h2>

            <?php if (!empty($erros)): ?>

                <div class="error">

                    <?php foreach ($erros as $erroInscricao): ?>

                        <p><?= e($erroInscricao) ?></p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <form method="POST">

                <label for="nome">Nome:</label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= e($_POST['nome'] ?? '') ?>"
                    required
                >


                <label for="email">E-mail:</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                >


                <button type="submit" class="btn">
                    Inscrever-se
                </button>

            </form>

        </section>


        <!-- LISTA DE INSCRITOS -->

        <section class="card">

            <h2>Participantes inscritos</h2>

            <?php if (empty($evento['inscritos'])): ?>

                <p>Nenhum participante inscrito.</p>

            <?php else: ?>

                <ul>

                    <?php foreach ($evento['inscritos'] as $inscrito): ?>

                        <li>
                            <?= e($inscrito['nome']) ?>
                            -
                            <?= e($inscrito['email']) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            <?php endif; ?>

        </section>


        <div class="actions">

            <a
                class="btn secondary"
                href="edicao.php?id=<?= e($evento['id']) ?>"
            >
                Editar
            </a>

            <a
                class="btn danger"
                href="remocao.php?id=<?= e($evento['id']) ?>"
            >
                Remover
            </a>

            <a
                class="btn"
                href="index.php"
            >
                Voltar
            </a>

        </div>

    <?php endif; ?>

</main>

</body>
</html>
