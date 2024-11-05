<?php

namespace Joc4enRatlla\Controllers;

/**
 * Controlador de autenticación para manejar el inicio de sesión de los usuarios.
 */
class AuthController
{
    /**
     * Modelo de usuario para acceder y manipular datos de usuario.
     *
     * @var mixed
     */
    private $userModel;

    /**
     * Constructor de AuthController.
     *
     * @param mixed $userModel Modelo de usuario que interactúa con la base de datos de usuarios.
     */
    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    /**
     * Inicia sesión para un usuario dado. Si el usuario no existe, intenta crearlo.
     *
     * @param string $nom_usuari    Nombre de usuario que intenta iniciar sesión.
     * @param string $contrasenya   Contraseña proporcionada por el usuario.
     * @return bool                 Devuelve true si el inicio de sesión es exitoso, false en caso contrario.
     */
    public function login($nom_usuari, $contrasenya) {
        $user = $this->userModel->findUserByUsername($nom_usuari);

        if (!$user) {
            if ($this->userModel->createUser($nom_usuari, $contrasenya)) {
                $user = $this->userModel->findUserByUsername($nom_usuari);
            }
        }

        if ($user && password_verify($contrasenya, $user['contrasenya'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom_usuari'] = $user['nom_usuari'];
            return true;
        }
        
        return false;
    }
}
