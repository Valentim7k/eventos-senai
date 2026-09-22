<?php
require_once 'init.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPost = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($idPost === false || $idPost === null || !isset($_SESSION['eventos'][$idPost])) {
        $erro = 'Evento não encontrado.';
    } else {
        unset($_SESSION['eventos'][$idPost]);
        header('Location: index.php');
        exit;
    }
}

if (!isset($erro)) {
    if ($id === false || $id === null || !isset($_SESSION['eventos'][$id])) {
        $erro = 'Evento não encontrado.';
        $evento = null;
    } else {
        $evento = $_SESSION['eventos'][$id];
    }
} else {
    $evento = null;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover evento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Remover evento</h1>
        <?php require 'menu.php'; ?>
    </div>
</header>

<main class="container">
    <?php if ($evento === null): ?>
        <div class="error"><?= e($erro) ?></div>
        <a class="btn" href="index.php">Voltar</a>
    <?php else: ?>
        <section class="card">
            <h2>Confirmar remoção</h2>
            <p>Você realmente deseja remover este evento?</p>

            <p><strong>Título:</strong> <?= e($evento['titulo']) ?></p>
            <p><strong>Data:</strong> <?= e($evento['data']) ?></p>
            <p><strong>Local:</strong> <?= e($evento['local']) ?></p>

            <form method="POST" class="actions">
                <input type="hidden" name="id" value="<?= e($evento['id']) ?>">
                <button class="btn danger" type="submit">Sim, remover</button>
                <a class="btn secondary" href="index.php">Cancelar</a>
            </form>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
