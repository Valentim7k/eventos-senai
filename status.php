<?php



require_once 'init.php';



$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);



if ($id === false || $id === null || !isset($_SESSION['eventos'][$id])) {

    header('Location: index.php');

    exit;

}



$evento = $_SESSION['eventos'][$id];



if (!isset($evento['status'])) {

    $_SESSION['eventos'][$id]['status'] = 'ativo';

    $evento['status'] = 'ativo';

}



if ($evento['status'] === 'ativo') {



    $_SESSION['eventos'][$id]['status'] = 'cancelado';



} elseif ($evento['status'] === 'cancelado') {



    $vagasOcupadas = count($evento['inscritos']);



    if ($vagasOcupadas < $evento['capacidade']) {

        $_SESSION['eventos'][$id]['status'] = 'ativo';

    }

}



header('Location: detalhes.php?id=' . $id);

exit;
