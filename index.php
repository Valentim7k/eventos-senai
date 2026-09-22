<?php
require_once 'init.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos SENAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Eventos SENAI</h1>
        <?php require 'menu.php'; ?>
    </div>
</header>

<main class="container">
    <h2>Lista de eventos</h2>

    <?php if (empty($_SESSION['eventos'])): ?>
        <div class="empty">
            <p>Nenhum evento cadastrado.</p>
            <a class="btn" href="cadastro.php">Cadastrar primeiro evento</a>
        </div>
    <?php else: ?>
        <div class="event-grid">
            <?php foreach ($_SESSION['eventos'] as $evento): ?>
                <article class="card event-card">
                    <h2><?= e($evento['titulo']) ?></h2>
                    <p><strong>Área:</strong> <?= e($evento['area']) ?></p>
                    <p><strong>Data:</strong> <?= e($evento['data']) ?></p>
                    <p><strong>Horário:</strong> <?= e($evento['inicio']) ?> às <?= e($evento['fim']) ?></p>
                    <p><strong>Local:</strong> <?= e($evento['local']) ?></p>

                    <div class="actions">
                        <a class="btn" href="detalhes.php?id=<?= e($evento['id']) ?>">Detalhes</a>
                        <a class="btn secondary" href="edicao.php?id=<?= e($evento['id']) ?>">Editar</a>
                        <a class="btn danger" href="remocao.php?id=<?= e($evento['id']) ?>">Remover</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
