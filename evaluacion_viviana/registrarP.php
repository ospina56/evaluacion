<?php

	include( "nucleo.php" );
	$obj_nucleo = new nucleo();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html ng-app="consulta_exposena_app" xmlns="http://www.w3.org/1999/xhtml">
	<head>            
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?= $obj_nucleo->imprimir_titulo_pagina( "Ingreso de personas" ) ?></title>
		<link rel="shortcut icon" href="expo12.png" type="image/png" />


	</head>

	<body>
		<?php
			include("menu.php");
			echo imprimir_menu();  
		?>
		<div class='row'>
        	<div class='col-xs-12 col-md-4'></div>
        	<div class='col-xs-12 col-md-4 well'>
			<form class="login" action="guardarP.php" method="post">
				Tipo Documento
				<?= $obj_nucleo->retornar_lista_tabla( "cod_tipo_doc", "desc_tipo_doc", "tb_tipo_doc", "tipo_doc" ) ?>
				<br>
				<br>
				Documento:
				<input class="form-control" type="text" name="num_doc" maxlength="12" min="8" ng-model="datos" ng-change="cargar_datos()">
				<br>
				<br>
				Nombres:<input class="form-control" type="text" name="nombres" value="{{ datos.nombres}}">
				<br>
				<br>
				Apellidos:<input class="form-control" type="text" name="apellidos">
				<br>
				<br>
				Fecha nacimiento:<input class="form-control" type="date" name="fecha_nacimiento">
				<br>
				<br>
				Teléfono:</li><input class="form-control" type="text" name="telefono">
				<br>
				<br>
				Nivel de formación:
				<?= $obj_nucleo->retornar_lista_tabla( "cod_rol", "desc_rol", "tb_roles", "rol" ) ?>
				<br>
				<br>
				Genero
				<?= $obj_nucleo->retornar_lista_tabla( "cod_genero", "genero", "tb_genero", "genero" ) ?>
				<br><br>
				<input class="btn btn-md btn-primary btn-block" type="submit" value="Ingresar">
			</form>
		</div>
	<div class='col-xs-12 col-md-4'></div>
	</div>
	<script>
		
		var consulta_museo_app = angular.module( 'consulta_exposena_app', [] );
		
		consulta_museo_app.controller( "consulta_exposena_controlador", 
					      
			[ "$scope", "$http",
				
				function( $scope, $http )
				{
					$scope.cargar_datos = function()
					{
						console.log( $scope.datos );
						if ( $scope.datos != "" )
						{
							$http.get( 'consultar_info_tecleando.php?cadena=' + $scope.datos ).success
							(								
								function( response )
								{
									$scope.informacion = response.records;
								}
							);

						}else{
								$scope.informacion = "";
						}
					}
					$scope.impresion = "Funciona el angular";
				}				
			]					      
		);
		
	</script>

	</body>
</html>
