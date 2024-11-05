<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
$dbConfig =  require $_SERVER['DOCUMENT_ROOT'] . '/../config/connection.php';

use Joc4enRatlla\Controllers\GameController;
use Joc4enRatlla\Controllers\AuthController;
use Joc4enRatlla\Models\User;
use Joc4enRatlla\Services\Connect;

$connection = new Connect($dbConfig);

if (!isset($_SESSION['user_id'])) {
    $user = new User($connection->getConnection());
    $authController = new AuthController($user);
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        $nom_usuari = $_POST['nom_usuari'];
        $contrasenya = $_POST['contrasenya'];

        if ($authController->login($nom_usuari, $contrasenya)) {
            header("location:/index.php");
            exit();
        }
        $error = "Nom d'usuari o contrasenya incorrectes.";

    }
    loadView('login',compact('error'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $gameController = new GameController($_POST, $connection->getConnection());
} else {
    loadView('jugador');
}