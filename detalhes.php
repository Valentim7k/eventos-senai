<?php
require_once 'init.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || !isset($_SESSION['eventos'][$id])) {
    $erro = 'Evento não encontrado.';
} else {
    $evento = $_SESSION['eventos'][$id];

    if (!isset($evento['inscritos'])) {
        $evento['inscritos'] = [];
    }

    $erros = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nome === '') {
            $erros[] = 'O nome é obrigatório.';
        }

        if ($email === '') {
            $erros[] = 'O e-mail é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail válido.';
        }

        foreach ($evento['inscritos'] as $inscrito) {
            if (strcasecmp($inscrito['email'], $email) === 0) {
                $erros[] = 'Este e-mail já está inscrito neste evento.';
                break;
            }
        }

        if (empty($erros)) {
            $_SESSION['eventos'][$id]['inscritos'][] = [
                'nome' => $nome,
                'email' => $email
            ];

            header('Location: detalhes.php?id=' . $id);
            exit;
        }
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
        <div class="error"><?= e($erro) ?></div>
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
            </dl>

            <h3>Inscrição</h3>

<?php if (!empty($erros)): ?>
    <div class="error">
        <?php foreach ($erros as $erro): ?>
            <p><?= e($erro) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" class="card">

    <label for="nome">Nome:</label>
    <input
        type="text"
        id="nome"
        name="nome"
        required
        value="<?= e($_POST['nome'] ?? '') ?>"
    >

    <label for="email">E-mail:</label>
    <input
        type="email"
        id="email"
        name="email"
        required
        value="<?= e($_POST['email'] ?? '') ?>"
    >

    <button type="submit" class="btn">
        Inscrever-se
    </button>

</form>

            <h3>Inscritos</h3>

<?php if (empty($evento['inscritos'])): ?>

    <p>Nenhum participante inscrito.</p>

<?php else: ?>

    <ul>
        <?php foreach ($evento['inscritos'] as $inscrito): ?>
            <li>
                <?= e($inscrito['nome']) ?>
                — <?= e($inscrito['email']) ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

            <div class="actions">
                <a class="btn secondary" href="edicao.php?id=<?= e($evento['id']) ?>">Editar</a>
                <a class="btn danger" href="remocao.php?id=<?= e($evento['id']) ?>">Remover</a>
                <a class="btn" href="index.php">Voltar</a>
            </div>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
