<?php
    
    /*El archivo autoload ahorra las molestias de tener que requerir cada uno de los controladores manualmente, de manera
    que se importará el controlador que se le pase por parametro GET a la url.*/
    require_once 'autoload.php';

    /*Si la super global variable GET controller está definida, entonces almacenará el contenido de la variable GET en
    la variable nombre_controlador*/
    if(isset($_GET['controller'])) {
        
        $nombre_controlador = $_GET['controller'].'Controller';
    
    /*En caso de que no se le asigne un valor, entonces redirigirá a la página de login*/
    }else{
        
        header("Location:?controller=Login&action=mostrarFormulario");
        exit();
    }
    /*Si existe un controlador dado ha sido definido, entonces se crea una instancia del mismo */
    if(class_exists($nombre_controlador)){
   
        $controlador = new $nombre_controlador();
        
        /*Si la super global variable GET action está definida y si lo que tiene almacenado action es el nombre de un método
        del objeto controlador, entonces almacenará el contenido de la variable GET en la variable action y se ejecutará el método action*/
        if(isset($_GET['action'])  && method_exists($controlador,$_GET['action'] )){
            
            $action = $_GET['action'];
            
            $controlador->$action();
        
        /*En caso contrario, entonces redirigirá a la página de login*/
        }else{
            
            header("Location:?controller=Login&action=mostrarFormulario");
            exit();
        
        }
    
    /*En caso de que no ninguna de estas condiciones anterirores se cumpla, entonces redirigirá a la página de login*/
    }else{
        
        header("Location:?controller=Login&action=mostrarFormulario");
        exit();
    
    }
?>