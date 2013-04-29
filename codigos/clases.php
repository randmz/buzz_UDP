<?php
///////////////////////////////////////
//                                   //
//    Código por Hugo Bórquez R.     //
//    pugopugo@hotmail.com           //
//                                   //
///////////////////////////////////////

//clase para el objeto usuario
class Usuario{
	public $rut;
	public $nombre;
	public $mail;
	public $cargo;
	public $is_admin;
	private $pass;
		
		function Usuario($id, $nivel){
			
			include $nivel.'trash/conn.php';
			if($conecta){
						
				$sql_check = mysql_query("SELECT Nombre, Correo, Rut, Cargo, Is_admin FROM usuarios WHERE Id_usuarios = '".$id."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					$row = mysql_fetch_array($sql_check);
					
					$this->mail = $row['Correo'];
					$this->nombre = $row['Nombre'];
					$this->rut = $row['Rut'];
					$this->cargo = $row['Cargo'];
					$this->is_admin = $row['Is_admin'];
				
				}else{
					//no hace nada 
					$this->mail = 'No hay';
					$this->nombre = 'No hay';
					$this->rut = 'No hay';
					$this->cargo = 'No hay';
					$this->is_admin = 0;
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
		} //fin constructor
		
		//método para el login
		function login($mail, $pass){
						
			include '../trash/conn.php';
			if($conecta){
						
				$sql_check = mysql_query("SELECT Id_usuarios FROM usuarios WHERE Correo = '".$mail."' AND Password = '".$pass."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$this->mail = $mail;
					$this->pass = $pass;
					
					$row = mysql_fetch_array($sql_check);
					
					//guardando sesión
					$_SESSION['id'] = $row['Id_usuarios'];
					$_SESSION['mail'] = $mail;
					$_SESSION['pass'] = $pass;
					header("Location: ../admin/cpanel.php"); //redirección
					
				}else{
					//error de login
					header("Location: ../error.php?tipo=login"); 
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
		} //fin login
		
		//método para el logout
		function logout(){
		
			//guardando sesión
			$_SESSION['mail'] = null;
			$_SESSION['pass'] = null;
			$_SESSION['id'] = null;
			header("Location: ../admin/index.php"); //redirección
			
		} //fin logout
		
		//método para agregar un usuario
		function agregar($nombre, $rut, $cargo, $mail, $pass){
						
			include '../trash/conn.php';
			if($conecta){
						
				mysql_query("INSERT INTO usuarios (Nombre, Rut, Cargo, Correo, Password) VALUES('".$nombre."', '".$rut."', '".$cargo."', '".$mail."', '".$pass."' ) ") or die(mysql_error());  
				
				header("Location: ../admin/cpusuarios.php"); //redirección
				
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
		} //fin agregar usuario
		
		function eliminar($id){
						
			include '../trash/conn.php';
			if($conecta){
						
				mysql_query("DELETE FROM usuarios WHERE Id_usuarios = '".$id."'") or die(mysql_error());  
				
				header("Location: ../admin/cpusuarios.php"); //redirección
				
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
		} //fin eliminar usuario
		
		//función para cuando no aprueba una publicación
		function no_aprobar($seccion, $id_seccion, $id){
						
			include '../trash/conn.php';
			if($conecta){
						
				mysql_query("DELETE FROM ".$seccion." WHERE ".$id_seccion." = '".$id."'") or die(mysql_error());  
				
				header("Location: ../admin/cpaprobar.php"); //redirección
				
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
		} //fin no aprobar
		
		//función para cuando no aprueba una publicación
		function aprobar($seccion, $id_seccion, $id, $destacada, $fecha_envio){
						
			include '../trash/conn.php';
			if($conecta){
				
				$aprobada = 1;
				$fecha_publicacion = $fecha_envio;
				//ver el mes y año
				list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
				
				//revisando si es destacada para sacar alguna antigua que haya
				if($destacada == '-'){
					$destacada = null;
				}else if($destacada == 'no'){
					$destacada = 0;
				}else if($destacada == 0){
					$destacada = 0;
				}else{
					$destacada = 1;
					mysql_query("UPDATE comunidad SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
					mysql_query("UPDATE actividades SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
					mysql_query("UPDATE opina SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
				}
				
				mysql_query("UPDATE ".$seccion." SET Destacada = '".$destacada."', Aprobada = 1, Fecha_publicacion = '".$fecha_publicacion."', Dia = '".$dia."', Mes = '".$mes."', Anio = '".$anio."' WHERE ".$id_seccion." = '".$id."'");
				
				header("Location: ../admin/cpaprobar.php"); //redirección
				
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
		} //fin no aprobar
		
		//función para recuperar todos los usuarios
		function get_usuarios(){
			include '../trash/conn.php';
			if($conecta){
						
				$sql_check = mysql_query("SELECT Id_usuarios, Nombre, Correo, Rut, Cargo FROM usuarios WHERE Is_admin = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					$valor[$i] = array($row['Id_usuarios'], $row['Nombre'], $row['Rut'], $row['Cargo'], $row['Correo']);
					}
				
				}else{
					$valor = 0;
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
			return $valor;
		} // fin get todos los usuarios
		
		//función para recuperar todos los usuarios
		function get_aprobar(){
			include '../trash/conn.php';
			
			$num1 = 0;
			
			if($conecta){
				
				//revisando en actividades
				$sql_check = mysql_query("SELECT Id_actividades, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_envio FROM actividades WHERE Aprobada = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					
					if($row['Destacada'] == 1){
						$row['Destacada'] == 's&iacute;';
					}else{
						$row['Destacada'] == 'no';
					}
					
					$valor[$i+$num1] = array('Actividades', $row['Id_actividades'], $row['Destacada'], $row['Titulo'], $row['Resumen'], $row['Cuerpo'], '-', '../imgs/actividades/'.$row['Id_actividades'].'/'.$row['Foto_principal'], 'http://blip.tv/play/'.$row['Video'], $row['Fecha_envio'], '1', $row['Id_usuario']);
					}
				
				$num1+= mysql_num_rows($sql_check);
				
				}else{
					//$valor = 0;
				}
				
				//revisando en comunidad
				$sql_check = mysql_query("SELECT Id_comunidad, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_envio FROM comunidad WHERE Aprobada = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					
					if($row['Destacada'] == 1){
						$row['Destacada'] == 's&iacute;';
					}else{
						$row['Destacada'] == 'no';
					}
					
					$valor[$i+$num1] = array('Comunidad', $row['Id_comunidad'], $row['Destacada'], $row['Titulo'], $row['Resumen'], $row['Cuerpo'], '-', '../imgs/comunidad/'.$row['Id_comunidad'].'/'.$row['Foto_principal'], 'http://blip.tv/play/'.$row['Video'], $row['Fecha_envio'], '2', $row['Id_usuario']);
					}
				
				$num1+= mysql_num_rows($sql_check);
				
				}else{
					//$valor = 0;
				}
				
				//revisando en cosecha
				$sql_check = mysql_query("SELECT Id_cosecha, Id_usuario, Titulo, Resumen, Cuerpo, Descripcion, Foto_principal, Video, Link, Tipo_cosecha, Fecha_envio FROM cosecha WHERE Aprobada = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					
					// colocando la sección
					$seccion = '';
					if($row['Tipo_cosecha'] == 1){
						$seccion = 'Cosecha (Gr&aacute;ficas)';
					}
					if($row['Tipo_cosecha'] == 2){
						$seccion = 'Cosecha (Spots & Videos)';
					}
					if($row['Tipo_cosecha'] == 3){
						$seccion = 'Cosecha (Boards)';
					}
					if($row['Tipo_cosecha'] == 4){
						$seccion = 'Cosecha (Proyectos de t&iacute;tulo)';
					}
					
					//definiendo posibles null
					if($row['Cuerpo'] == null){
						$row['Cuerpo'] = '-';
					}
					if($row['Descripcion'] == null){
						$row['Descripcion'] = '-';
					}
					if($row['Resumen'] == null){
						$row['Resumen'] = '-';
					}
					if(($row['Video'] == null)&&($row['Link'] == null)){
						$video_link = '-';
					}
					
					//viendo si es video o link
					if($row['Video'] != null){
						$video_link = 'http://blip.tv/play/'.$row['Video'];
					}else{
						$video_link = $row['Link'];
					}
					
					$valor[$i+$num1] = array($seccion, $row['Id_cosecha'], '-', $row['Titulo'], $row['Resumen'], $row['Cuerpo'], $row['Descripcion'], '../imgs/cosecha/'.$row['Id_cosecha'].'/'.$row['Foto_principal'], $video_link, $row['Fecha_envio'], '3', $row['Id_usuario']);
					}
				
				$num1+= mysql_num_rows($sql_check);
				
				}else{
					//$valor = 0;
				}
				
				//revisando en noticias
				$sql_check = mysql_query("SELECT Id_noticias, Id_usuario, Titulo, Resumen, Cuerpo, Foto_principal, Fecha_envio FROM noticias WHERE Aprobada = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					$valor[$i+$num1] = array('Noticias', $row['Id_noticias'], '-', $row['Titulo'], $row['Resumen'], $row['Cuerpo'], '-', '../imgs/noticias/'.$row['Id_noticias'].'/'.$row['Foto_principal'], '-', $row['Fecha_envio'], '4', $row['Id_usuario']);
					}
				
				$num1+= mysql_num_rows($sql_check);
				
				}else{
					//$valor = 0;
				}
				
				//revisando en opina
				$sql_check = mysql_query("SELECT Id_opina, Id_usuario, Titulo, Cuerpo, Destacada, Foto_principal, Video, Fecha_envio FROM opina WHERE Aprobada = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					
					if($row['Destacada'] == 1){
						$row['Destacada'] == 's&iacute;';
					}else{
						$row['Destacada'] == 'no';
					}
					
					$valor[$i+$num1] = array('Opina', $row['Id_opina'], $row['Destacada'], $row['Titulo'], '-', $row['Cuerpo'], '-', '../imgs/opina/'.$row['Id_opina'].'/'.$row['Foto_principal'], 'http://blip.tv/play/'.$row['Video'], $row['Fecha_envio'], '5', $row['Id_usuario']);
					}
				
				$num1+= mysql_num_rows($sql_check);
				
				}else{
					//$valor = 0;
				}
				
				//revisando en recursos
				$sql_check = mysql_query("SELECT Id_recursos, Id_usuario, Titulo, Cuerpo, Link, Foto_principal, Tipo, Fecha_envio FROM recursos WHERE Aprobada = 0");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					
					//viendo el nombre de la sección según el tipo
					$seccion = '';
					if($row['Tipo'] == 1){
						$seccion = 'Recursos (Estudios)';
					}
					if($row['Tipo'] == 2){
						$seccion = 'Recursos (Videos)';
					}
					if($row['Tipo'] == 3){
						$seccion = 'Recursos (Links)';
					}
					
					$valor[$i+$num1] = array($seccion, $row['Id_recursos'], '-', $row['Titulo'], '-', $row['Cuerpo'], '-', '../imgs/recursos/'.$row['Id_recursos'].'/'.$row['Foto_principal'], $row['Link'], $row['Fecha_envio'], '6', $row['Id_usuario']);
					}
				
				$num1+= mysql_num_rows($sql_check);
				
				}else{
					//$valor = 0;
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
			return $valor;
		} // fin get aprobar
		
		//función para recuperar el nombre de las imágenes de la bd
		function get_imagenes($id_noticia, $seccion){
			include '../trash/conn.php';
			if($conecta){
				$nombre_galeria = $seccion.'_galeria';
				$id = 'Id_'.$seccion;
				$sql_check = mysql_query("SELECT Nombre_imagen_video FROM ".$nombre_galeria." WHERE ".$id." = '".$id_noticia."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					$valor[$i] = $row['Nombre_imagen_video'];
					}
				
				}else{
					$valor = 0;
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
			return $valor;
		} //fin get imagenes
		
		function get_total_seccion($seccion, $id_seccion, $id_actual){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM ".$seccion." WHERE Aprobada = 1 AND ".$id_seccion." != '".$id_actual."'");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} // fin total de la sección
		
		function get_total_cosecha($tipo, $id_actual){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM cosecha WHERE Aprobada = 1 AND Id_cosecha != '".$id_actual."' AND Tipo_cosecha = '".$tipo."'");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} // fin total de la sección
		
		//función para sacar el rightbar
		function rightbar($pag, $registros, $seccion, $id_seccion, $id_actual, $tipo){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				
				//comienzo
				$start = (($pag - 1) * $registros);
				
				if($seccion == 'noticias'){
				$link = 'noticiasinterno2.php';
				}
				if($seccion == 'actividades'){
				$link = 'actividadesinterno.php';
				}
				if($seccion == 'comunidad'){
				$link = 'comunidadinterno.php';
				}
				if($seccion == 'cosecha'){
				$link = 'cosechainterno.php';
				}
				if($seccion == 'opina'){
				$link = 'opinainterno.php';
				}
				
				//revisando si existe
				if($seccion != 'cosecha'){
					$sql_check = mysql_query("SELECT ".$id_seccion." AS Id, Id_usuario, Titulo, Foto_principal, Fecha_publicacion FROM ".$seccion." WHERE Aprobada = 1 AND ".$id_seccion." != '".$id_actual."' ORDER BY (Dia + Mes*30 + Anio*365) DESC, ".$id_seccion." DESC LIMIT ".$start.",".$registros."");
					
					if(mysql_num_rows($sql_check)){
					
						for($i = 0; $i < mysql_num_rows($sql_check); $i++){
								
							$row = mysql_fetch_array($sql_check);
									
							$valor[$i] = array($row['Id'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion'], $link);
							
						}
					}else{
						//$valor[0] = array(0, 0, 0, 0);
					$valor = 0;
					}
				}else{
					$sql_check = mysql_query("SELECT Id_cosecha AS Id, Id_usuario, Titulo, Foto_principal, Fecha_publicacion, Tipo_cosecha FROM cosecha WHERE Aprobada = 1 AND Id_cosecha != '".$id_actual."' AND Tipo_cosecha = '".$tipo."' ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_cosecha DESC LIMIT ".$start.",".$registros."");
					
					if(mysql_num_rows($sql_check)){
					
						for($i = 0; $i < mysql_num_rows($sql_check); $i++){
								
							$row = mysql_fetch_array($sql_check);
									
							$valor[$i] = array($row['Id'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion'], $link, $row['Tipo_cosecha']);
							
						}
					}else{
						//$valor[0] = array(0, 0, 0, 0);
					$valor = 0;
					}
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin rightbar
	
} //fin clase usuarios

//#####################################################################################
//#####################################################################################

//clase para las noticias
class Noticia{
	public $destacada;
	public $titulo;
	public $resumen;
	public $cuerpo;
	public $id_noticia;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	
		function Noticia($id_noticia){
			//sacar los datos de una noticia a partir de su id
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Fecha_publicacion FROM noticias WHERE Id_noticias = '".$id_noticia."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin constructor
		
		//método para agregar una noticia
		function agregar($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario){
						
					
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO noticias (Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$resumen."', '".$cuerpo."', '".$destacada."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_noticias) AS ultima_noticia from noticias") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id_noticia = $row['ultima_noticia'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/noticias")){
					mkdir("../imgs/noticias");
					}
				
				mkdir("../imgs/noticias/".$id_noticia);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id_noticia;
		} //fin agregar noticia
		
		//método para agregar la o las imágenes
		function agregar_imagen($id_noticia, $filename, $contador){
			include '../trash/conn.php';
			if($conecta){
				
				mysql_query("INSERT INTO noticias_galeria (id_noticias, Nombre_imagen_video) VALUES ('".$id_noticia."', '".$filename."')");
				
				if($contador == 1){
				mysql_query("UPDATE noticias SET Foto_principal = '".$filename."' WHERE Id_noticias = '".$id_noticia."'");
				}
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		//función para sacar las noticias
		function get_noticias($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_noticias, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Fecha_publicacion FROM noticias WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_noticias DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_noticia = $row['Id_noticias'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_noticia = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get noticias
		
		//función para recuperar el nombre de las imágenes de la bd
		function get_imagenes($id_noticia){
			include 'trash/conn.php';
			if($conecta){
						
				$sql_check = mysql_query("SELECT Nombre_imagen_video FROM noticias_galeria WHERE Id_noticias = '".$id_noticia."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					$valor[$i] = $row['Nombre_imagen_video'];
					}
				
				}else{
					$valor = 0;
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
			return $valor;
		} //fin get imagenes
		
		function get_total(){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM noticias WHERE Aprobada = 1");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor-5;
		} // fin total noticias
		
		//para el archivo
		function get_archivo($pag, $registros){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				
				//comienzo
				$start = (($pag - 1) * $registros)+5;
				
				//contador para ver si cumplió las metas
				$contador = 0;
				
				//revisando si existe
				$sql_check = mysql_query("SELECT Id_noticias, Id_usuario, Titulo, Foto_principal, Fecha_publicacion FROM noticias WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_noticias DESC LIMIT ".$start.",".$registros."");
				
				if(mysql_num_rows($sql_check)){
				
					for($i = 0; $i < mysql_num_rows($sql_check); $i++){
							
						$row = mysql_fetch_array($sql_check);
												
						$valor[$i] = array($row['Id_noticias'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion']);
						
					}
				}else{
					//$valor[0] = array(0, 0, 0, 0);
				//$valor = 0;
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin get archivo noticia
		
} //fin clase noticia

//#####################################################################################
//#####################################################################################

//clase para la comunidad
class Comunidad{
	public $destacada;
	public $titulo;
	public $resumen;
	public $cuerpo;
	public $id_comunidad;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	public $video;
	
		function Comunidad($id_comunidad){
			//sacar los datos de una noticia a partir de su id
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM comunidad WHERE Id_comunidad = '".$id_comunidad."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->video = '';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin constructor
		
		//método para agregar una noticia
		function agregar($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario){
						
					
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
					
					//revisando si es destacada para sacar alguna antigua que haya
					if($destacada == 1){
						mysql_query("UPDATE comunidad SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
						mysql_query("UPDATE actividades SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
						mysql_query("UPDATE opina SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
					}
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO comunidad (Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$resumen."', '".$cuerpo."', '".$destacada."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_comunidad) AS ultima from comunidad") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id = $row['ultima'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/comunidad")){
					mkdir("../imgs/comunidad");
					}
				
				mkdir("../imgs/comunidad/".$id);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id;
		} //fin agregar comunidad
		
		//método para agregar la o las imágenes
		function agregar_imagen($id_comunidad, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE comunidad SET Foto_principal = '".$filename."' WHERE Id_comunidad = '".$id_comunidad."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		function agregar_video($id_comunidad, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE comunidad SET Video = '".$filename."' WHERE Id_comunidad = '".$id_comunidad."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar video
		
		//función para sacar las comunidades
		function get_comunidades($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_comunidad, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM comunidad WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_comunidad DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_comunidad = $row['Id_comunidad'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_comunidad = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->video = '5392134';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get comunidades
				
		function get_total(){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM comunidad WHERE Aprobada = 1");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor-5;
		} // fin total noticias
		
		//para el archivo
		function get_archivo($pag, $registros){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				
				//comienzo
				$start = (($pag - 1) * $registros)+5;
				
				//contador para ver si cumplió las metas
				$contador = 0;
				
				//revisando si existe
				$sql_check = mysql_query("SELECT Id_comunidad, Id_usuario, Titulo, Foto_principal, Fecha_publicacion FROM comunidad WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_comunidad DESC LIMIT ".$start.",".$registros."");
				
				if(mysql_num_rows($sql_check)){
				
					for($i = 0; $i < mysql_num_rows($sql_check); $i++){
							
						$row = mysql_fetch_array($sql_check);
												
						$valor[$i] = array($row['Id_comunidad'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion']);
						
					}
				}else{
					//$valor[0] = array(0, 0, 0, 0);
				//$valor = 0;
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin get archivo comunidades
}

//#####################################################################################
//#####################################################################################

//clase para las actividades
class Actividades{
	public $destacada;
	public $titulo;
	public $resumen;
	public $cuerpo;
	public $id_actividades;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	public $video;
	
		function Actividades($id_actividades){
			//sacar los datos de una noticia a partir de su id
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM actividades WHERE Id_actividades = '".$id_actividades."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->video = '';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin constructor
		
		//método para agregar una noticia
		function agregar($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario){
						
					
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
					
					//revisando si es destacada para sacar alguna antigua que haya
					if($destacada == 1){
						mysql_query("UPDATE comunidad SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
						mysql_query("UPDATE actividades SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
						mysql_query("UPDATE opina SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
					}
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO actividades (Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$resumen."', '".$cuerpo."', '".$destacada."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_actividades) AS ultima from actividades") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id = $row['ultima'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/actividades")){
					mkdir("../imgs/actividades");
					}
				
				mkdir("../imgs/actividades/".$id);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id;
		} //fin agregar comunidad
		
		//método para agregar la o las imágenes
		function agregar_imagen($id_actividades, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE actividades SET Foto_principal = '".$filename."' WHERE Id_actividades = '".$id_actividades."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		function agregar_video($id_actividades, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE actividades SET Video = '".$filename."' WHERE Id_actividades = '".$id_actividades."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar video
		
		//función para sacar las comunidades
		function get_actividades($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_actividades, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM actividades WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_actividades DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_actividades = $row['Id_actividades'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_actividades = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->video = '5392134';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get actividades
				
		function get_total(){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM actividades WHERE Aprobada = 1");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor-5;
		} // fin total noticias
		
		//para el archivo
		function get_archivo($pag, $registros){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				
				//comienzo
				$start = (($pag - 1) * $registros)+5;
				
				//contador para ver si cumplió las metas
				$contador = 0;
				
				//revisando si existe
				$sql_check = mysql_query("SELECT Id_actividades, Id_usuario, Titulo, Foto_principal, Fecha_publicacion FROM actividades WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_actividades DESC LIMIT ".$start.",".$registros."");
				
				if(mysql_num_rows($sql_check)){
				
					for($i = 0; $i < mysql_num_rows($sql_check); $i++){
							
						$row = mysql_fetch_array($sql_check);
												
						$valor[$i] = array($row['Id_actividades'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion']);
						
					}
				}else{
					//$valor[0] = array(0, 0, 0, 0);
				//$valor = 0;
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin get archivo actividades
}

//#####################################################################################
//#####################################################################################

//clase para opina
class Opina{
	public $destacada;
	public $titulo;
	public $cuerpo;
	public $id_opina;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	public $video;
	
		function Opina($id_opina){
			//sacar los datos de una noticia a partir de su id
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_usuario, Titulo, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM opina WHERE Id_opina = '".$id_opina."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->video = '';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin constructor
		
		//método para agregar una noticia
		function agregar($destacada, $titulo, $cuerpo, $fecha_envio, $is_admin, $id_usuario){
						
					
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
					
					//revisando si es destacada para sacar alguna antigua que haya
					if($destacada == 1){
						mysql_query("UPDATE comunidad SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
						mysql_query("UPDATE actividades SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
						mysql_query("UPDATE opina SET Destacada = 0 WHERE Destacada = 1 AND Aprobada = 1");
					}
					
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO opina (Id_usuario, Titulo, Cuerpo, Destacada, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$cuerpo."', '".$destacada."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_opina) AS ultima from opina") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id = $row['ultima'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/opina")){
					mkdir("../imgs/opina");
					}
				
				mkdir("../imgs/opina/".$id);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id;
		} //fin agregar comunidad
		
		//método para agregar la o las imágenes
		function agregar_imagen($id_opina, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE opina SET Foto_principal = '".$filename."' WHERE Id_opina = '".$id_opina."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		function agregar_video($id_opina, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE opina SET Video = '".$filename."' WHERE Id_opina = '".$id_opina."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar video
		
		//función para sacar las comunidades
		function get_opina($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_opina, Id_usuario, Titulo, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM opina WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_opina DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_opina = $row['Id_opina'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_opina = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->cuerpo = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->video = '5392134';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get opina
		
		function get_total(){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM opina WHERE Aprobada = 1");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor-5;
		} // fin total noticias
		
		//para el archivo
		function get_archivo($pag, $registros){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				
				//comienzo
				$start = (($pag - 1) * $registros)+5;
				
				//contador para ver si cumplió las metas
				$contador = 0;
				
				//revisando si existe
				$sql_check = mysql_query("SELECT Id_opina, Id_usuario, Titulo, Foto_principal, Fecha_publicacion FROM opina WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_opina DESC LIMIT ".$start.",".$registros."");
				
				if(mysql_num_rows($sql_check)){
				
					for($i = 0; $i < mysql_num_rows($sql_check); $i++){
							
						$row = mysql_fetch_array($sql_check);
												
						$valor[$i] = array($row['Id_opina'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion']);
						
					}
				}else{
					//$valor[0] = array(0, 0, 0, 0);
				//$valor = 0;
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin get archivo opina
}

//#####################################################################################
//#####################################################################################

//clase para recursos
class Recursos{
	public $link;
	public $tipo;
	public $titulo;
	public $cuerpo;
	public $id_recursos;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	
		function Recursos($id_recursos){
			//sacar los datos de una noticia a partir de su id
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_usuario, Titulo, Cuerpo, Link, Tipo, Foto_principal, Fecha_publicacion FROM recursos WHERE Id_recursos = '".$id_recursos."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->cuerpo = $row['Cuerpo'];
					$this->link = $row['Link'];
					$this->imagen = $row['Foto_principal'];
					$this->tipo = $row['Tipo'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->cuerpo = 'No hay';
					$this->link = 0;
					$this->imagen = 'pruebamini.gif';
					$this->tipo = '';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin constructor
		
		//método para agregar recursos
		function agregar($titulo, $cuerpo, $link, $tipo, $fecha_envio, $is_admin, $id_usuario){
						
					
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO recursos (Id_usuario, Titulo, Cuerpo, Link, Tipo, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$cuerpo."', '".$link."', '".$tipo."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_recursos) AS ultima from recursos") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id = $row['ultima'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/recursos")){
					mkdir("../imgs/recursos");
					}
				
				mkdir("../imgs/recursos/".$id);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id;
		} //fin agregar recursos
		
		//método para agregar la o las imágenes
		function agregar_imagen($id_recursos, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE recursos SET Foto_principal = '".$filename."' WHERE Id_recursos = '".$id_recursos."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		//función para sacar las comunidades
		function get_recursos($numero, $tipo){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_recursos, Id_usuario, Titulo, Cuerpo, Link, Foto_principal, Fecha_publicacion FROM recursos WHERE Aprobada = 1 AND Tipo = '".$tipo."' ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_recursos DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_recursos = $row['Id_recursos'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->cuerpo = $row['Cuerpo'];
					$this->link = $row['Link'];
					$this->imagen = $row['Foto_principal'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_recursos = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->cuerpo = 'No hay';
					$this->link = '#';
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get recursos
		
		function get_recursos2($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_recursos, Id_usuario, Titulo, Cuerpo, Link, Foto_principal, Fecha_publicacion FROM recursos WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_recursos DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_recursos = $row['Id_recursos'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->cuerpo = $row['Cuerpo'];
					$this->link = $row['Link'];
					$this->imagen = $row['Foto_principal'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_recursos = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->cuerpo = 'No hay';
					$this->link = '#';
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get recursos2
		
		function get_total($tipo){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM recursos WHERE Aprobada = 1 AND Tipo = '".$tipo."'");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor-5;
		} // fin total noticias
		
		//para el archivo
		function get_archivo($pag, $registros, $tipo){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				
				//comienzo
				$start = (($pag - 1) * $registros)+3;
				
				//contador para ver si cumplió las metas
				$contador = 0;
				
				//revisando si existe
				$sql_check = mysql_query("SELECT Id_recursos, Id_usuario, Titulo, Foto_principal, Fecha_publicacion, Link FROM recursos WHERE Aprobada = 1 AND Tipo = '".$tipo."' ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_recursos DESC LIMIT ".$start.",".$registros."");
				
				if(mysql_num_rows($sql_check)){
				
					for($i = 0; $i < mysql_num_rows($sql_check); $i++){
							
						$row = mysql_fetch_array($sql_check);
												
						$valor[$i] = array($row['Id_recursos'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion'], $row['Link']);
						
					}
				}else{
					//$valor[0] = array(0, 0, 0, 0);
				//$valor = 0;
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin get archivo recursos
}

//#####################################################################################
//#####################################################################################

//clase para las actividades
class Home{
	public $destacada;
	public $titulo;
	public $resumen;
	public $cuerpo;
	public $id;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	public $video;
	public $link;
				
		//función para sacar las comunidades
		function get_destacada($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_comunidad, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM comunidad WHERE Aprobada = 1 AND Destacada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_comunidad DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id = $row['Id_comunidad'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					$this->link = 'comunidadinterno';
					
				}else{
					// sacando si hay destacada en actividades
					$sql_check = mysql_query("SELECT Id_actividades, Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM actividades WHERE Aprobada = 1 AND Destacada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_actividades DESC LIMIT ".$numero.",1");
				
					if(mysql_num_rows($sql_check)){
						//login correcto
						$row = mysql_fetch_array($sql_check);
						
						$this->id = $row['Id_actividades'];
						$this->id_usuario = $row['Id_usuario'];
						$this->titulo = $row['Titulo'];
						$this->resumen = $row['Resumen'];
						$this->cuerpo = $row['Cuerpo'];
						$this->destacada = $row['Destacada'];
						$this->imagen = $row['Foto_principal'];
						$this->video = $row['Video'];
						$this->fecha_publicacion = $row['Fecha_publicacion'];
						$this->link = 'actividadesinterno';
						
					}else{
						// sacando si hay destacada en opina
						$sql_check = mysql_query("SELECT Id_opina, Id_usuario, Titulo, Cuerpo, Destacada, Foto_principal, Video, Fecha_publicacion FROM opina WHERE Aprobada = 1 AND Destacada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_opina DESC LIMIT ".$numero.",1");
					
						if(mysql_num_rows($sql_check)){
							//login correcto
							$row = mysql_fetch_array($sql_check);
							
							$this->id = $row['Id_opina'];
							$this->id_usuario = $row['Id_usuario'];
							$this->titulo = $row['Titulo'];
							$this->resumen = $row['Cuerpo'];
							$this->cuerpo = $row['Cuerpo'];
							$this->destacada = $row['Destacada'];
							$this->imagen = $row['Foto_principal'];
							$this->video = $row['Video'];
							$this->fecha_publicacion = $row['Fecha_publicacion'];
							$this->link = 'opinainterno';
							
						}else{
							$this->id = 0;
							$this->id_usuario = 0;
							$this->titulo = 'No hay';
							$this->resumen = 'No hay';
							$this->cuerpo = 'No hay';
							$this->destacada = 0;
							$this->imagen = 'pruebamini.gif';
							$this->video = '5392134';
							$this->fecha_publicacion = '0/0/0';
						}
					}
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get home
}

//#####################################################################################
//#####################################################################################

//clase para las cosechas
class Cosecha{
	public $destacada;
	public $titulo;
	public $resumen;
	public $cuerpo;
	public $id_cosecha;
	public $id_usuario;
	public $fecha_publicacion;
	public $imagen;
	public $descripcion;
	public $video;
	public $link;
	public $tipo_cosecha;
	
		function Cosecha($id_cosecha){
			//sacar los datos de una noticia a partir de su id
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_usuario, Titulo, Resumen, Cuerpo, Descripcion, Destacada, Foto_principal, Video, Link, Tipo_cosecha, Fecha_publicacion FROM cosecha WHERE Id_cosecha = '".$id_cosecha."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->descripcion = $row['Descripcion'];
					$this->video = $row['Video'];
					$this->link = $row['Link'];
					$this->tipo_cosecha = $row['Tipo_cosecha'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->descripcion = 'No hay';
					$this->video = 'No hay';
					$this->link = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin constructor
		
		//método para agregar una gráfica o board
		function agregar_grafica_boards($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario, $tipo_cosecha){
						
					
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO cosecha (Id_usuario, Titulo, Resumen, Cuerpo, Destacada, Tipo_cosecha, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$resumen."', '".$cuerpo."', '".$destacada."', '".$tipo_cosecha."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_cosecha) AS ultima from cosecha") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id_cosecha = $row['ultima'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/cosecha")){
					mkdir("../imgs/cosecha");
					}
				
				mkdir("../imgs/cosecha/".$id_cosecha);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id_cosecha;
		} //fin agregar cosecha
		
		//método para agregar link
		function agregar_link($titulo, $cuerpo, $link, $fecha_envio, $is_admin, $id_usuario, $tipo_cosecha){
						
			include '../trash/conn.php';
			if($conecta){
				
				if($is_admin == 1){
					$aprobada = 1;
					$fecha_publicacion = $fecha_envio;
					//ver el mes y año
					list($dia, $mes, $anio) = explode('/', $fecha_publicacion);
				}else{
					$aprobada = 0;
					$fecha_publicacion = 0;
					$dia = 0;
					$mes = 0;
					$anio = 0;
				}
				
				mysql_query("INSERT INTO cosecha (Id_usuario, Titulo, Descripcion, Link, Tipo_cosecha, Fecha_envio, Fecha_publicacion, Dia, Mes, Anio, Aprobada) VALUES('".$id_usuario."', '".$titulo."', '".$cuerpo."', '".$link."', '".$tipo_cosecha."', '".$fecha_envio."', '".$fecha_publicacion."', '".$dia."', '".$mes."', '".$anio."', '".$aprobada."' ) ") or die(mysql_error());  
				
				//sacar el id
				$sql_check = mysql_query("select MAX(Id_cosecha) AS ultima from cosecha") or die(mysql_error()); 
				$row = mysql_fetch_array($sql_check);
				$id = $row['ultima'];
				
				//crear directorio de las imágenes o video de la noticia
				if(!is_dir("../imgs/cosecha")){
					mkdir("../imgs/cosecha");
					}
				
				mkdir("../imgs/cosecha/".$id);
				
				//header("Location: ../admin/cpnoticias.php"); //redirección
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}			
			
			return $id;
		} //fin agregar link
		
		//método para agregar la o las imágenes
		function agregar_imagen($id_cosecha, $filename, $contador){
			include '../trash/conn.php';
			if($conecta){
				
				mysql_query("INSERT INTO cosecha_galeria (id_cosecha, Nombre_imagen_video) VALUES ('".$id_cosecha."', '".$filename."')");
				
				if($contador == 1){
				mysql_query("UPDATE cosecha SET Foto_principal = '".$filename."' WHERE Id_cosecha = '".$id_cosecha."'");
				}
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		function agregar_imagen_unica($id_cosecha, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE cosecha SET Foto_principal = '".$filename."' WHERE Id_cosecha = '".$id_cosecha."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar imagen
		
		function agregar_video($id_cosecha, $filename){
			include '../trash/conn.php';
			if($conecta){
				mysql_query("UPDATE cosecha SET Video = '".$filename."' WHERE Id_cosecha = '".$id_cosecha."'");
				
				mysql_close($conecta);
			}else{
				header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
		}//fin agregar video
		
		//función para sacar las cosechas
		function get_cosecha($numero, $tipo_cosecha){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_cosecha, Id_usuario, Titulo, Resumen, Cuerpo, Descripcion, Destacada, Foto_principal, Video, Link, Fecha_publicacion FROM cosecha WHERE Aprobada = 1 AND Tipo_cosecha = '".$tipo_cosecha."' ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_cosecha DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_cosecha = $row['Id_cosecha'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->resumen = $row['Resumen'];
					$this->cuerpo = $row['Cuerpo'];
					$this->descripcion = $row['Descripcion'];
					$this->destacada = $row['Destacada'];
					$this->imagen = $row['Foto_principal'];
					$this->video = $row['Video'];
					$this->link = $row['Link'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_cosecha = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->resumen = 'No hay';
					$this->cuerpo = 'No hay';
					$this->descripcion = 'No hay';
					$this->video = 'No hay';
					$this->link = 'No hay';
					$this->destacada = 0;
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get cosecha
		
		function get_cosecha2($numero){
			include 'trash/conn.php';
			if($conecta){
				
				$sql_check = mysql_query("SELECT Id_cosecha, Id_usuario, Titulo, Tipo_cosecha, Foto_principal, Fecha_publicacion FROM cosecha WHERE Aprobada = 1 ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_cosecha DESC LIMIT ".$numero.",1");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					$row = mysql_fetch_array($sql_check);
					
					$this->id_cosecha = $row['Id_cosecha'];
					$this->id_usuario = $row['Id_usuario'];
					$this->titulo = $row['Titulo'];
					$this->tipo_cosecha = $row['Tipo_cosecha'];
					$this->imagen = $row['Foto_principal'];
					$this->fecha_publicacion = $row['Fecha_publicacion'];
					
				}else{
					$this->id_cosecha = 0;
					$this->id_usuario = 0;
					$this->titulo = 'No hay';
					$this->cuerpo = 'No hay';
					$this->link = '#';
					$this->imagen = 'pruebamini.gif';
					$this->fecha_publicacion = '0/0/0';
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
				header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
			}
		} //fin get cosecha2
		
		//función para recuperar el nombre de las imágenes de la bd
		function get_imagenes($id_cosecha){
			include 'trash/conn.php';
			if($conecta){
						
				$sql_check = mysql_query("SELECT Nombre_imagen_video FROM cosecha_galeria WHERE Id_cosecha = '".$id_cosecha."'");
				
				if(mysql_num_rows($sql_check)){
					//login correcto
					
					for($i = 0 ; $i < mysql_num_rows($sql_check) ; $i++){
					$row = mysql_fetch_array($sql_check);
					$valor[$i] = $row['Nombre_imagen_video'];
					}
				
				}else{
					$valor = 0;
				}
				
				mysql_free_result($sql_check);
				mysql_close($conecta);
			}else{
			header("Location: ../error.php?tipo=bd"); //por si no conecta, manda error.
			}
			
			return $valor;
		} //fin get imagenes
				
		function get_total($tipo){
			//incluyendo conexión
			include 'trash/conn.php';
				
			if($conecta){
				session_start();
				
				//revisando si existe la ficha, si no, crearla.
				$sql_check = mysql_query("SELECT count(*) FROM cosecha WHERE Aprobada = 1 AND Tipo_cosecha = '".$tipo."'");
				
				$row = mysql_fetch_array($sql_check);
				$valor = $row[0]; 

			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor-5;
		} // fin total cosecha
		
		//para el archivo
		function get_archivo($pag, $registros, $tipo){
			//incluyendo conexión
			include 'trash/conn.php';
			
			if($conecta){
				session_start();
				
				//comienzo
				$start = (($pag - 1) * $registros)+3;
				
				//contador para ver si cumplió las metas
				$contador = 0;
				
				//revisando si existe
				$sql_check = mysql_query("SELECT Id_cosecha, Id_usuario, Titulo, Foto_principal, Fecha_publicacion, Link FROM cosecha WHERE Aprobada = 1 AND Tipo_cosecha = '".$tipo."' ORDER BY (Dia + Mes*30 + Anio*365) DESC, Id_cosecha DESC LIMIT ".$start.",".$registros."");
				
				if(mysql_num_rows($sql_check)){
				
					for($i = 0; $i < mysql_num_rows($sql_check); $i++){
							
						$row = mysql_fetch_array($sql_check);
												
						$valor[$i] = array($row['Id_cosecha'], $row['Id_usuario'], $row['Titulo'], $row['Foto_principal'], $row['Fecha_publicacion'], $row['Link']);
						
					}
				}else{
					//$valor[0] = array(0, 0, 0, 0);
				//$valor = 0;
				}

				
			}else{
					header("Location: error.php?tipo=bd"); //por si no conecta, manda error.
					exit(1);
					}
					
			mysql_free_result($sql_check);
			mysql_close($conecta);
			
			return $valor;
		} //fin get archivo cosecha
		
} //fin clase noticia
