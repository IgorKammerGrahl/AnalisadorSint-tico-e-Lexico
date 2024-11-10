<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisador Léxico e Sintático</title>
    <script>
    function analisar(codigo) {
        document.getElementById('wait').style.display = 'block'; // Mostrar mensagem de espera

        fetch('processar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'codigo=' + encodeURIComponent(codigo)
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('wait').style.display = 'none'; // Ocultar mensagem de espera
            const statusDiv = document.getElementById('status');
            
            if (data.status === 'sucesso') {
                statusDiv.innerHTML = "<h2>Tokens Encontrados:</h2><pre>" + data.tokens.join("\n") + "</pre>";

                if (data.sintaticoSucesso) {
                    statusDiv.innerHTML += "<h2>Análise Sintática: Sucesso</h2>";
                } else {
                    statusDiv.innerHTML += "<h2>Análise Sintática: Falha</h2><h3>Erros Sintáticos:</h3><pre>" + data.errosSintaticos.join("\n") + "</pre>";
                }
            } else {
                statusDiv.innerHTML = 'Erros encontrados:<br>' + data.mensagem; 
            }
        })
        .catch(error => {
            document.getElementById('wait').style.display = 'none'; 
            document.getElementById('status').innerHTML = 'Erro ao processar: ' + error;
        });
    }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        #corpo {
            width: 800px;
            height: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #titulo {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        #entrada,
        #saida {
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none; 
        }

        #status {
            background: #e9ecef;
            height: 400px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            max-height: 200px;
            overflow: auto; 
            white-space: pre-wrap; 
        }

        #controles {
            text-align: center;
        }

        #botao {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #botao:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div id="wait" style="display: none;">
    <b>Analisando seu Código...</b>
</div>

<div id="corpo">
    <div id="titulo">
        Analisador Léxico e Sintático
    </div>
    
    <div id="entrada">
        Código: 
        <textarea name="codigo" id="codigo"></textarea>
    </div>

    <div id="saida">
        Resultado: 
        <div id="status"></div>
    </div>
    
    <div id="controles">
        <input id="botao" type="button" name="analisar" value="Analisar Código" onClick="analisar(document.getElementById('codigo').value);">
    </div>
</div>

</body>
</html>
