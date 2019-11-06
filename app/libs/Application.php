<?php  
/**
 * La clase Application procesa la URL
 */
class Application
{
	private $url_controller = null;
	private $url_action = null;
	private $url_params = [];

	function __construct()
	{
	
		$url = $this->separarURL();

		if (! $this->url_controller) {
			// Si no existe url_controller añadimos un controlador login en la direccion indicada en require_once, creamos el objeto de la clase loginController y llamamos a un metodo llamado index()
			require_once ('../app/controllers/LoginController.php');
			$page = new LoginController();
			$page->index(); 

		} elseif (file_exists('../app/controllers/'.ucfirst($this->url_controller).'Controller.php')) {
			// Comprobamos si existe el fichero del controlador, posteriormente creamos un nuevo objeto
			$controller = ucfirst($this->url_controller) . 'Controller';
			require_once('../app/controllers/' . $controller . '.php');
			$this->url_controller = new $controller;

			if (method_exists($this->url_controller, $this->url_action) && is_callable(array($this->url_controller, $this->url_action))) {
				// Si el metodo existe y es llamable la funcion entramos
				if (! empty($this->url_params)) {
					// Si el array de params no esta vacio 
					call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);		// Guardamos los datos en un array 
				} else {
					// Llamamos al metodo de un objeto
					$this->url_controller->{$this->url_action}();
				}
			} else {
				if (strlen($this->url_action) == 0) {
					// Comprobamos que la longitud del parametro es igual que cero y si es asi nos dirigimos al metodo index
					$this->url_controller->index();
				
				} else {
					// Si se escribe algo que no existe se lanza un mensaje en una nueva pagina de error
					header('HTTP/1.0 404 Not Found');
				}
			}
		} else {

			require_once ('../app/controllers/LoginController.php');
			$page = new LoginController();
			$page->index();
		}
	}

	private function separarURL()
	{
		if ($_SERVER['REQUEST_URI'] != '/') {
			$url = trim($_SERVER['REQUEST_URI'], '/'); // Elimina los espacios en blanco al principio y al final 
			$url = filter_var($url, FILTER_SANITIZE_URL); // Comprueba si hay algun caracter raro y lo filtra
			$url = explode('/', $url); // Formamos un array de url con el simbolo '/' como corte para coger los diferentes valores del array

			// Comprobamos si existen los valores del controller y del action
			$this->url_controller = isset($url[0]) ? $url[0] : null;
			$this->url_action = isset($url[1]) ? $url[1] : null;

			// Borramos la posicion 0 y 1 de la url
			unset($url[0], $url[1]);

			// Lo valores que quedan en la url se los añadimos al array params
			$this->url_params = array_values($url);
		}
	}
}
?>