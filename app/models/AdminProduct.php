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

	// Consulta la base de datos para mandarle los datos que recibimos del formulario para crear un nuevo producto
	public function createProduct($data)
	{
		$sql = 'INSERT INTO products(type, name, description, price, discount, send, image, published, relation1, relation2, relation3, mostSold, new, status, deleted, create_at, updated_at, deleted_at, author, publisher, pages, people, objetives, necesites) VALUES (:type, :name, :description, :price, :discount, :send, :image, :published, :relation1, :relation2, :relation3, :mostSold, :new, :status, :deleted, :create_at, :updated_at, :deleted_at, :author, :publisher, :pages, :people, :objetives, :necesites)';
		$params = [
			':type' => $data['type'], 
			':name' => $data['name'], 
			':description' => $data['description'], 
			':price' => $data['price'], 
			':discount' => $data['discount'], 
			':send' => $data['send'], 
			':image' => $data['image'], 
			':published' => $data['published'], 
			':relation1' => $data['relation1'], 
			':relation2' => $data['relation2'], 
			':relation3' => $data['relation3'], 
			':mostSold' => $data['mostSold'], 
			':new' => $data['new'], 
			':status' => $data['status'], 
			':deleted' => 0, 
			':create_at' => date('Y-m-d H:i:s'), 
			':updated_at' => null, 
			':deleted_at' => null,
			':author' => $data['author'], 
			':publisher' => $data['publisher'], 
			':pages' => $data['pages'], 
			':people' => $data['people'], 
			':objetives' => $data['objetives'], 
			':necesites' => $data['necesites']
		];
		
		$query = $this->db->prepare($sql);
		$response = $query->execute($params);
		return $response;
	}
}