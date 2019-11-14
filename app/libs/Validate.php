<?php  

/**
 * Clase para validaciones
 */
class Validate 
{
	// Validacion de valores numericos 
	public static function number($string) 
	{
		// Buscara los elementos que no queremos que aparezcan, pero que el usuario puede insertar
		$search = [' ', '€', '$', ','];
		$replace = ['', '', '', ''];
		$number = str_replace($search, $replace, $string);	// Sustitulle el valor buscado con el valor reemplazado de la misma posicion en la cadena recibida $string
		return $number;
	}

	// Validacion de la fecha
	public static function date($string)
	{
		$date = explode('-', $string);
		if (count($date) == 1) {
			return false;
		}
		return checkdate($date[1], $date[2], $date[0]);
	}

	// Compara la fecha de publicacion con la fecha actual 
	public static function dateDif($string)
	{
		// Variable con la fecha actual 
		$now = new DateTime();
		// Variable de la fecha que recibimos como parametro 
		$date = new DateTime($string);
		return $date > $now;
	}

	// Valida el nombre del fichero para evitar los caracteres especiales
	public static function file($string)
	{
		$search = [' ', '*', '!', '@', '?', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', '¿', '¡', 'ñ', 'Ñ'];
		$replace = ['-', ''. '', '', '', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', '', '', 'n', 'N'];
		$file = str_replace($search, $replace, $string);
		return $file;
	}

	// Redimensionar la imagen, para en el caso de recibir una imagen de grandes dimensiones poder recortarla
	public static function resizeImage($image, $newWidth)
	{
		// recibimos la localizacion de la imagen
		$file = 'img/' . $image;
		// Recibimos informacion de la imagen
		$info = getimagesize($file);
		$width = $info[0]; 		// Ancho
		$height = $info[1]; 	//Alto
		$type = $info['mime']; 	// Tipo de la imagen

		$factor = $newWidth / $width;
		$newHeight = $factor * $height;

		// Creamos la imagen
		$image = imagecreatefromjpeg($file);

		// Creamos el nuevo fonfo
		$canvas = imagecreatetruecolor($newWidth, $newHeight);

		// Creamos una copia de la imagen
		imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		// Genera la salida, exporta la imagen a un fichero
		imagejpeg($canvas, $file, 80);
	}

	// Comprobar que lo que nos han subido por file realmente es una imagen.
	public static function imageFile($file)
	{
		$info = getimagesize($file);
		$imageType = $info[2];
		return (bool) (in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF]));
	}

	// Sustitucion de las funciones addslashes y htmlentities, que usamos en create() en AdminporductController, para validar cadenas de texto.
	public static function text($string)
	{
		$search = ['^', 'delete', 'drop', 'truncate', 'exec', 'system'];
		$replace = ['-', 'dele*te', 'dr*op', 'trun*cate', 'ex*ec', 'sys*tem'];
		$string = str_replace($search, $replace, $string);
		$string = addslashes(htmlentities($string));
		return $string;
	}


}