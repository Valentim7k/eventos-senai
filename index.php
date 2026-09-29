<?php
require_once 'init.php';
function e($valor) {
   return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

}
$busca = trim($_GET['busca'] ?? '');
$area = trim($_GET['area'] ?? '');
$data = trim($_GET['data'] ?? '');
$eventosFiltrados = [];
foreach ($_SESSION['eventos'] as $evento) {

    if ($busca !== '' && stripos($evento['titulo'], $busca) === false) {
        continue;
    }
    if ($area !== '' && $evento['area'] !== $area) {
        continue;
    }
    if ($data !== '' && $evento['data'] !== $data) {
        continue;
    }
    $eventosFiltrados[] = $evento;
}
$areas = [];
foreach ($_SESSION['eventos'] as $evento) {
    if (!in_array($evento['area'], $areas)) {
        $areas[] = $evento['area'];
    }
}
sort($areas);
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
    <form method="GET" class="card">
        <h3>Buscar e filtrar eventos</h3>
        <label for="busca">Título:</label>
        <input
            type="text"
            id="busca"
            name="busca"
            placeholder="Digite o título do evento"
            value="<?= e($busca) ?>"
        >
        <label for="area">Área:</label>
        <select id="area" name="area">
            <option value="">Todas as áreas</option>
            <?php foreach ($areas as $areaDisponivel): ?>
                <option
                    value="<?= e($areaDisponivel) ?>"
                    <?= $area === $areaDisponivel ? 'selected' : '' ?>
                >
                    <?= e($areaDisponivel) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="data">Data:</label>
        <input
            type="date"
            id="data"
            name="data"
            value="<?= e($data) ?>"
        >
        <button type="submit" class="btn">Buscar</button>
        <a href="index.php" class="btn secondary">Limpar filtros</a>
    </form>
    <?php if (empty($_SESSION['eventos'])): ?>
        <div class="empty">
            <p>Nenhum evento cadastrado.</p>
            <a class="btn" href="cadastro.php">Cadastrar primeiro evento</a>
        </div>
    <?php elseif (empty($eventosFiltrados)): ?>
        <div class="empty">
            <p>Nenhum evento encontrado com os filtros informados.</p>
        </div>
    <?php else: ?>
        <div class="event-grid">
            <?php foreach ($eventosFiltrados as $evento): ?>
                <article class="card event-card">
                    <h2><?= e($evento['titulo']) ?></h2>
                    <p>
                        <strong>Área:</strong>
                        <?= e($evento['area']) ?>
                    </p>
                    <p>
                        <strong>Data:</strong>
                        <?= e($evento['data']) ?>
                    </p>
                    <p>
                        <strong>Horário:</strong>
                        <?= e($evento['inicio']) ?>
                        às
                        <?= e($evento['fim']) ?>
                    </p>
                    <p>
                        <strong>Local:</strong>
                        <?= e($evento['local']) ?>
                    </p>
                    <div class="actions">
                        <a
                            class="btn"
                            href="detalhes.php?id=<?= e($evento['id']) ?>"
                        >
                            Detalhes
                        </a>
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
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
