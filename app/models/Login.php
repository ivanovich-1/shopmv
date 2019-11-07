<?php  

/**
 * Modelo Login
 */
class Login
{
	
	private $db;

	 function __construct()
	{
		// Accedemos a la base de datos 
		$this->db = MySQLdb::getInstance()->getDatabase();
	}

	public function createUser($data)
	{
		// creamos un nuevo usuario con los datos que recibimos en el registro y lo enviamos a la base de datos, en el caso de que el email no exista ya en la base de datos.
		$response = false;
		
		if ( ! $this->existsEmail($data['email'])) {
			
			$password = hash_hmac('sha512', $data['password'], ENCRIPTKEY);
			
			$sql = 'INSERT INTO users(first_name, last_name_1, last_name_2, email, password, address, city, state, zipcode, country) VALUES(:first_name, :last_name_1, :last_name_2, :email, :password, :address, :city, :state, :zipcode, :country)';
			
			$query = $this->db->prepare($sql);
			
			$params = [
				':first_name' => $data['first_name'], 
				':last_name_1' => $data['last_name_1'], 
				':last_name_2' => $data['last_name_2'], 
				':email' => $data['email'], 
				':password' => $password, 
				':address' => $data['address'], 
				':city' => $data['city'], 
				':state' => $data['state'], 
				':zipcode' => $data['postcode'], 
				':country' => $data['country']
			];
			
			$response = $query->execute($params);
		}
		return $response;
	}

	public function existsEmail($email)
	{
		// Realizamos una consulta y la comprobación la guardamos en $query
		$sql = 'SELECT * FROM users WHERE email=:email';
		$query = $this->db->prepare($sql);
		$query->execute([':email' => $email]);
		return $query->rowCount();
	}

	public function getUserByEmail($email)
	{
		// Sacamos la informacion sobre el usuario de dicho email, devuelve los datos en forma de objeto
		$sql = 'SELECT * FROM users WHERE email=:email';
		$query = $this->db->prepare($sql);
		$query->execute([':email' => $email]);
		return $query->fetch(PDO::FETCH_OBJ);
	}

	// Vamos a desarrollar el email para enviarlo
	public function sendEmail($email)
	{
		$user = $this->getUserByEmail($email);

		$fullName = $user->first_name . ' ' . $user->last_name_1 . ($user->last_name_2 != '' ? ' ' . $user->last_name_2 : '');
		
		$msg = $fullName . ' accede al siguiente enlace para cambiar tu contraseña.<br>';
		$msg.='<a href="' . ROOT . 'login/changepassword/' . $user->id . '">Cambia tu clave de acceso</a>';
		
		$headers = 'MIME-Version: 1.0\r\n';
		$headers.= 'Content-type:text/html; charset=UTF-8\r\n';
		$headers.= 'From: ShopMV\r\n';
		$headers.= 'Reply-to:administracion@shopmv.local';
		
		$subject = 'Cambio de contraseña en ShopMV';
		
		return mail($email, $subject, $msg, $headers);
	}

	// Encriptaremos la contraseña y la cambiamos con la que habia en la base de datos.
	public function changePassword($id, $password)
	{
		$pass = hash_hmac('sha512', $password, ENCRIPTKEY);
		
		$sql = 'UPDATE users SET password=:password WHERE id=:id';
		
		$query = $this->db->prepare($sql);
		
		$params = [
			':password'	=> $pass,
			':id'		=> $id
		];
		
		return $query->execute($params);
	}

	// Vamos a verificar al usuario con el email y la contraseña, es decir, si el usuario existe en nuestra base de datos o si la contrasela no es la correcta.
	public function verifyUser($email, $password) {
		$errors = [];
		$user = $this->getUserByEmail($email);
		$pass = hash_hmac('sha512', $password, ENCRIPTKEY);
		if ( ! $user ) {
			array_push($errors, 'El usuario no existe en nuestros registros');
		} elseif($user->password != $pass) {
			array_push($errors, 'La contraseña no es correcta');
		}
		return $errors;
	}
}
?>