<?php
/**
 * Controlador Shop para la tienda
 */
class ShopController extends Controller
{
	private $model;
	
	function __construct()
	{
		$this->model = $this->model('Shop');
	}
	// Se guardan todos los parametros que quemos mostrar en la vista. Creamos tambien un nuevo objeto sesion para dejar la sesion del usuario abierta. 
	public function index()
	{
		$session = new Session();
		
		if ($session->getLogin()) {
			$data = [
				'title'	=> 'Bienvenid@ a nuestra tienda',
				'menu'	=> false
			];
			
			$this->view('shop/index', $data);
		} else {
			header('location:' . ROOT);
		}
	}
}