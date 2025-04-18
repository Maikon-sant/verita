<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Verità - Verificador de Notícias</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h1 class="text-center mb-4">Verità 🕵️‍♂️</h1>

    <form action="resultado.php" method="post">
      <div class="mb-3">
        <label for="noticia" class="form-label">Cole aqui a notícia que deseja verificar:</label>
        <textarea class="form-control" id="noticia" name="noticia" rows="8" required></textarea>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">Verificar Notícia</button>
      </div>
    </form>
  </div>
</body>
</html>
