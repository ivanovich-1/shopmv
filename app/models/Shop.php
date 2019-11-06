<?php
/**
 *  Clase Shop
 */
class Shop
{
	private $db;
	
	function __construct()
	{
		// Accedemos a la base de datos
		$this->db = MySQLdb::getInstance()->getDatabase();
	}
}