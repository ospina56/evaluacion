<?php
class Encuesta{
    public function encuestas( $id_encuesta = null){
        include 'config.php';
        $salida="";
        $sql="SELECT * FROM tb_encuestas ";
        if( $id_encuesta !=null)
        $sql .= "WHERE id_encuesta = ".$id_encuesta;
        $sql .="order by fecha_registro DESC LIMIT 1";
        
        $conexion=mysqli_connect($servidor , $usuario, $clave, $bd);
        $resultado=$conexion->query($sql);
        
        if(mysqli_num_rows($resultado)> 0){
            while($fila =mysqli_fetch_assoc($resultado)){
                $salida .= "<br>".$this->preguntas( $id_encuesta );   	

            }
        }
        return utf8_encode($salida);
    }

    public function preguntas($id_encuesta = null){
        include 'config.php';
        $salida="<br><br>";
        $sql=" SELECT * FROM tb_preguntas WHERE id_encuesta = ". $id_encuesta."ORDER BY id_pregunta";
        $conexion=mysqli_connect($servidor, $usuario, $clave, $bd);
        $resultado=$conexion->query($sql);
        if(mysqli_num_rows($resultado)> 0){
            while($fila=mysqli_fetch_assoc($resultado)){
                $salida .="<br>".$fila['texto_pregunta']. $this->respuestas($fila['id_pregunta']);
            }
        }
        return $salida;
    }

    public function respuestas($id_encuesta = null){
        include 'config.php';
        $salida="";
        $sql="SELECT * FROM tb_posibles_respuestas WHERE id_pregunta =$id_pregunta ORDER BY id_posibles_respuestas";
        $conexion=mysqli_connect($servidor,$usuario,$clave,$bd);
        $resultado=$conexion->query($sql);

        if(mysqli_num_rows($resultado) >0){
            while($fila=mysqli_fetch_assoc($resultado)){
                $idre=$fila['id_posibles_respuestas'];
                $tex=$fila['texto_respuesta'];
                $salida.="<br><input type='radio' name='opciones_".$id_pregunta."' value='".$idre."' required>".$tex;  
            }
            $salida .="<br><br>";
        }
        return $salida;
    }
}