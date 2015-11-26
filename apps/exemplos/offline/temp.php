<?php
include "offline.php";

// Path do servico a ser executado
$_SERVER['REQUEST_PATH'] = 'exemplos/controles/actions/main';

// Dados de entrada - $_REQUEST[variavel] = valor
$_REQUEST['nome'] = 'T';

// Processamento (true indica que o retorno do processamento deve ser capturado e n�o exibido com echo)
$return = \Maestro\Manager::processRequest(true);
var_dump($return);
// Neste exemplo, o retorno � um objeto JSON
///$result = json_decode($return);

// Acesso aos dados de output
//$data = $result->data;

// Exibe os dados
var_dump($data);

?>