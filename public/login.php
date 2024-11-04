<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 300px;">
      <h3 class="text-center mb-4">Connexion</h3>
      <form action="blog.html" method="GET">
        
      <div class="mb-3">
          <label for="username" class="form-label">Nom d'utilisateur</label>
          <input type="text" class="form-control" id="username" name="username">
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password">
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    
      </form>
    </div>
  </div>
</body>
</html>
