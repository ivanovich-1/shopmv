<?php
/**
 * Clase para las sesiones
 */
class Session
{
	private $login = false;
	private $user;
	
	// Inicializa la sesion del usuario.
	function __construct()
	{
		session_start();
		if (isset($_SESSION['user'])) {
			$this->user = $_SESSION['user'];
			$this->login = true;
		} else {
			unset($this->user);
			$this->login = false;
		}
	}
	
	// Creamos el metodo Login para que el usuario cuando se loguee la sesion se active
	public function login($user)
	{
		$this->user = $user;
		$_SESSION['user'] = $user;
		$this->login = true;
	}
	
	// Creamos el metodo logout para que el usuario se desconecte de la sesision.
	public function logout()
	{
		unset($_SESSION['user']);
		unset($this->user);
		session_destroy();
		$this->login = false;
	}
	
	// Metodo para llamar al Login. Recibis los datos del login.
	public function getLogin()
	{
		return $this->login;
	}
	
	// Metodo para llamar al usuario. Recibir los datos del usuario.
	public function getUser()
	{
		return $this->user;
	}
}
