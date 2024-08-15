<?php

	include( "nucleo.php" );
	$obj_nucleo = new nucleo(); 

	include("menus.php");
	echo imprimir_menu();
	
	$id_encuesta = $_GET[ 'id_encuesta' ];

	echo "<br><br>";
	$row="";
	if( isset( $_GET[ 'documento_invisible' ] ) )
	{
		$conteo_preguntas = 0;
		$row.="<div class='row'>";
		$row.="<div class='col-xs-12 col-md-2'></div>";
		$row.="<div class='col-xs-12 col-md-8'>";
		$row.="<p class='alert alert-info alert-dismissable'><strong>Se han guardado la encuesta.</strong></p><br>";
		$row.="Encuestado: ".$_GET[ 'documento_invisible' ]."<br>";
		$row.= $obj_nucleo->retornar_dato_tabla( "num_doc", $_GET[ 'documento_invisible' ], " CONCAT( nombres, ' ', apellidos ) ", "nombre_completo", "tb_personas"  );
		$row. "<br><br>";
		echo $row;
		$vector = explode( "_", $_GET[ 'combinaciones_preguntas' ] );

		echo "<br>Preguntas a responder: ".( count( $vector ) - 1 )."<br><br>";		
		
		$max_conteo = $obj_nucleo->retornar_dato_tabla( "id_encuesta", $id_encuesta, " MAX( id_pregunta ) ", "max_id_pregunta", "tb_preguntas"  ); 
		$max_fecha = $obj_nucleo->retornar_dato_tabla( "num_doc", $_GET[ 'documento_invisible' ], " MAX( fecha_registro ) ", "max_fecha", "tb_personas_respuestas"  );;
		
		for( $i = 0; $i <= $max_conteo; $i ++ )
		{
			if( isset( $_GET[ 'opciones_'.$i ] ) )
			{
				$conteo_preguntas += $obj_nucleo->obj_encuesta->guardar_respuesta_encuestado( $_GET[ 'documento_invisible' ], $_GET[ 'opciones_'.$i ] );
			}
		}

		echo "Preguntas contestadas: ".$conteo_preguntas;
		echo "<br><br>";
		echo "Si se presentan varias respuestas a una pregunta, es probable que un usuario haya respondido en diferentes d&iacute;as.<br>";
		echo $obj_nucleo->obj_encuesta->retornar_datos_encuesta_usuario( $_GET[ 'documento_invisible' ], $max_fecha );
		$row.="</div>";
		$row.="<div class='col-xs-12 col-md-2'></div>";
		$row.="</div>";
		
	}
	
?>