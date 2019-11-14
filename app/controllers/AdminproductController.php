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
		$errors = [];
		$dataForm = [];
		
		$typeConfig = $this->model->getConfig('productType');
		$statusConfig = $this->model->getConfig('productStatus');
		$catalogue = $this->model->getCatalogue();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Recibimos la informacion por formulario 
			$type = $_POST['type'] ?? '';
			$name = Validate::text($_POST['name'] ?? '');
			$description = Validate::text($_POST['description'] ?? '');
			$price = Validate::number($_POST['price'] ?? '');
			$discount = Validate::number($_POST['discount'] ?? '');
			$send = Validate::number($_POST['send'] ?? '');
			$image = Validate::file($_FILES['image']['name']);
			$published = $_POST['published'] ?? '';
			$relation1 = $_POST['relation1'] ?? 0;
			$relation2 = $_POST['relation2'] ?? 0;
			$relation3 = $_POST['relation3'] ?? 0;
			$mostSold = isset($_POST['mostSold']) ? '1' : '0';
			$new = isset($_POST['new']) ? '1' : '0';
			$status = $_POST['status'] ?? '';
			// Books
			// htmlentities y addslashes son para que no inserte caracteres que no son convenientes
			$author = Validate::text($_POST['author'] ?? '');
			$publisher = Validate::text($_POST['publisher'] ?? '');
			$pages = Validate::number($_POST['pages'] ?? '');
			// Courses
			$people = Validate::text($_POST['people'] ?? '');
			$objetives = Validate::text($_POST['objetives'] ?? '');
			$necesites = Validate::text($_POST['necesites'] ?? '');
			
			// Validamos la informacion
			if (empty($name)) {
				array_push($errors, 'El nombre del producto es requerido');
			}
			if (empty($description)) {
				array_push($errors, 'La descripcion del producto es requerida');
			}
			if ( ! is_numeric($price)) {
				array_push($errors, 'El precio del producto debe de ser un numero');
			}
			if ( ! is_numeric($discount)) {
				array_push($errors, 'El descuento del producto debe de ser un numero');
			}
			if ( ! is_numeric($send)) {
				array_push($errors, 'Los gasto de envio del producto debe de ser un numero');
			}
			if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
				array_push($errors, 'El descuento no debe ser mayor que el precio');
			}
			if ( ! Validate::date($published)) {
				array_push($errors, 'La fecha o su formato no es correcto');
			} else if (Validate::dateDif($published)) {
				array_push($errors, 'La fecha de publicacion no puede ser posterior a hoy');
			}
			if ($type == 1) {	
				if (empty($people)) {
					array_push($errors, 'El público objetivo del curso es obligatorio');
				}
				if (empty($objetives)) {
					array_push($errors, 'Los objetivos del curso son necesarios');
				}
				if (empty($necesites)) {
					array_push($errors, 'Los requesitos del curso son necesarios');
				}
			} elseif ($type == 2) {
				if (empty($author)) {
					array_push($errors, 'El autor del libro es necesario');
				}
				if (empty($publisher)) {
					array_push($errors, 'La editorial del libro es necesaria');
				}
				if ( ! is_numeric($pages)) {
					$pages = 0;
					array_push($errors, 'La cantidad de paginas de un libro debe de ser un numero');
				}
			} else {
				array_push($errors, 'Debes de seleccionar un tipo valido');
			}


			if ($image) {
				if (Validate::imageFile($_FILES['image']['tmp_name'])) {
					// Comenzamos a tratar la imagen una vez validada
					$image = strtolower($image);	// Pone la cadena de caracteres en minuscula

					if (is_uploaded_file($_FILES['image']['tmp_name'])) {
							move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
								Validate::resizeImage($image, 240);
							} else {
								array_push($errors, 'Error al subir el archivo de imagen');
							}
						}else {
							array_push($errors, 'El formato d imagen no es aceptado');
						}
			} else {
				array_push($errors, 'No he recibido la imagen');
			}
			

			// Creamos el array con los datos
			$dataForm = [
				'type'		=> $type,
				'name'		=> $name,
				'description'=> $description,
				'author'	=> $author,
				'publisher'	=> $publisher,
				'people'	=> $people,
				'objetives'	=> $objetives,
				'necesites'	=> $necesites,
				'price'		=> $price,
				'discount'	=> $discount,
				'send'		=> $send,
				'pages'		=> $pages,
				'published'	=> $published,
				'image'		=> $image,
				'mostSold'	=> $mostSold,
				'new'		=> $new,
				'relation1' => $relation1,
				'relation2' => $relation2,
				'relation3' => $relation3,
				'status'	=> $status
			];
			
			if (empty($errors)) {
				//Enviamos datos al modelo
				if ($this->model->createProduct($dataForm)) {
					header('location:' . ROOT . 'adminproduct');
				}
				array_push($errors, 'Se ha producido algán problema durante la insercion del registro en la BD');

				if (empty($errors)) {
					// Redirigimos al index de adminproduct
				
				}
			}
		}
		$data = [
			'title' => 'Administración de productos - Alta',
			'menu' => false,
			'admin' => true,
			'type' => $typeConfig,
			'status' => $statusConfig,
			'catalogue' => $catalogue,
			'errors' => $errors,
			'data' => $dataForm
		];
		$this->view('admin/products/create',$data);
	}
	
	// Metodo de modificacion del producto. Nos redirige a un formulario para la modificacion de los datos y el status del producto.
	public function update($id)
	{
		$errors = [];
		
		$typeConfig = $this->model->getConfig('productType');
		$statusConfig = $this->model->getConfig('productStatus');
		$catalogue = $this->model->getCatalogue();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Recibimos la informacion por formulario 
			$type = $_POST['type'] ?? '';
			$name = Validate::text($_POST['name'] ?? '');
			$description = Validate::text($_POST['description'] ?? '');
			$price = Validate::number($_POST['price'] ?? '');
			$discount = Validate::number($_POST['discount'] ?? '');
			$send = Validate::number($_POST['send'] ?? '');
			$image = Validate::file($_FILES['image']['name']);
			$published = $_POST['published'] ?? '';
			$relation1 = $_POST['relation1'] ?? 0;
			$relation2 = $_POST['relation2'] ?? 0;
			$relation3 = $_POST['relation3'] ?? 0;
			$mostSold = isset($_POST['mostSold']) ? '1' : '0';
			$new = isset($_POST['new']) ? '1' : '0';
			$status = $_POST['status'] ?? '';
			// Books
			// htmlentities y addslashes son para que no inserte caracteres que no son convenientes
			$author = Validate::text($_POST['author'] ?? '');
			$publisher = Validate::text($_POST['publisher'] ?? '');
			$pages = Validate::number($_POST['pages'] ?? '');
			// Courses
			$people = Validate::text($_POST['people'] ?? '');
			$objetives = Validate::text($_POST['objetives'] ?? '');
			$necesites = Validate::text($_POST['necesites'] ?? '');
			
			// Validamos la informacion
			if (empty($name)) {
				array_push($errors, 'El nombre del producto es requerido');
			}
			if (empty($description)) {
				array_push($errors, 'La descripcion del producto es requerida');
			}
			if ( ! is_numeric($price)) {
				array_push($errors, 'El precio del producto debe de ser un numero');
			}
			if ( ! is_numeric($discount)) {
				array_push($errors, 'El descuento del producto debe de ser un numero');
			}
			if ( ! is_numeric($send)) {
				array_push($errors, 'Los gasto de envio del producto debe de ser un numero');
			}
			if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
				array_push($errors, 'El descuento no debe ser mayor que el precio');
			}
			if ( ! Validate::date($published)) {
				array_push($errors, 'La fecha o su formato no es correcto');
			} else if (Validate::dateDif($published)) {
				array_push($errors, 'La fecha de publicacion no puede ser posterior a hoy');
			}
			if ($type == 1) {	
				if (empty($people)) {
					array_push($errors, 'El público objetivo del curso es obligatorio');
				}
				if (empty($objetives)) {
					array_push($errors, 'Los objetivos del curso son necesarios');
				}
				if (empty($necesites)) {
					array_push($errors, 'Los requesitos del curso son necesarios');
				}
			} elseif ($type == 2) {
				if (empty($author)) {
					array_push($errors, 'El autor del libro es necesario');
				}
				if (empty($publisher)) {
					array_push($errors, 'La editorial del libro es necesaria');
				}
				if ( ! is_numeric($pages)) {
					$pages = 0;
					array_push($errors, 'La cantidad de paginas de un libro debe de ser un numero');
				}
			} else {
				array_push($errors, 'Debes de seleccionar un tipo valido');
			}


			if ($image) {
				if (Validate::imageFile($_FILES['image']['tmp_name'])) {
					// Comenzamos a tratar la imagen una vez validada
					$image = strtolower($image);	// Pone la cadena de caracteres en minuscula

					if (is_uploaded_file($_FILES['image']['tmp_name'])) {
							move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
								Validate::resizeImage($image, 240);
							} else {
								array_push($errors, 'Error al subir el archivo de imagen');
							}
						}else {
							array_push($errors, 'El formato d imagen no es aceptado');
						}
			} 

			// Creamos el array con los datos
			$dataForm = [
				'id' 		=> $id,
				'type'		=> $type,
				'name'		=> $name,
				'description'=> $description,
				'author'	=> $author,
				'publisher'	=> $publisher,
				'people'	=> $people,
				'objetives'	=> $objetives,
				'necesites'	=> $necesites,
				'price'		=> $price,
				'discount'	=> $discount,
				'send'		=> $send,
				'pages'		=> $pages,
				'published'	=> $published,
				'image'		=> $image,
				'mostSold'	=> $mostSold,
				'new'		=> $new,
				'relation1' => $relation1,
				'relation2' => $relation2,
				'relation3' => $relation3,
				'status'	=> $status
			];
			
			if (empty($errors)) {
				//Enviamos datos al modelo
				if ($this->model->updateProduct($dataForm)) {
					header('location:' . ROOT . 'adminproduct');
				}
				array_push($errors, 'Se ha producido algán problema durante la insercion del registro en la BD');

				if (empty($errors)) {
					// Redirigimos al index de adminproduct
				
				}
			}
		}

		// Vamos a capturar el producto que queremos modificar 
		$product = $this->model->getProductById($id);

		$data = [
			'title' => 'Administración de productos - Alta',
			'menu' => false,
			'admin' => true,
			'type' => $typeConfig,
			'status' => $statusConfig,
			'catalogue' => $catalogue,
			'errors' => $errors,
			'product' => $product
		];

		$this->view('admin/products/update',$data);
	}
	
	// Metodo de eliminacion del producto. Muestra un formulario con los datos del producto que se quiere eliminar y si se quiere eliminar se llama al modelo, si es la primera vez que se accede se muestra la vista, si hay errores se muestra la vista con ellos.
	public function delete($id)
	{
		$errors = [];

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$erros = $this->model->delete($id);

			if (empty($errors)) {
				header('location:' . ROOT . 'adminproduct');
			}
		}

		$product = $this->model->getProductById($id);
		$typeConfig = $this->model->getConfig('productType');

		$data = [
			'title' => 'Administración de productos | Eliminacion',
			'menu' => false,
			'admin' => true,
			'data' => $product, 
			'type' => $typeConfig,
			'errors' => $errors
		];

		$this->view('admin/products/delete',$data);
	}
}