<?php

require __DIR__ . '/vendor/autoload.php';

define('TITLE', 'Editar vaga');

use \App\Entity\Vaga;

//VALIDAÇÃO DO ID: ID DEVE EXISTIR OU SER NUMÉRICO

if(!isset($_GET['id']) or !is_numeric($_GET['id'])){

    //RETORNA PARA HOME APÓS ERRO EXIBINDO UMA MENSAGEM DE ERRO!
    header('location: index.php?status=error');
    exit;
}

//CONSULTA A VAGA
$objetoVaga = Vaga::getUmaVaga($_GET['id']);

//VALIDAÇÃO DA VAGA

if(!$objetoVaga instanceof Vaga){

    //RETORNA PARA HOME APÓS ERRO EXIBINDO UMA MENSAGEM DE ERRO!
    header('location: index.php?status=error');
    exit;

}

//VALIDAÇÃO DO POST
if (isset($_POST['titulo'], $_POST['descricao'], $_POST['ativo'])) {

    $objetoVaga->titulo     = $_POST['titulo'];
    $objetoVaga->descricao  = $_POST['descricao'];
    $objetoVaga->ativo            = $_POST['ativo'];
    $objetoVaga->atualizaUmaVaga();

    //RETORNA PARA HOME APÓS CADASTRAR EXIBINDO UMA MENSAGEM DE SUCESSO!
    header('location: index.php?status=success');
    exit; //IMPEDE DE CARREGAR A PÁGINA NOVAMENTE

}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/formulario.php';
include __DIR__ . '/includes/footer.php';
