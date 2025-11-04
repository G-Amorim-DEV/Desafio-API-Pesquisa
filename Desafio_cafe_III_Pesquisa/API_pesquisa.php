<?php
// ========================================
// API_pesquisa.php — versão aprimorada
// ========================================

// Função que processa a pergunta usando a API do Gemini
function processarPergunta($api_key, $pergunta) {
    if (empty($api_key) || empty($pergunta)) {
        throw new Exception("API key e pergunta são obrigatórias.");
    }

    // Verifica se a extensão cURL está disponível
    if (!function_exists('curl_init')) {
        throw new Exception("A extensão cURL não está habilitada no servidor.");
    }

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . urlencode($api_key);

    $payload = json_encode([
        "contents" => [[
            "parts" => [["text" => $pergunta]]
        ]]
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        throw new Exception("Erro na conexão com o Gemini: $curl_error");
    }

    if ($http_code !== 200) {
        throw new Exception("Erro HTTP $http_code — resposta da API: " . $response);
    }

    $data = json_decode($response, true);
    if ($data === null) {
        throw new Exception("Erro ao decodificar JSON da resposta do Gemini.");
    }

    return $data;
}

// ========================================
// Endpoint da API — acesso externo via POST
// ========================================
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido. Use POST."]);
        exit;
    }

    $json_input = file_get_contents('php://input');
    $data_input = json_decode($json_input, true);

    $pergunta = $data_input['pergunta'] ?? null;
    $api_key = $data_input['api_key'] ?? null;

    if (empty($pergunta) || empty($api_key)) {
        http_response_code(400);
        echo json_encode(["error" => "Parâmetros 'pergunta' e 'api_key' são obrigatórios."]);
        exit;
    }

    try {
        $response = processarPergunta($api_key, $pergunta);
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
