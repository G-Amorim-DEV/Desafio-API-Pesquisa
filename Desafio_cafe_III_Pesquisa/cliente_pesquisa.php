<?php
// ========================================
// app/processa_pesquisa.php
// ========================================

$target_url = "http://localhost/Desafio_cafe_III_Pesquisa/API_pesquisa.php";

// Variáveis padrão
$response_message = "";
$error_message = "";
$user_question = "";
$user_api_key = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_question = trim($_POST['pergunta'] ?? '');
    $user_api_key = trim($_POST['api_key'] ?? '');

    if (empty($user_question) || empty($user_api_key)) {
        $error_message = "⚠️ Por favor, preencha todos os campos antes de enviar.";
    } else {
        $client_payload = json_encode([
            "pergunta" => $user_question,
            "api_key" => $user_api_key
        ]);

        // Envia requisição
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $target_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $client_payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
        ]);

        $response_from_api = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Processa resultado
        if ($response_from_api === false) {
            $error_message = "Erro ao conectar-se com a API: $curl_error";
        } else {
            if ($http_code === 200) {
                $responseData = json_decode($response_from_api, true);
                $output_text = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "Nenhum texto retornado.";
                $response_message = nl2br(htmlspecialchars($output_text));
            } elseif ($http_code === 429) {
                $error_message = "⚠️ Limite de uso da API atingido. Tente novamente em alguns minutos.";
            } else {
                $error_message = "Erro na API (HTTP $http_code):<br><pre>" . htmlspecialchars($response_from_api) . "</pre>";
            }
        }
    }
}
