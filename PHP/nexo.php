<?php 
include "clases/ObjectVO.php";
if ( !empty( $_FILES ) ){
    $temporal = $_FILES[ 'file' ][ 'tmp_name' ];
    $ruta = "..". DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
    move_uploaded_file( $temporal, $ruta );
}
if(isset($_GET['accion'])){

	$accion=$_GET['accion'];
	if($accion=="traer"){

		$respuesta= array();
		$respuesta['listado']=ObjectVO::TraerTodosLosObjetos();
//		print_r($respuesta);die();
		$arrayJson = json_encode($respuesta);
		echo  $arrayJson;
	}
}else{
	$DatosPorPost = file_get_contents("php://input");
	$respuesta = json_decode($DatosPorPost);
	//var_dump($respuesta);
	switch($respuesta->datos->accion){

		case "borrar":{
			if($respuesta->datos->objeto->foto!="pordefecto.png")
			{
				unlink("../fotos/".$respuesta->datos->objeto->foto);
			}
			ObjectVO::BorrarObjeto($respuesta->datos->objeto->id);
			break;
		}

		case "insertar":{
			// EN EL NOMBRE DE LA FOTO AHORA ESTA ATRIBUTO3 PONER OTRO
			if($respuesta->datos->objeto->foto!="pordefecto.png")
			{
				$rutaVieja="../fotos/".$respuesta->datos->objeto->foto;
				$rutaNueva=$respuesta->datos->objeto->nombre.".".PATHINFO($rutaVieja, PATHINFO_EXTENSION);
				copy($rutaVieja, "../fotos/".$rutaNueva);
				//unlink($rutaVieja);
				$respuesta->datos->objeto->foto=$rutaNueva;
			}
//				var_dump($respuesta->datos->objeto);die();
			ObjectVO::InsertarObjeto($respuesta->datos->objeto);
			break;
		}
		case "buscar":{

			echo json_encode(ObjectVO::TraerUnObjeto($respuesta->datos->id));
			break;
		}
		case "modificar":{
			
			if($respuesta->datos->objeto->foto!="pordefecto.png"){				
//				$rutaVieja="../fotos/".$respuesta->datos->objeto->foto;
				$rutaNueva=$respuesta->datos->objeto->foto;
				copy($rutaVieja, "../fotos/".$rutaNueva);
				//unlink($rutaVieja);
				$respuesta->datos->objeto->foto=$rutaNueva;
			}
			ObjectVO::ModificarObjeto($respuesta->datos->objeto);
			break;
		}
	}
}
?>