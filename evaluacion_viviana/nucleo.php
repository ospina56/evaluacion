<?php

include( "encuesta.php" );

class nucleo
{
	
	public $obj_encuesta;	
	
	function __construct ()
	{
		$this->obj_encuesta = new Encuesta();
	}
	
	function insertar($genero)
	{
		include("config.php");
		
		$sql = " INSERT INTO tb_genero(genero)";
		$sql.= " VALUES ('$genero')";
		$respuesta = mysql_query( $sql );
	}
	
	function guardar_personas( $tipo_doc, $num_doc, $nombres, $apellidos, $rol, $genero, $telefono = null, $fecha_nacimiento = null )
	{		
		include("config.php"); 
	
		$tipo_doc		= TRIM( $tipo_doc );
		$num_doc		= $this->ajustar_nombres_propios( $num_doc );
		$nombres		= $this->ajustar_nombres_propios( $nombres );
		$apellidos		= $this->ajustar_nombres_propios( $apellidos );
		$rol			= TRIM( $rol );
		$genero			= TRIM( $genero );
	
		$respuesta = 0; 
		$error = "";
		$existe_usuario = 0;

		$existe_usuario = $this->retornar_dato_tabla( "num_doc", $num_doc, " COUNT( num_doc ) ", "num_doc", "tb_personas" );
		
		if( $existe_usuario * 1 == 0 )
		{
			$error = $this->validar_campos_ingreso_personas( $tipo_doc, $num_doc, $nombres, $apellidos, $rol, $genero );
			
			if( $error == "" )
			{
				
				$sql  = " INSERT INTO tb_personas( num_doc, nombres, apellidos,cod_rol, cod_genero, fecha_registro, sn_mostrar, sn_contar, cod_tipo_doc, cod_programa, fecha_nacimiento, telefono ) ";
				$sql .= " VALUES( '$num_doc', '$nombres', '$apellidos', '$rol', '$genero', NOW(), 's', 's', '$tipo_doc', 1, '$fecha_nacimiento', '$telefono' ) ";
				
				//echo $sql;
				
				$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
				$resultado = $conexion->query( $sql );
				
				if( $conexion->affected_rows > 0 ) //Si se afecta o inserta alguna fila, se retornará uno.
				{
					$respuesta = "Se guardaron o modificaron ".$conexion->affected_rows." registros correctamente.";
					
				}else{
						$respuesta = "Error: No se realizaron ingresos <strong>nuevos</strong>, es probable que la información ya exista.";	
					}
				
				
			}else{
					$respuesta = $error;	
				}
		
		}else{
		
			$error = $this->validar_campos_ingreso_personas( $tipo_doc, $num_doc, $nombres, $apellidos, $rol, $genero, 1 );
			
			if( $error * 1 <= 4 ) 
			{
	
	
				$error = 0;
				
				if( $tipo_doc."" != "-1" ) 	$error += $this->actualizar_campo_tabla( "num_doc", $num_doc, "cod_tipo_doc", $tipo_doc, "tb_personas" );
				if( $nombres != "" ) 		$error += $this->actualizar_campo_tabla( "num_doc", $num_doc, "nombres", $nombres, "tb_personas" );
				if( $apellidos != "" ) 		$error += $this->actualizar_campo_tabla( "num_doc", $num_doc, "apellidos", $apellidos, "tb_personas" );
				if( $rol."" != "-1" ) 		$error += $this->actualizar_campo_tabla( "num_doc", $num_doc, "rol", $rol, "tb_personas" );
				if( $genero."" != "-1" )	$error += $this->actualizar_campo_tabla( "num_doc", $num_doc, "cod_genero", $genero, "tb_personas" );
				
				$respuesta = "<br>Se actualizaron ".$error." datos de la persona.<br><br> ";
			}			
		}
		
		return $respuesta;		
	}
	
	function validar_campos_ingreso_personas( $tipo_doc, $num_doc, $nombres, $apellidos, $rol, $genero, $des = null )
	{
		$error = "";
		$cuenta_error = 0;
		
		if( $tipo_doc == "" || $tipo_doc * 1 == -1 ){ $error = "Error: Debe seleccionar un tipo de documento."; $cuenta_error ++; }
		if( strlen( $num_doc ) < 4 ){ $error = "Error: Debe ingresar un documento correcto.";  $cuenta_error ++; }
		if( strlen( $nombres ) < 3 ){ $error = "Error: Debe ingresar el nombre de la persona correctamente.";  $cuenta_error ++; }
		if( strlen( $apellidos ) < 3 ){ $error = "Error: Debe ingresar los apellidos de la persona correctamente.";  $cuenta_error ++; }
		if( $rol == "" || $rol * 1 == -1 ){ $error = "Error: Debe seleccionar un rol de la lista de roles.";  $cuenta_error ++; }
		if( $genero == "" || $genero * 1 == -1 ){ $error = "Error: Debe seleccionar el género de la persona.";  $cuenta_error ++; }
		
		if( $des != null ) $error = $cuenta_error;
		
		return $error;
	}
	
