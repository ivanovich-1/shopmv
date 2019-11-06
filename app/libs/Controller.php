<?php  
/**
 * 
 */
class Controller 
{
	
	public function model($model)
	{
		require_once('../app/models/' . $model . '.php');
		return new $model;	// Devuleve una nuena instancia del modelo
	}

	public function view($view, $data = [])
	{
		// Comprobamos si el archivo de vista existe o no, si es asi accedemos a el, sino muere con un mensaje.
		if (file_exists('../app/views/' . $view . '.php')) {
			require_once('../app/views/' . $view . '.php');
		} else {
			die ('La vista no existe');
		}
	}
}
?>