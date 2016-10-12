<?php
require_once"accesoDatos.php";
class mascota
{
//--ATRIBUTOS
	public $id;
	public $edad;
 	public $nombre;
  	public $tipo;
  	public $foto;
  	public $sexo;
  	public $fechaNacimiento;
//--GETTERS Y SETTERS
  	public function GetId()
	{
		return $this->id;
	}
	public function Getnombre()
	{
		return $this->nombre;
	}
	public function GetFecha()
	{
		return $this->fechaNacimiento;
	}
	public function GetSexo()
	{
		return $this->sexo;
	}
	public function Getedad()
	{
		return $this->edad;
	}
	public function Gettipo()
	{
		return $this->tipo;
	}
	public function GetFoto()
	{
		return $this->foto;
	}

	public function SetId($valor)
	{
		$this->id = $valor;
	}
	public function Setnombre($valor)
	{
		$this->nombre = $valor;
	}
	public function Setedad($valor)
	{
		$this->edad = $valor;
	}
	public function Settipo($valor)
	{
		$this->tipo = $valor;
	}
	public function SetFoto($valor)
	{
		$this->foto = $valor;
	}
//--CONSTRUCTOR
	public function __construct($val=NULL)
	{
		if($val != NULL){
			$obj = mascota::TraerUnamascota($val);			
			$this->nombre = $obj->nombre;
			$this->edad = $obj->edad;
			$this->tipo = $obj->$tipo;
			$this->foto = $obj->foto;
			$this->foto = $obj->sexo;
			$this->foto = $obj->fechaNacimiento;
		}
	}
//--TOSTRING	
  	public function ToString()
	{
	  	return $this->nombre."-".$this->edad."-".$this->tipo."-".$this->foto."-".$this->fechaNacimiento."-".$this->sexo;
	}

//--METODO DE CLASE
	public static function TraerUnamascota($idParametro) 
	{	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//CREATE DEFINER=`root`@`localhost` PROCEDURE `TraerUnamascota`(IN `idd` INT) NO SQL select * from objectVO where id = idd
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM objectVO WHERE id=:id");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();
		$mascotaBuscada= $consulta->fetchObject('objectVO');
		return $mascotaBuscada;	
	}
	public static function TraerTodasLasmascotas()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//CREATE DEFINER=`root`@`localhost` PROCEDURE `TraerTodasLasmascota`() NO SQL SELECT * FROM objectVO
		//OJO QUE EL STORE CORTA EL NOMBRE
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM objectVO");
		$consulta->execute();			
		$arrmascotas= $consulta->fetchAll(PDO::FETCH_CLASS, "objectVO");	
		return $arrmascotas;
	}
	public static function Borrarmascota($idParametro)
	{	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//CREATE DEFINER=`root`@`localhost` PROCEDURE `Borrarmascota`(IN `idp` INT) NO SQL delete from objectVO	WHERE id=idp
		$consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM objectVO	WHERE id=:id");	
		$consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
		
	}
	public static function Modificarmascota($mascota)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			//CREATE DEFINER=`root`@`localhost` PROCEDURE `Modificarmascota`(IN `idc` INT, IN `nombret` VARCHAR(50), IN `edadt` VARCHAR(50), IN `tipot` VARCHAR(50), IN `fotot` VARCHAR(50))NO SQL update objectVO	set nombre=nombret,	edad=edadt,	tipo=tipot,	foto=fotot WHERE id=idc
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE objectVO SET nombre=:nombre,	edad=:edad, fechaNacimiento=:fechaNacimiento, sexo=:sexo,tipo=:tipo,foto=:foto WHERE id=:id");
			$consulta->bindValue(':id',$mascota->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre', $mascota->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':edad',$mascota->edad, PDO::PARAM_STR);
			$consulta->bindValue(':tipo',$mascota->tipo, PDO::PARAM_STR);
			$consulta->bindValue(':fechaNacimiento', $mascota->fechaNacimiento, PDO::PARAM_STR);
			$consulta->bindValue(':sexo', $mascota->sexo, PDO::PARAM_STR);
			$consulta->bindValue(':foto', $mascota->foto, PDO::PARAM_STR);
			return $consulta->execute();
	}
	public static function Insertarmascota($mascota)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//OJO COMO PASAS LOS PARAMETROS AL STORE.
		//CREATE DEFINER=`root`@`localhost` PROCEDURE `Insertarmascota`(IN `nombret` VARCHAR(50), IN `edadt` VARCHAR(50), IN `tipot` VARCHAR(50), IN `fotot` VARCHAR(100)) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER INSERT into objectVO (nombre,edad,tipo,foto) values (nombret, edadt, tipot, fotot)
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into objectVO (nombre,edad,tipo,foto,fechaNacimiento,sexo) values (:nombre, :edad, :tipo, :foto,:fechaNacimiento,:sexo)");
		$consulta->bindValue(':edad',$mascota->edad, PDO::PARAM_STR);
		$consulta->bindValue(':nombre', $mascota->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':tipo', $mascota->tipo, PDO::PARAM_STR);
		$consulta->bindValue(':fechaNacimiento', $mascota->fechaNacimiento, PDO::PARAM_STR);
		$consulta->bindValue(':sexo', $mascota->sexo, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $mascota->foto, PDO::PARAM_STR);
		$consulta->execute();		
		return $objetoAccesoDato->RetornarUltimoIdInsertado();		
	}	

	/*
	id = id
	nombre=apellido
	edad=nombre
	tipo=dni
	foto = foto
	clase= objectVO
	*/
}
?>