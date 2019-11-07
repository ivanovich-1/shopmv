<?php
/**
 * Controlador de administracion de productos
 */
class AdminproductController extends Controller
{
	private $model;
	public function __construct()
	{
		$this->model = $this->model('AdminProduct');
	}
	public function index()
	{
		$session = new Session();

		if($session->getLogin()){
			
			$products = $this->model->getProducts();
			$type = $this->model->getConfig('productType');
			
			$data = [
				'title' => 'Administración de productos',
				'menu' => false,
				'admin' => true,
				'type' => $type,
				'data' => $products
			];
			
			$this->view('admin/products/index',$data);
		}else{
			header('location:'. ROOT .'admin');
		}
	}
	public function create()
	{
		$type = $this->model->getConfig('productType');
		$status = $this->model->getConfig('productStatus');
		$catalogue = $this->model->getCatalogue();

		$data = [
			'title' => 'Administración de productos - Alta',
			'menu' => false,
			'admin' => true,
			'type' => $type,
			'status' => $status,
			'catalogue' => $catalogue
		];
		$this->view('admin/products/create',$data);
	}
	public function update($id)
	{
	}
	public function delete($id)
	{
	}
}