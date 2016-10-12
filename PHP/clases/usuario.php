<?php
	require_once("AccesoDatos.php");
	class Usuario
	{
		private $_id;
		private $_correo;
		private $_clave;

	// 	public function GetId()
	// {
	// 	return $this->_id;
	// }

	// public function GetCorreo()
	// {
	// 	return $this->_correo;
	// }
	// public function GetNombre()
	// {
	// 	return $this->_nombre;
	// }
	// public function GetClave()
	// {
	// 	return $this->_clave;
	// }
	// public function GetTipo()
	// {
	// 	return $this->_tipo;
	// }
	// public function SetId($valor)
	// {
	// 	$this._id=$valor;
	// }
	// public function SetCorreo($valor)
	// {
	// 	$this._correo=$valor;
	// }
	// public function SetNombre($valor)
	// {
	// 	$this._nombre=$valor;
	// }
	// public function SetClave($valor)
	// {
	// 	$this._clave=$valor;
	// }
	// public function SetTipo($valor)
	// {
	// 	$this._tipo=$valor;
	// }
	 	public function __construct($id, $correo, $nombre, $clave, $tipo, $foto)
		{
			$this->_id=$id;
			$this->_correo=$correo;
			$this->_clave=$clave;
		
		}
		public static function ToArray()
		{
			$conexion=AccesoDatos::dameUnObjetoAcceso();
			$sentencia=$conexion->RetornarConsulta("SELECT * FROM misusuarios");
			$sentencia->Execute();
			$usuarios=$sentencia->fetchAll(PDO::FETCH_ASSOC);
			$conexion=null;
			return $usuarios;
		}
		public static function TraerUsuarios()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from misusuarios");
		//$consulta =$objetoAccesoDato->RetornarConsulta("CALL TraerTodasLasmascotas() ");
		$consulta->execute();			
		$arrUsuarios= $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");	
		return $arrUsuarios;
	}
		public static function BuscarUsuario($id)
		{
			$conexion = AccesoDatos::dameUnObjetoAcceso();
			$sentencia = $conexion->RetornarConsulta("SELECT * FROM misusuarios WHERE id=:id");
			$sentencia->bindValue(":id", $id, PDO::PARAM_INT);
			$sentencia->Execute();
			$usuario=$sentencia->fetchAll(PDO::FETCH_ASSOC);
			$conexion = null;
			return $usuario;
		}
		public function InsertarUsuario($user)
		{
			$conexion = AccesoDatos::dameUnObjetoAcceso();
			$sentencia = $conexion->RetornarConsulta("INSERT INTO misusuarios(correo, clave) VALUES (:correo, :clave)");
			$sentencia->bindValue(":correo", $user->_correo, PDO::PARAM_STR);
			
			$sentencia->bindValue(":clave", $user->_clave, PDO::PARAM_STR);
			$sentencia->Execute();
			$id = $conexion->lastInsertId();
			$conexion = null;
			return $id;
		}
		public function ModificarUsuario()
		{
			$conexion=AccesoDatos::dameUnObjetoAcceso();
			$sentencia=$conexion->RetornarConsulta("UPDATE misusuarios SET correo=:correo, nombre=:nombre, clave=:clave, tipo=:tipo, foto=:foto WHERE id=:id");
			$sentencia->bindValue(":id", $this->_id, PDO::PARAM_INT);
			$sentencia->bindValue(":correo", $this->_correo, PDO::PARAM_STR);
			$sentencia->bindValue(":nombre", $this->_nombre, PDO::PARAM_STR);
			$sentencia->bindValue(":clave", $this->_clave, PDO::PARAM_STR);
		
			$sentencia->Execute();
			$conexion = null;
		}
		public static function EliminarUsuario($id)
		{
			$conexion=AccesoDatos::dameUnObjetoAcceso();
			$sentencia=$conexion->RetornarConsulta("DELETE FROM misusuarios WHERE id=:id");
			$sentencia->bindValue(":id", $id, PDO::PARAM_INT);
			$sentencia->Execute();
			$conexion = null;
		}
		public function getId()
		{
			return $this->_id;
		}
		public function getMail()
		{
			return $this->_correo;
		}
		public function getUser()
		{
			return $this->_nombre;
		}
		public function getPass()
		{
			return $this->_clave;
		}
		public function getTipo()
		{
			return $this->_tipo;
		}
	}
?>