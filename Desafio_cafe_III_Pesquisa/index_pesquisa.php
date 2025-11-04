<?php
// ===============================
// index_pesquisa.php
// ===============================

// Inicializa variáveis
$api_key = "";
$pergunta = "";
$response_message = "";
$error_message = "";

// Processa formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $api_key = trim($_POST['api_key'] ?? "");
    $pergunta = trim($_POST['pergunta'] ?? "");

    if (!empty($api_key) && !empty($pergunta)) {
        require_once "cliente_pesquisa.php";
    } else {
        $error_message = "⚠️ Por favor, preencha todos os campos antes de enviar.";
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
        <h1>💬 Pesquisa com API Gemini</h1>

        <form method="post" action="">
            <label for="api_key"><span class="emoji-label">🔑</span>Chave da API:</label>
            <input type="password" id="api_key" name="api_key" value="<?= htmlspecialchars($api_key) ?>" placeholder="Insira sua chave da API" required>

            <label for="pergunta"><span class="emoji-label">❓</span>Pergunta:</label>
            <textarea id="pergunta" name="pergunta" rows="4" placeholder="Digite sua pergunta..." required><?= htmlspecialchars($pergunta) ?></textarea>

            <button type="submit">Enviar Pergunta</button>
        </form>

        <?php if (!empty($response_message)): ?>
            <div class="resposta">
                <h2>🧠 Resposta:</h2>
                <p><?= $response_message ?></p>
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
