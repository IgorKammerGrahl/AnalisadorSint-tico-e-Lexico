<?php
include("analiseLexica.php");
include("analisadorSintatico.php");

$sourceCode = $_POST['codigo'] ?? ''; // Ajustado para corresponder ao nome da variável enviada pelo JavaScript
$analisador = new AnalisadorLexico();

try {
    // 1. Realiza a análise léxica
    $resultado = $analisador->lexer($sourceCode);
    $tokens = $resultado['tokens'];
    $errosLexicos = $resultado['erros'];

    // 2. Prepara a resposta
    $response = [];
    $response['tokens'] = [];
    $response['erros'] = [];
    $response['sintaticoSucesso'] = false;

    // Exibe os tokens encontrados
    foreach ($tokens as $token) {
        array_push($response['tokens'], $token[2] ? $token[2] : $token[0]);
    }

    // 3. Exibe erros léxicos, se houver
    if (!empty($errosLexicos)) {
        foreach ($errosLexicos as $erro) {
            array_push($response['erros'], $erro);
        }
    } else {
        $analisadorSintaticoDR = new AnalisadorSintaticoDR($analisador);
        if ($analisadorSintaticoDR->Programa()) {
            $response['sintaticoSucesso'] = true;
        } else {
            $errosSintaticos = $analisadorSintaticoDR->getErros();
            if (!empty($errosSintaticos)) {
                foreach ($errosSintaticos as $erro) {
                    array_push($response['erros'], $erro);
                }
            }
        }
    }

    // Retorna a resposta como JSON
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
?>