	function insertar_rol( $n_rol )
	{		
		include( "config.php" );
		$n_rol = $this->ajustar_nombres_propios( $n_rol );  
		
		$sql  = " insert into tb_rol( n_rol ) ";
		$sql .= " values( '$n_rol' ) ";
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		if( $conexion->affected_rows > 0 ) $respuesta = 1; 
		
		return $respuesta;
	}
	
	function actualizar_campo_tabla( $campo_busqueda, $valor_busqueda, $campo_a_actualizar, $valor_a_actualizar, $tabla, $dir_config = null )
	{
		if( $dir_config != null )
		{
			include( TRIM( $dir_config )."config.php" );
			
		}else{
				include( "config.php" );	
			}
				
		$sql  = " UPDATE $tabla SET $campo_a_actualizar = '".TRIM( $valor_a_actualizar )."' ";
		$sql .= " WHERE $campo_busqueda = '".$valor_busqueda."' ";
		
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		return $conexion->affected_rows; 
	}
	
	function actualizar_url_imagen( $cod_tipo_doc, $num_doc, $url_imagen, $dir_config = null )
	{
		if( $dir_config != null )
		{
			include( TRIM( $dir_config )."config.php" );
			
		}else{
				include( "config.php" );	
			}
				
		$sql  = " UPDATE tb_personas SET url_imagen = '".TRIM( $url_imagen )."' ";
		$sql .= " WHERE cod_tipo_doc = ".$cod_tipo_doc;
		$sql .= " AND   num_doc = ".$num_doc;

		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		return $conexion->affected_rows;
	}
	
	function imprimir_roles()
	{
		include( "config.php" );
		
		$salida = "";
		
		$sql = " SELECT * FROM tb_roles ORDER BY desc_rol ";
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		if( mysqli_num_rows( $resultado ) > 0 )
		{
			while( $fila = mysqli_fetch_assoc( $resultado ) )
			{   
				$salida .= "<br>".$fila[ 'desc_rol' ];   	
			}
		}
		
		return $salida;
	}
	
	function retornar_lista_tabla( $campo_id, $campo_descripcion, $tabla, $nombre_lista )
	{
		include( "config.php" );
		
		$sql  = " SELECT $campo_id AS $campo_id, $campo_descripcion AS $campo_descripcion ";
		$sql .= " FROM $tabla ";
		$sql .= " WHERE TRIM( $campo_descripcion ) <> '' ";
		$sql .= " ORDER BY $campo_descripcion ";
		$salida = ""; 
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		if( mysqli_num_rows( $resultado ) > 0 )
		{
			$salida  = "<select class='form-control' name = '$nombre_lista'>";
			$salida .= "<option value='-1'>Seleccione</option>";
			
			while( $fila = mysqli_fetch_assoc( $resultado ) )
			{   
				$salida .= "<option value='".$fila[ $campo_id ]."'>".$fila[ $campo_descripcion ]."</option>";   	
			}
			
			$salida .= "</select>";
		}
		
		return utf8_encode( $salida );
	}
	
	function retornar_dato_tabla( $campo_busqueda, $valor_busqueda, $campo_a_retornar, $alias_campo, $tabla, $dir_config = null )
	{
		if( $dir_config != null )
		{
			include( TRIM( $dir_config )."config.php" );
			
		}else{
				include( "config.php" );	
			}
		
		$sql  = " SELECT $campo_a_retornar AS $alias_campo ";
		$sql .= " FROM $tabla ";
		$sql .= " WHERE $campo_busqueda = '".TRIM( $valor_busqueda )."' ";

		$salida = ""; 
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		if( mysqli_num_rows( $resultado ) > 0 )
		{		
			while( $fila = mysqli_fetch_assoc( $resultado ) )
			{   
				$salida .= $fila[ $alias_campo ];   	
			}
		}
		
		return utf8_decode( $salida );

	}
	
	function guardar_visitas( $identif_1, $identif_2, $dir_config = null )
	{
		if( $dir_config != null )
		{
			include( TRIM( $dir_config )."config.php" );
			
		}else{
				include( "config.php" );	
			}
				
		$sql  = " INSERT INTO tb_personas_registros( cod_pers_reg, identif_1, identif_2, fec_reg ) ";
		$sql .= " VALUES( null, '$identif_1', '$identif_2', NOW() ) ";
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		return $conexion->affected_rows; 
	}
	
	
	function retornar_conteo_tabla( $tabla, $campo_fecha = null, $campo_conteo = null, $dir_config = null )
	{
		if( $dir_config != null )
		{
			include( TRIM( $dir_config )."config.php" );
			
		}else{
				include( "config.php" );	
			}
			
		if( $campo_fecha == null ) $campo_fecha = " fecha_registro ";
		if( $campo_conteo == null ) $campo_conteo = " COUNT( * ) ";
		
		$sql  = " SELECT CONCAT( DAY( $campo_fecha ), '/', MONTH( $campo_fecha ), '/', YEAR( $campo_fecha )  ) AS fecha, $campo_conteo AS conteo ";
		$sql .= " FROM $tabla ";
		$sql .= " GROUP BY fecha ";
		$sql .= " ORDER BY fecha DESC ";
		$salida = ""; 
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		if( mysqli_num_rows( $resultado ) > 0 )
		{
			$salida .= "<div class='row'>";
			
			while( $fila = mysqli_fetch_assoc( $resultado ) )
			{
				$salida .= "<div class='col-xs-3 col-md-3 col-lg-4'>" . $fila[ 'fecha' ] . "</div>" ;
				$salida .= "<div class='col-xs-1 col-md-1 col-lg-1'> <b>:</b> </div>" ;
				$salida .= "<div class='col-xs-3 col-md-3 col-lg-4'>" . $fila[ 'conteo' ] . "</div>" ; 
			}
			
			$salida .= "</div>";
		}
		
		return utf8_decode( $salida );
	}
	
