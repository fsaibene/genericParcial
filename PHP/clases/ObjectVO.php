<?php

require_once"accesoDatos.php";

class ObjectVO
{
//--------------------------------------------------------------------------------//
//--ATRIBUTOS
	public $id;
	public $atr;
	public $foto;

//--GETTERS Y SETTERS
  	public function GetId(){
		return $this->id;
	}
	public function GetAtr(){
		return $this->atr;
	}
	public function GetFoto(){
		return $this->foto;
	}

	public function SetAtr($valor){
		$this->atr = $valor;
	}
	public function SetFoto($valor){
		$this->foto = $valor;
	}
//--------------------------------------------------------------------------------//
//--CONSTRUCTOR
	public function __construct($id = NULL){
		if($id){
			$obj = ObjectVO::TraerUnObjeto($id);
			$this->atr = $obj->atr;
			$this->foto = $obj->foto;
//			$this->apellido = $obj->apellido;
//			$this->nombre = $obj->nombre;
//			$this->dni = $dni;
		}
	}
//--------------------------------------------------------------------------------//
//--TOSTRING	
//  	public function ToString()
//	{
//	  	return $this->apellido."-".$this->nombre."-".$this->dni."-".$this->foto;
//	}
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--METODO DE CLASE
	public static function TraerUnObjeto($idParametro){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//$consulta =$objetoAccesoDato->RetornarConsulta("select * from persona where id =:id");
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from obetos where id =:id");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();
		$objetoBuscado= $consulta->fetchObject('ObjectVO');
		return $objetoBuscado;
					
	}
	
	public static function TraerTodosLosObjetos(){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//$consulta =$objetoAccesoDato->RetornarConsulta("select * from persona");
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from objetos");
		$consulta->execute();			
		$arrObjetos= $consulta->fetchAll(PDO::FETCH_CLASS, "ObjectVO");
		return $arrObjetos;
	}
	
	public static function BorrarObjeto($idParametro){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//$consulta =$objetoAccesoDato->RetornarConsulta("delete from persona	WHERE id=:id");	
		$consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM objetos where id=:id");
		$consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}
	
	public static function ModificarObjeto($objeto){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		/*$consulta =$objetoAccesoDato->RetornarConsulta("
			update persona
			set nombre=:nombre,
			apellido=:apellido,
			foto=:foto
			WHERE id=:id");
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();*/
		$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE objetos SET atr=:atr, foto=:foto");
//		$consulta->bindValue(':id',$objeto->id, PDO::PARAM_INT);
		$consulta->bindValue(':atr',$objeto->atr, PDO::PARAM_STR);
		$consulta->bindValue(':foto',$objeto->foto, PDO::PARAM_STR);
//			$consulta->bindValue(':apellido', $persona->apellido, PDO::PARAM_STR);
//			$consulta->bindValue(':foto', $persona->foto, PDO::PARAM_STR);
		return $consulta->execute();
	}

//--------------------------------------------------------------------------------//

	public static function InsertarObjeto($objeto){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		//$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into persona (nombre,apellido,dni,foto)values(:nombre,:apellido,:dni,:foto)");
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into objetos (atr,foto)values(:atr,:foto)");
//		$consulta->bindValue(':nombre',$objeto->id, PDO::PARAM_INT);
		$consulta->bindValue(':atr', $objeto->atr, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $objeto->foto, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
				
	}	
//--------------------------------------------------------------------------------//

}
