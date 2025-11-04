<?php
// ========================================
// cliente_pesquisa.php — versão aprimorada
// ========================================

// URL da API local (corrigida)
$target_url = "http://localhost/Desafio_cafe_III_Pesquisa/API_pesquisa.php";

// Variáveis padrão
$response_message = "";
$error_message = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_key = trim($_POST['api_key'] ?? '');
    $pergunta = trim($_POST['pergunta'] ?? '');

    if (empty($api_key) || empty($pergunta)) {
        $error_message = "⚠️ Por favor, preencha todos os campos antes de enviar.";
    } else {
        $client_payload = json_encode([
            "pergunta" => $pergunta,
            "api_key" => $api_key
        ]);

        if (!function_exists('curl_init')) {
            $error_message = "A extensão cURL não está habilitada no servidor.";
        } else {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $target_url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $client_payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
                CURLOPT_TIMEOUT => 30
            ]);

            $response_from_api = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);

            if ($response_from_api === false) {
                $error_message = "Erro ao conectar-se com a API: $curl_error";
            } else {
                $responseData = json_decode($response_from_api, true);

                if (!$responseData) {
                    $error_message = "Resposta inválida da API. Conteúdo recebido: " . htmlspecialchars($response_from_api);
                } elseif (isset($responseData['error'])) {
                    $error_message = "⚠️ Erro da API: " . htmlspecialchars($responseData['error']);
                } elseif ($http_code !== 200) {
                    $error_message = "Erro HTTP $http_code: " . htmlspecialchars($response_from_api);
                } else {
                    $output_text = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "Nenhum texto retornado.";
                    $response_message = nl2br(htmlspecialchars($output_text));
                }
            }
        }
    }
}
?>
