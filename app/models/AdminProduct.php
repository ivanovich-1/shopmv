<?php
/**
 * Modelo de administracion de productos
 */
class AdminProduct
{
	private $db;

	public function __construct()
	{
		$this->db = MySQLdb::getInstance()->getDatabase();
	}

	// Consulta a la base de datos para coger los datos de los productos.
	public function getProducts()
	{
		$sql = 'SELECT * FROM products WHERE deleted = 0';
		$query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_OBJ);
	}
	// Consulta a la base de datos para coger el estado del usuario mediante el type.
	public function getConfig($type)
	{
		$sql = 'SELECT * FROM config WHERE type=:type ORDER BY value';
		$query = $this->db->prepare($sql);
		$query->execute([':type' => $type]);
		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	// Consulta la base de datos para coger todos los datos de los productos que tenemos en catalogo
	public function getCatalogue() 
	{
		$sql = 'SELECT id, name, type FROM products WHERE deleted=0 AND status!=0 ORDER BY type, name';
		$query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_OBJ);
	}
}