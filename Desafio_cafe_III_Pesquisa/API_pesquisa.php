<?php
// ========================================
// API_pesquisa.php
// ========================================

// Resposta será sempre em JSON
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

// 1. Permitir apenas o método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido. Use POST."]);
    exit;
}

// 2. Lê o corpo da requisição JSON
$json_input = file_get_contents('php://input');
$data_input = json_decode($json_input, true);

// 3. Valida parâmetros obrigatórios
$user_question = $data_input['pergunta'] ?? null;
$client_api_key = $data_input['api_key'] ?? null;

if (empty($user_question) || empty($client_api_key)) {
    http_response_code(400);
    echo json_encode(["error" => "Parâmetros 'pergunta' e 'api_key' são obrigatórios."]);
    exit;
}

// 4. Define a chave e o endpoint da API Gemini
$GEMINI_API_KEY = $client_api_key; // ⚠️ Use sua chave fixa aqui se quiser proteger o servidor
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$GEMINI_API_KEY}";

// 5. Monta o payload (conteúdo da requisição)
$payload = json_encode([
    "contents" => [[
        "parts" => [[ "text" => $user_question ]]
    ]]
]);

// 6. Envia requisição para o Gemini via cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// 7. Trata possíveis erros de conexão
if ($response === false) {
    http_response_code(500);
    echo json_encode([
        "error" => "Erro na conexão com o servidor Gemini.",
        "detalhes" => $curl_error
    ]);
    exit;
}

// 8. Retorna resposta ao cliente
if ($http_code === 200) {
    echo $response; // Retorna o JSON original do Gemini
} else {
    http_response_code($http_code);
    echo json_encode([
        "error" => "Erro ao consultar a API Gemini.",
        "status" => $http_code,
        "resposta" => $response
    ]);
}

// Function to process the question using the API
function processarPergunta($api_key, $question) {
    // Add your API processing logic here
    // This is a placeholder implementation
    if (empty($api_key) || empty($question)) {
        throw new Exception("API key and question are required");
    }
    return "Your question has been processed.";
}
