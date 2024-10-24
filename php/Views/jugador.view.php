<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecció de jugador i color de fitxes</title>
</head>
<body>
<h1>Configuració del joc</h1>

<form action="/index.php" method="POST">
    <label for="name">Nom del jugador:</label>
    <input type="text" id="name" name="name" placeholder="Escriu el teu nom" required>
    <br><br>

    <label for="color">Tria el color de les fitxes:</label>
    <select id="color" name="color" required>
        <option value="">-- Selecciona un color --</option>
        <option value="red">Vermell</option>
        <option value="blue">Blau</option>
        <option value="yellow">Groc</option>
        <option value="green">Verd</option>
        <option value="black">Negre</option>
    </select>
    <br><br>

    <input type="submit" value="Començar el joc">
</form>

</body>
</html>

