<?php
/**
 * Modelo shop de administracion
 */
class AdminShop
{
	private $db;

	function __construct()
	{
		// Accedemos a la base de datos
		$this->db = MySQLdb::getInstance()->getDatabase();

	}
}