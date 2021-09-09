<?php
//Garantir que seja lido sem problemas
header("Content-Type: text/plain");

//Capturar CNPJ
$cnpj = $_REQUEST["cnpj"];
$cnpj2 = $_GET["cnpj"];

//Criando Comunicação cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.receitaws.com.br/v1/cnpj/".$cnpj);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$retorno = curl_exec($ch);
curl_close($ch);


$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, "http://www.receitaws.com.br/v1/cnpj/".$cnpj2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
$retorno2 = curl_exec($ch2);

// $retorno = json_decode($retorno); //Ajuda a ser lido mais rapidamente
// echo json_encode($retorno, JSON_PRETTY_PRINT);

// $retorno1 = json_decode($retorno);
echo json_encode($retorno, JSON_PRETTY_PRINT);
echo json_encode($retorno2, JSON_PRETTY_PRINT);

?>