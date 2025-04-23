<?php
function extrairTextoDaURL($url)
{
    $html = @file_get_contents($url);
    if (!$html) {
        return false;
    }

    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($html);
    $xpath = new DOMXPath($doc);

    $tagsDeTexto = ['p', 'h1', 'h2', 'h3', 'article', 'span', 'div'];
    $textoExtraido = '';

    foreach ($tagsDeTexto as $tag) {
        $nodos = $xpath->query("//{$tag}");
        foreach ($nodos as $nodo) {
            $texto = trim($nodo->textContent);
            if (strlen($texto) > 100) {
                $textoExtraido .= $texto . "\n\n";
            }
        }
    }

    return substr($textoExtraido, 0, 5000);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['url'])) {
        $url = $_POST['url'];
        $conteudo = extrairTextoDaURL($url);

        if (!$conteudo) {
            echo "Erro ao acessar ou extrair conteúdo da URL.";
            exit;
        }

        $prompt = "Leia a seguinte notícia extraída do site:\n\n$conteudo\n\nVerifique se ela é verdadeira ou falsa. Leia os argumentos, fatos e fontes e se tem autor ou não desconsidere sua data de aprendizado. Ao final, escreva: VERDADEIRA ou FALSA.";

        //$api_key = ""; //
        $api_url = "https://api.openai.com/v1/chat/completions";

        $data = [
            "model" => "gpt-4.1-mini",
            "messages" => [
                ["role" => "system", "content" => "Você é um verificador de fatos especialista em notícias."],
                ["role" => "user", "content" => $prompt]
            ],
            "temperature" => 0.7,
            "max_tokens" => 10000
        ];

        $headers = [
            "Authorization: Bearer $api_key",
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['choices'][0]['message']['content'])) {
            $resposta_completa = trim($responseData['choices'][0]['message']['content']);

            echo "<strong>Resposta completa da IA:</strong><br>" . nl2br($resposta_completa) . "<br><br>";

            if (preg_match('/\b(VERDADEIRA|FALSA)\b/i', $resposta_completa, $resposta_principal)) {
                $resultado = strtoupper($resposta_principal[1]);
                echo "<strong>Classificação Final:</strong> <span style='color: " . ($resultado === 'VERDADEIRA' ? 'green' : 'red') . "'>$resultado</span><br><br>";
            } else {
                echo "<strong>Não foi possível determinar se é verdadeira ou falsa.</strong><br><br>";
            }
        } else {
            echo "Erro ao obter resposta da API OpenAI.<br><br>";
        }
    } else {
        echo "Por favor, preencha todos os campos corretamente.<br><br>";
    }
}
?>