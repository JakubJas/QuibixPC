<?php
require 'basedatosconexion.php';

$conn = connection::dbConnection();

//GET USAURIOS DE LA BD DE USUARIO.

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $sql = "SELECT * FROM usuario";
    $resultado = $conn->query($sql);

    if($resultado->num_rows > 0){
        $usuarios = array();

        while($columna = $resultado->fetch_assoc()){
            $usuarios[] = $columna;
        }

        echo json_encode($usuarios);
    }else{
        echo json_encode(array('mensaje'=>'No se encontraron usuarios'));
    }
}