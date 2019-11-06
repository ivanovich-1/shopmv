<?php
/**
 * Clase para la Administración
 */
class AdminController extends Controller
{
	private $model;
	
	public function __construct()
	{
		$this->model = $this->model('Admin');
	}
	// Aqui guardamos los valores que le queremos dar a la vista.
	public function index()
	{
		$data = [
			'title'	=> 'Adminstración',
			'subtitle' => 'Módulo de Administración',
			'menu'	=> false
		];
		
		$this->view('admin/index', $data);
	}

	// Verificamos todos los datos del administrador que son introducidos por el formulario.
	public function verifyUser()
	{
		$errors = [];
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = isset($_POST['user']) ? $_POST['user'] : '';
			$password = isset($_POST['password']) ? $_POST['password'] : '';
			$dataForm = [
				'email'	=> $email,
				'password' => $password
			];
			if (empty($email)) {
				array_push($errors, 'El correo del usuario es obligatorio');
			}
			if ( ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
				array_push($errors, 'El correo electrónico no es válido');
			}
			if (empty($password)) {
				array_push($errors, 'La contraseña es obligatoria');
			}
			if (count($errors) == 0) {
				$errors = $this->model->verifyUser($dataForm);
				if (count($errors) == 0) {
					$session = new Session();
					$session->login($dataForm);
					header('location:'. ROOT . 'Adminshop');
				}
			}

		}
		$data = [
			'title'	=> 'Administración',
			'subtitle' => 'Módulo de Administración',
			'menu'	=> false,
			'errors'=> $errors
		];
		$this->view('admin/index', $data);

	}
}