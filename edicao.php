<?php
require_once 'init.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || !isset($_SESSION['eventos'][$id])) {
    $erro = 'Evento não encontrado.';
    $dados = null;
} else {
    $dados = $_SESSION['eventos'][$id];
    $erros = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idPost = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($idPost === false || $idPost === null || !isset($_SESSION['eventos'][$idPost]) || $idPost !== $id) {
            $erros[] = 'ID do evento inválido.';
        } else {
            $campos = ['titulo', 'descricao', 'area', 'data', 'inicio', 'fim', 'local', 'responsavel'];

            foreach ($campos as $campo) {
                $dados[$campo] = trim($_POST[$campo] ?? '');

                if ($dados[$campo] === '') {
                    $erros[] = "O campo " . $campo . " é obrigatório.";
                }
            }

            if ($dados['inicio'] !== '' && $dados['fim'] !== '' && $dados['fim'] <= $dados['inicio']) {
                $erros[] = 'O horário final deve ser maior que o horário inicial.';
            }

            if (empty($erros)) {
                $dados['id'] = $id;
                $_SESSION['eventos'][$id] = $dados;

                header('Location: index.php');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar evento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Editar evento</h1>
        <?php require 'menu.php'; ?>
    </div>
</header>

<main class="container">
    <?php if ($dados === null): ?>
        <div class="error"><?= e($erro) ?></div>
        <a class="btn" href="index.php">Voltar</a>
    <?php else: ?>
        <section class="form-card">
            <?php if (!empty($erros)): ?>
                <div class="error">
                    <strong>Corrija os erros:</strong>
                    <ul>
                        <?php foreach ($erros as $erro): ?>
                            <li><?= e($erro) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?= e($id) ?>">

                <label for="titulo">Título</label>
                <input id="titulo" name="titulo" type="text" value="<?= e($dados['titulo']) ?>" required>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" required><?= e($dados['descricao']) ?></textarea>

                <label for="area">Área</label>
                <input id="area" name="area" type="text" value="<?= e($dados['area']) ?>" required>

                <label for="data">Data</label>
                <input id="data" name="data" type="date" value="<?= e($dados['data']) ?>" required>

                <label for="inicio">Horário de início</label>
                <input id="inicio" name="inicio" type="time" value="<?= e($dados['inicio']) ?>" required>

                <label for="fim">Horário de fim</label>
                <input id="fim" name="fim" type="time" value="<?= e($dados['fim']) ?>" required>

                <label for="local">Local</label>
                <input id="local" name="local" type="text" value="<?= e($dados['local']) ?>" required>

                <label for="responsavel">Responsável</label>
                <input id="responsavel" name="responsavel" type="text" value="<?= e($dados['responsavel']) ?>" required>

                <div class="form-actions">
                    <button class="btn" type="submit">Salvar alterações</button>
                    <a class="btn secondary" href="index.php">Cancelar</a>
                </div>
            </form>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
