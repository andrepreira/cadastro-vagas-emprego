<?php

/**
 * Debugger
 echo '<pre>';
        print_r();
    echo '<pre>';
    exit;
 */

require __DIR__ . '/vendor/autoload.php';

define('TITLE', 'Cadastrar vaga');

use \App\Entity\Vaga;

$objetoVaga = new Vaga;

//VALIDAÇÃO DO POST
if (isset($_POST['titulo'], $_POST['descricao'], $_POST['ativo'])) {
    $objetoVaga->titulo     = $_POST['titulo'];
    $objetoVaga->descricao  = $_POST['descricao'];
    $objetoVaga->ativo      = $_POST['ativo'];
    $objetoVaga->cadastraVaga();

    //RETORNA PARA HOME APÓS CADASTRAR EXIBINDO UMA MENSAGEM DE SUCESSO!
    header('location: index.php?status=success');
    exit; //IMPEDE DE CARREGAR A PÁGINA NOVAMENTE

}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/formulario.php';
include __DIR__ . '/includes/footer.php';
