# 🧠 API de Pesquisa com Consumo via JSON (PHP + Gemini)

## 📘 Descrição Geral

Este projeto implementa uma **API de pesquisa em PHP** que consome e retorna dados em **formato JSON**.  
Ela serve como **ponte entre o usuário e um modelo de IA** (como o Gemini, da Google), permitindo que perguntas sejam enviadas por um formulário web e que as respostas sejam exibidas de forma organizada e estilizada.

O sistema foi projetado com uma arquitetura simples e modular, composta por três camadas principais:
- **Interface (index_pesquisa.php + Style_pesquisa.css)**
- **Cliente intermediário (cliente_pesquisa.php)**
- **API central (API_pesquisa.php)**

---

## 🗂️ Estrutura de Arquivos

| Arquivo | Função |
|----------|--------|
| `index_pesquisa.php` | Página principal — interface de interação com o usuário. Envia as perguntas e exibe as respostas. |
| `cliente_pesquisa.php` | Cliente intermediário que recebe a pergunta do usuário e faz a requisição JSON para a API. |
| `API_pesquisa.php` | Núcleo da aplicação — processa o JSON recebido, chama o modelo Gemini e retorna a resposta. |
| `Style_pesquisa.css` | Arquivo de estilo responsável pelo design da interface, formulários e área de resposta. |

---

## ⚙️ Funcionamento do Sistema

### 🔁 Fluxo Completo
1. O usuário acessa o `index_pesquisa.php` e digita sua pergunta.
2. O formulário envia os dados para o `cliente_pesquisa.php` via **método POST**.
3. O cliente cria um **JSON** contendo a pergunta do usuário e envia para a `API_pesquisa.php`.
4. A `API_pesquisa.php` consome a API externa (ex: Gemini), processa a resposta e a retorna no formato JSON.
5. O `cliente_pesquisa.php` decodifica a resposta e a exibe no `index_pesquisa.php`.

---

## 💬 Exemplo de Requisição

### 🔹 Envio para a API (`POST`)
```json
{
  "question": "Quem foi Albert Einstein?"
}
```

### 🔹 Retorno da API
```json
{
  "response": "Albert Einstein foi um físico teórico alemão, criador da Teoria da Relatividade..."
}
```

---

## 🧠 Explicação dos Arquivos

### 🧩 `API_pesquisa.php`
- É o **núcleo lógico** da aplicação.
- Recebe requisições em **formato JSON**.
- Interpreta o conteúdo enviado e faz uma chamada ao **modelo Gemini** (ou outra API configurada).
- Retorna a resposta formatada como JSON.

**Principais responsabilidades:**
- Validar a entrada do usuário.
- Gerar a requisição externa com `cURL` ou `file_get_contents()`.
- Retornar o resultado com cabeçalho HTTP e `Content-Type: application/json`.

---

### 🌐 `cliente_pesquisa.php`
- Atua como **ponte entre a interface e a API**.
- Recebe os dados via formulário (`POST`).
- Monta o JSON e envia para a `API_pesquisa.php`.
- Recebe e exibe a resposta de forma segura.

**Principais funções:**
- Tratar erros de requisição (como 404 ou 500).
- Exibir mensagens de erro legíveis ao usuário.
- Garantir que o formato enviado seja compatível com a API.

---

### 🖥️ `index_pesquisa.php`
- É a **interface gráfica principal** da aplicação.
- Exibe um formulário simples e intuitivo.
- Mostra a resposta retornada pela API em uma caixa estilizada.

**Componentes principais:**
- `<form>` com campo de texto e botão de envio.
- `<div class="resposta">` que exibe a resposta processada.
- Integração PHP para mostrar o resultado após o envio.

---

### 🎨 `Style_pesquisa.css`
Define toda a aparência do sistema e foi criado com foco em **simplicidade, responsividade e legibilidade**.

**Principais características:**
- Layout centralizado e responsivo.
- Paleta azul moderna (`#2962ff`, `#1a237e`).
- Sombras suaves e cantos arredondados.
- Efeitos de foco nos campos de entrada.
- **Modo escuro automático** via `prefers-color-scheme: dark`.

**Exemplo visual de classes:**
```css
.container {
    max-width: 700px;
    margin: 3rem auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}
```

---

## 🧾 Códigos de Resposta HTTP

| Código | Significado |
|--------|--------------|
| `200 OK` | A requisição foi processada com sucesso. |
| `400 Bad Request` | O JSON enviado está incorreto ou incompleto. |
| `404 Not Found` | O endpoint da API não foi encontrado. |
| `500 Internal Server Error` | Falha na execução (ex: erro na comunicação com a API externa). |

---

## 💻 Tecnologias Utilizadas
- **PHP 8+**
- **HTML5 / CSS3**
- **JSON para troca de dados**
- **cURL** (para requisições HTTP)
- **Modelo Gemini / Google AI** (API externa)

---

## 🧰 Requisitos do Ambiente
- Servidor local: **XAMPP**, **WAMP** ou **Apache/Nginx**
- **PHP com suporte a cURL**
- Conexão com a internet
- Navegador moderno (Chrome, Edge, Firefox)

---

## 🚀 Como Executar Localmente

1. Coloque todos os arquivos na pasta:
   ```
   C:\xampp\htdocs\Desafio_cafe_III_Pesquisa\
   ```

2. Inicie o servidor Apache no XAMPP.

3. Acesse em seu navegador:
   ```
   http://localhost/Desafio_cafe_III_Pesquisa/index_pesquisa.php
   ```

4. Digite uma pergunta e clique em **Enviar**.

5. A resposta da API será exibida na tela.

---

## 🧩 Personalização e Ajustes

| Elemento | Como alterar |
|-----------|---------------|
| **Chave da API** | Dentro do arquivo `API_pesquisa.php`, substitua pela sua chave da Google AI (Gemini). |
| **Estilo da página** | Edite o arquivo `Style_pesquisa.css`. |
| **Formatação da resposta** | Ajuste a div `.resposta` no `index_pesquisa.php`. |
| **Mensagens de erro** | Customize o `cliente_pesquisa.php` para exibir mensagens mais detalhadas. |

---

## 🧠 Observação Importante

Esta API **não armazena dados em JSON** localmente — ela apenas **consome e retorna** JSON entre o cliente e a API externa.  
Todo o processamento ocorre **em tempo real**, com comunicação direta via HTTP.

---

## 💬 Agradecimento

> Obrigado(a) por sua participação e interesse!  
> O projeto demonstra como estruturar uma comunicação entre cliente e servidor em PHP consumindo dados via JSON.  
> Se houver sugestões ou dúvidas sobre a implementação, este é o momento para discutir e aprimorar juntos!
