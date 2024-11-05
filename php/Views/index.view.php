<html>
<head>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
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
<?php include_once $_SERVER['DOCUMENT_ROOT'].'/../Views/partials/error.view.php'  ?>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <?php include_once $_SERVER['DOCUMENT_ROOT'].'/../Views/partials/board.view.php'  ?>
     <input type="submit" name="reset" value="Reiniciar joc">
     <input type="submit" name="exit" value="Sortir">
     <input type="submit" name="save" value="Guardar Partida">
     <input type="submit" name="restore" value="Restaurar  Partida">
</form>
 <?php include_once $_SERVER['DOCUMENT_ROOT'].'/../Views/partials/panel.view.php'  ?>

</body>
</html