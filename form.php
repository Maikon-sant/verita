<?php
session_start();
$modoEscuro = isset($_SESSION['modoEscuro']) ? $_SESSION['modoEscuro'] : false;
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="<?php echo $modoEscuro ? 'dark' : 'light'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verita - Verificador de Notícias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/bootstrap-icons/bootstrap-icons.min.css">
</head>

<body class="d-flex flex-column">

    <header class="header-verita fixed-top shadow-sm">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <h1 class="h4 mb-0 d-flex align-items-center">
                <i class="bi bi-patch-check-fill me-2"></i> Verita
            </h1>
            <small class="text-white">Verificador de Notícias</small>
        </div>
    </header>
    <main class="flex-grow-1 pt-5 mt-0 d-flex align-items-center">
        <div class="container mt-3">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="glass-card card border-primary shadow-sm p-4 p-md-5 mb-2 mt-4">
                                    <div class="text-center mb-5">
                                        <h2 class="fw-bold mb-3">🔍 Verifique Agora</h2>
                                        <p class="lead text-muted">Cole o link da notícia abaixo para verificar a
                                            veracidade
                                        </p>
                                    </div>
                                    <div class="container">
                                        <form id="veritaForm" action="./api/api.php" method="post"
                                            class="needs-validation st-form">
                                            <div>
                                                <div class="input-group custom-textarea">
                                                    <span class="input-group-text custom-border bg-light">
                                                        <i class="bi bi-link-45deg"></i>
                                                    </span>
                                                    <textarea name="url" class="form-control custom-border"
                                                        style="background-color: rgba(248, 249, 250, 0.85);"
                                                        placeholder="Cole o link da notícia aqui..." rows="4"
                                                        required></textarea>
                                                </div>
                                            </div>

                                            <div class="d-grid mt-4">
                                                <button type="submit" class="btn btn-verita text-white py-3">
                                                    <i class="bi bi-search me-2"></i> Verificar Agora
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5 mt-4">
                        <div class="container text-center">
                            <div class="d-flex flex-column gap-3">
                                <div class="card border-primary shadow-sm">
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-check me-3 text-success text-icon"></i>
                                                <p class="card-text mb-0">Consulta de vericidade de notícia</p>
                                            </div>

                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-check me-3 text-success text-icon"></i>
                                                <p class="card-text mb-0">Análise profunda de veracidade ou não</p>
                                            </div>

                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-check me-3 text-success text-icon"></i>
                                                <p class="card-text mb-0">Analise feita por inteligência artificial</p>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check me-3 text-success text-icon"></i>
                                                <p class="card-text mb-0">Integração com API da OpenAI</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card pb-2 border-primary shadow-sm">
                                    <div class="card-body">
                                        <strong>
                                            <p class="card-title text-start">Tecnologias utilizadas</p>
                                        </strong>
                                        <img class="mt-3 me-4" src="./assets/img/bootstrap.png" alt="Originality"
                                            width="48">
                                        <img class="mt-3 me-4" src="./assets/img/openai.png" alt="Originality"
                                            width="100">
                                        <img class="mt-3" src="./assets/img/php.png" alt="Originality" width="80">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div id="respostaIA" class="mt-5"></div>
            </div>

    </main>

    <div class="switch-container">
        <button id="toggleModo" class="btn btn-secondary rounded-circle p-2 shadow">
            <span id="iconeModo">
                <?php echo $modoEscuro ? '☀️' : '🌙'; ?>
            </span>
        </button>
    </div>


    <script>
        document.getElementById("veritaForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            const respostaDiv = document.getElementById("respostaIA");
            respostaDiv.innerHTML = "<div class='text-center mt-4'><div class='spinner-border text-primary' role='status'><span class='visually-hidden'>Carregando...</span></div><p class='mt-2'>Analisando a notícia, por favor aguarde...</p></div>";

            fetch(form.action, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    respostaDiv.innerHTML = `
                <div class="card border-primary shadow-sm p-4 mt-4">
                    <h4 class="mb-3">🧠 Resultado da Análise</h4>
                    <div class="text-start">${data}</div>
                </div>
            `;
                    respostaDiv.scrollIntoView({ behavior: "smooth" });
                })
                .catch(error => {
                    respostaDiv.innerHTML = `<div class="alert alert-danger mt-4">Erro ao processar a análise. Tente novamente.</div>`;
                    console.error("Erro:", error);
                });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>

</html>