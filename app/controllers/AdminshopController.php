<?php
/**
 * Panel de administracion de la tienda
 */
class AdminshopController extends Controller
{
	
	private $model;

	function __construct()
	{
		$this->model = $this->model('AdminShop');
	}
	
	// Se guardan todos los parametros que quemos mostrar en la vista. Segun si tenemos una sesion activa o no mostramos una vista o reenviamos al formulario para que loguee al usuario.
	public function index()
	{
		$session = new Session();

		if ($session->getLogin()) {
			$data = [
				'title'	=> 'Administración | Inicio',
				'menu'	=> false,
				'admin' => true,
				'subtitle' => 'Administración de la tienda'
			];
			$this->view('admin/shop/index', $data);
		}else {
			header('location'. ROOT . 'admin');
		}
	}
}