<html>
<head>
    <link rel="stylesheet" href="4ratlla.css?v=<?php echo time(); ?>">
    <title>4 en ratlla</title>
    <style>
        .player1 {
            background-color: <?= $players[1]->getColor() ?> ; 
        }
        .player2 {
            background-color:  <?= $players[2]->getColor() ?>; 
        }

    </style>
</head>
<body>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/../Views/partials/board.view.php'  ?>
<div>

        <h1>Guanyador: Jugador <?= $players[$winner]->getName() ?></h1>

</div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

     <input type="submit" name="reset" value="Reiniciar joc">
</form>

</body>
</html>