	function retornar_conteo_roles( $campo_fecha = null, $dir_config = null )
	{
		if( $dir_config != null )
		{
			include( TRIM( $dir_config )."config.php" );
			
		}else{
				include( "config.php" );	
			}
		
		$sql  = " SELECT COUNT( t1.cod_rol ) AS conteo, t2.desc_rol AS rol ";
		if( $campo_fecha != null ) $sql .= " , CONCAT( DAY( t1.fecha_registro ), '/', MONTH( t1.fecha_registro ), '/', YEAR( t1.fecha_registro )  ) AS fecha ";
		$sql .= " FROM tb_personas t1, tb_roles t2 ";
		$sql .= " WHERE t1.cod_rol = t2.cod_rol ";
		$sql .= " GROUP BY t2.desc_rol ";
		if( $campo_fecha != null ) $sql .= " , fecha ";
		$sql .= " ORDER BY ";
		if( $campo_fecha != null ) $sql .= " fecha DESC, ";
		$sql .= " t2.desc_rol ";		
		$salida = ""; 
		
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );
		
		if( mysqli_num_rows( $resultado ) > 0 )
		{
			$salida .= "<div class='row  '>";
			
			while( $fila = mysqli_fetch_assoc( $resultado ) )
			{
				$salida .= "<div class='col-xs-1 col-md-1 col-lg-1'>" . $fila[ 'conteo' ] . "</div>" ;
				if( $campo_fecha != null ) $salida .= "<div class='col-xs-3 col-md-3 col-lg-3'>" . $fila[ 'fecha' ] . "</div>" ;
				$salida .= "<div class='col-xs-9 col-md-8 col-lg-8'> <b>".$fila[ 'rol' ]."</b> </div> <br>" ;

				
			}
			
			$salida .= "</div>";
		}
		
		return utf8_decode( $salida );

	}
	
	function consultar_info_tecleando( $texto_a_buscar )
        {
		
		include( "config.php" );
		
		$texto_a_buscar = trim( $texto_a_buscar );
		
		if( $texto_a_buscar != "" )
		{
			
			$sql  = " SELECT * ";
			$sql .= " FROM tb_personas t1, tb_roles t2 ";
			$sql .= " WHERE t1.cod_rol = t2.cod_rol ";
			$sql .= " AND t1.num_doc = '$texto_a_buscar'";
			$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
			$resultado = $conexion->query( $sql );
			
			$salida = "";
			
			if( mysqli_num_rows( $resultado ) > 0 )
			{                    
				while( $fila = mysqli_fetch_assoc( $resultado ) )
				{                        
					if( $salida != "" ) $salida .= ",";
					$salida .= '{"documento":"'.$fila[ "num_doc" ].'",';
					$salida .= '"nombres":"'.utf8_encode( $fila[ "nombres" ] ).'",';
					$salida .= '"apellidos":"'.utf8_encode( $fila[ "apellidos" ] ).'",';

					$salida .= '"desc_rol":"'.utf8_encode( $fila[ "cod_rol" ] ).'"}';
				}
				
				$salida ='{"records":['.$salida.']}';
			}  
		}
		
		return $salida;
        }
	
	/**
	 * @param	cadena		
	 */
	function ajustar_nombres_propios( $cadena )
	{
		return ucwords( trim( strtolower( preg_replace( "/\s+/", " ", ( str_replace( ".", " ",( $cadena ) ) ) ) ) ) );
	}
	
	function imprimir_titulo_pagina( $cadena )
	{
		return $cadena." EXPOSENA 2015 - CDATTG ";
	}

	
	/**
	 *
	 */
	function conteo_genero($campo, $condicion)
	{
		include( "config.php" );
		
		$sql  = " SELECT COUNT( $campo ) AS conteo ";
		$sql .= " FROM tb_genero t1";
		$sql .= " WHERE t1.$campo = '$condicion' ";
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado2= $conexion->query($sql);
		
		$conte = mysqli_fetch_assoc($resultado2);
		return $conte['conteo'];
	}
	 
	/**
	 * @return 		texto 			Un número que indica el id de la encuesta deseada.
	 */
	static function retornar_id_encuesta()
	{
		include( "config.php" );
		
		return $encuesta;
	}

}

?>