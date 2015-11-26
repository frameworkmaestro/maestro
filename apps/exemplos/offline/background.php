<?php
include "offline.php";

// Endereco do servico a ser executado
$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . 'exemplos/data/dadosPessoaByNome';

// Dados de entrada - $_REQUEST[variavel] = valor
$_REQUEST['nome'] = 'T';

// Processamento (true indica que o retorno do processamento deve ser capturado e não exibido com echo)
$return = Manager::processRequest(true);

// Neste exemplo, o retorno é um objeto JSON
$result = json_decode($return);

// Acesso aos dados de output
$data = $result->data;

// Exibe os dados
var_dump($data);

?>