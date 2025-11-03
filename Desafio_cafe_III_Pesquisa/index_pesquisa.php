<?php
// ===============================
// index_pesquisa.php
// ===============================

// Evita warnings de variáveis indefinidas
$user_api_key = "";
$user_question = "";
$response_message = "";
$error_message = "";

// Quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura dados do formulário de forma segura
    $user_api_key = $_POST['user_api_key'] ?? "";
    $user_question = $_POST['user_question'] ?? "";

    // Verifica se os campos estão preenchidos
    if (!empty($user_api_key) && !empty($user_question)) {
        // Inclui a API que faz a requisição ao modelo
        require_once "API_pesquisa.php";

        // Chama a função que processa a pergunta
        try {
            $response_message = processarPergunta($user_api_key, $user_question);
        } catch (Exception $e) {
            $error_message = "Erro ao processar a requisição: " . $e->getMessage();
        }
    } else {
        $error_message = "Por favor, preencha todos os campos antes de enviar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pesquisa - Desafio Café III</title>
    <link rel="stylesheet" href="css/Style_pesquisa.css">
</head>
<body>
    <div class="container">
        <h1>💬 Pesquisa com API</h1>

        <form method="post" action="">
            <label for="user_api_key"><span class="emoji-label">🔑</span>Chave da API:</label>
            <input type="text" id="user_api_key" name="user_api_key" value="<?= htmlspecialchars($user_api_key) ?>" placeholder="Insira sua chave da API" required>

            <label for="user_question"><span class="emoji-label">❓</span>Pergunta:</label>
            <textarea id="user_question" name="user_question" rows="4" placeholder="Digite sua pergunta..." required><?= htmlspecialchars($user_question) ?></textarea>

            <button type="submit">Enviar Pergunta</button>
        </form>

        <?php if (!empty($response_message)): ?>
            <div class="resposta">
                <h2>🧠 Resposta:</h2>
                <p><?= nl2br(htmlspecialchars($response_message)) ?></p>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="resposta" style="border-left-color: #ff1744; background: #fff3f3;">
                <h2>⚠️ Erro:</h2>
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
