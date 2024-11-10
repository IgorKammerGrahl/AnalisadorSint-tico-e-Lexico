<?php
require_once 'AnaliseLexica.php';  // Caminho do seu arquivo de AnalisadorLexico
require_once 'AnalisadorSintatico.php';  // Caminho do seu arquivo de AnalisadorSintaticoDR

// Código-fonte de exemplo a ser analisado
$codigoFonte = "PROGRAM exemplo() { int x; }";

// Instancia o analisador léxico e gera os tokens
$analisadorLexico = new AnalisadorLexico();
$resultadoLexico = $analisadorLexico->lexer($codigoFonte); // Chama o método lexer
//var_dump( $analisadorLexico->tokens);

// Exibe os tokens gerados para verificar a análise léxica
print_r($resultadoLexico['tokens']);  // Verifique a estrutura

// Verifica se há erros na análise léxica antes de seguir para a análise sintática
if (!empty($resultadoLexico['erros'])) {
    echo "Erros na análise léxica: ";
    print_r($resultadoLexico['erros']);
    exit; // Encerra se houver erros léxicos
}

// Instancia o analisador sintático com os tokens extraídos
$analisadorSintatico = new AnalisadorSintaticoDR($analisadorLexico);

// Executa a análise sintática
if ($analisadorSintatico->Programa()) {
    echo "Análise sintática concluída com sucesso!";
} else {
    echo "Erros de análise sintática: ";
    print_r($analisadorSintatico->getErros());
}
?>
