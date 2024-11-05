<?php if (isset($error)): ?>
    <div class="error">
        <?= $error; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php">
    <label for="nom_usuari">Nom d'usuari:</label>
    <input type="text" id="nom_usuari" name="nom_usuari" required>

    <label for="contrasenya">Contrasenya:</label>
    <input type="password" id="contrasenya" name="contrasenya" required>

    <button type="submit">Iniciar sessió</button>
</form>