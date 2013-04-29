<?php
session_start();

///////////////////////////////////////
//                                   //
//    Código por Hugo Bórquez R.     //
//    pugopugo@hotmail.com           //
//                                   //
///////////////////////////////////////

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);

require 'funciones.php';

//para ver de donde viene
$tipo = $_REQUEST['tipo'];

switch($tipo){

	case 'login':
		$mail = $_POST['login'];
		$pass = $_POST['pass'];
		$mail = cambiar_caracteres($mail);
		$pass = md5($pass);
		//objeto
		$usuario = new Usuario();
		$usuario->login($mail, $pass);
		break;
	
	case 'logout':
		$usuario = new Usuario();
		$usuario->logout();
		break;
	
	case 'agregar_usuario':
		$nombre = cambiar_caracteres($_POST['nombre']);
		$rut = cambiar_caracteres($_POST['rut']);
		$cargo = cambiar_caracteres($_POST['cargo']);
		$mail = cambiar_caracteres($_POST['mail']);
		$pass = md5($_POST['pass']);
		
		$nuevo = new Usuario();
		$nuevo->agregar($nombre, $rut, $cargo, $mail, $pass);
		break;
	
	case 'eliminar':
		$id = $_REQUEST['id'];
		$borrado = new Usuario();
		$borrado->eliminar($id);
		break;
	
	case 'agregar_noticia':
	
		//incluyendo el archivo de funciones para subir imágenes
		include 'fn_subir_imagenes.php';
		
		/*if (isset($_POST['destacada']))
		{
			$destacada = $_POST['destacada'];
		}else{
			$destacada = 0;
		}*/
		$destacada = 0;
		
		//contador para ver cuál es la imagen principal
		$contador = 1;
		
		$titulo = $_POST['titulo'];
		$resumen = $_POST['resumen'];
		$cuerpo = $_POST['cuerpo'];
		$fecha_envio = date('d/m/Y');
		
		$id_usuario = $_SESSION['id'];
		$usuario = new Usuario($id_usuario, '../');
		$is_admin = $usuario->is_admin;
		
		$noticia = new Noticia();
		$id_noticia = $noticia->agregar($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario);
		
		// SUBIR IMAGENES
		if($destacada == 0){
		// Configuration - Your Options
		$allowed_filetypes = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.

		$upload_path = '../imgs/noticias/'.$id_noticia.'/'; // The place the files will be uploaded to (currently a 'files' directory).
		//Preguntamos si nuetro arreglo 'archivos' fue definido
		 if (isset ($_FILES["archivos"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["archivos"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['archivos']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes)){
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['archivos']['tmp_name'][$i],$upload_path . $filename))
					{
						$imagen = $upload_path . $filename;
						
						mkdir('../imgs/noticias/'.$id_noticia.'/thumb/');
						$thumb_name='../imgs/noticias/'.$id_noticia.'/thumb/'.$filename;
						$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
						
						$noticia->agregar_imagen($id_noticia, $filename, $contador);
						
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_imagen");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
		}
		header("Location: ../admin/cpnoticias.php"); //redirección
		break;
	
	case 'agregar_comunidad':
		
		//incluyendo el archivo de funciones para subir imágenes
		include 'fn_subir_imagenes.php';
		include 'blipPHP.php';
		
		if (isset($_POST['destacada']))
		{
			$destacada = $_POST['destacada'];
		}else{
			$destacada = 0;
		}
		
		$titulo = $_POST['titulo'];
		$resumen = $_POST['resumen'];
		$cuerpo = $_POST['cuerpo'];
		$fecha_envio = date('d/m/Y');
		
		$id_usuario = $_SESSION['id'];
		$usuario = new Usuario($id_usuario, '../');
		$is_admin = $usuario->is_admin;
			
		$comunidad = new Comunidad();
		$id_comunidad = $comunidad->agregar($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario);
			
		// SUBIR IMAGENES

		// Configuration - Your Options
		$allowed_filetypes_imagen = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.
		$allowed_filetypes_video = array('.avi','.mpg','.mpeg','.mp4','.mov','.wmv'); // These will be the types of file that will pass the validation.

		$upload_path = '../imgs/comunidad/'.$id_comunidad.'/'; // The place the files will be uploaded to (currently a 'files' directory).
		//Preguntamos si nuetro arreglo 'archivos' fue definido
		if (isset ($_FILES["imagen"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["imagen"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['imagen']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_imagen)){
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i],$upload_path . $filename))
					{
						$imagen = $upload_path . $filename;
						
						mkdir('../imgs/comunidad/'.$id_comunidad.'/thumb/');
						$thumb_name='../imgs/comunidad/'.$id_comunidad.'/thumb/'.$filename;
						$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
						
						$comunidad->agregar_imagen($id_comunidad, $filename);
						
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_imagen");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
		
		//$comunidad->agregar_imagen($id_comunidad, $filename);
		
		/** Create blipPHP object. **/
		$blipPHP = new blipPHP("BuzzUDP", "adminbuzz");
		
		if (isset ($_FILES["video"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["video"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['video']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename_video = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_video)){
					//$respond = 'paso 3 '.$i;
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['video']['tmp_name'][$i],$upload_path . $filename_video))
					{
						$video = $upload_path . $filename_video;
						

						/** Upload file **/
						$response = $blipPHP->upload($upload_path.$filename_video, $titulo, $resumen);
						
						$rpta = intval($response->payload->asset->item_id);
						$respond = $blipPHP->info($rpta);
						$id_video = $respond->payload->asset->embedLookup;
						
						if($response != null){
						$comunidad->agregar_video($id_comunidad, $id_video);
						}else{
						$comunidad->agregar_video($id_comunidad, 'error');
						}
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_video");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
			
		//$comunidad->agregar_video($id_comunidad, $respond);
			
		header("Location: ../admin/cpcomunidad.php"); //redirección
		break;
	
	case 'agregar_actividades':
		
		//incluyendo el archivo de funciones para subir imágenes
		include 'fn_subir_imagenes.php';
		include 'blipPHP.php';
		
		if (isset($_POST['destacada']))
		{
			$destacada = $_POST['destacada'];
		}else{
			$destacada = 0;
		}
		
		$titulo = $_POST['titulo'];
		$resumen = $_POST['resumen'];
		$cuerpo = $_POST['cuerpo'];
		$fecha_envio = date('d/m/Y');
		
		$id_usuario = $_SESSION['id'];
		$usuario = new Usuario($id_usuario, '../');
		$is_admin = $usuario->is_admin;
			
		$actividades = new Actividades();
		$id_actividades = $actividades->agregar($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario);
			
		// SUBIR IMAGENES

		// Configuration - Your Options
		$allowed_filetypes_imagen = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.
		$allowed_filetypes_video = array('.avi','.mpg','.mpeg','.mp4','.mov','.wmv'); // These will be the types of file that will pass the validation.

		$upload_path = '../imgs/actividades/'.$id_actividades.'/'; // The place the files will be uploaded to (currently a 'files' directory).
		//Preguntamos si nuetro arreglo 'archivos' fue definido
		if (isset ($_FILES["imagen"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["imagen"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['imagen']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_imagen)){
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i],$upload_path . $filename))
					{
						$imagen = $upload_path . $filename;
						
						mkdir('../imgs/actividades/'.$id_actividades.'/thumb/');
						$thumb_name='../imgs/actividades/'.$id_actividades.'/thumb/'.$filename;
						$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
						
						$actividades->agregar_imagen($id_actividades, $filename);
						
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_imagen");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
		
		//$comunidad->agregar_imagen($id_comunidad, $filename);
		
		/** Create blipPHP object. **/
		$blipPHP = new blipPHP("BuzzUDP", "adminbuzz");
		
		if (isset ($_FILES["video"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["video"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['video']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename_video = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_video)){
					//$respond = 'paso 3 '.$i;
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['video']['tmp_name'][$i],$upload_path . $filename_video))
					{
						$video = $upload_path . $filename_video;
						

						/** Upload file **/
						$response = $blipPHP->upload($upload_path.$filename_video, $titulo, $resumen);
						
						$rpta = intval($response->payload->asset->item_id);
						$respond = $blipPHP->info($rpta);
						$id_video = $respond->payload->asset->embedLookup;
						
						if($response != null){
						$actividades->agregar_video($id_actividades, $id_video);
						}else{
						$actividades->agregar_video($id_actividades, 'error');
						}
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_video");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
			
		//$comunidad->agregar_video($id_comunidad, $respond);
			
		header("Location: ../admin/cpactividades.php"); //redirección
		break;
		
	case 'agregar_opina':
		
		//incluyendo el archivo de funciones para subir imágenes
		include 'fn_subir_imagenes.php';
		include 'blipPHP.php';
		
		if (isset($_POST['destacada']))
		{
			$destacada = $_POST['destacada'];
		}else{
			$destacada = 0;
		}
		
		$titulo = $_POST['titulo'];
		$cuerpo = $_POST['cuerpo'];
		$fecha_envio = date('d/m/Y');
		
		$id_usuario = $_SESSION['id'];
		$usuario = new Usuario($id_usuario, '../');
		$is_admin = $usuario->is_admin;
			
		$opina = new Opina();
		$id_opina = $opina->agregar($destacada, $titulo, $cuerpo, $fecha_envio, $is_admin, $id_usuario);
			
		// SUBIR IMAGENES

		// Configuration - Your Options
		$allowed_filetypes_imagen = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.
		$allowed_filetypes_video = array('.avi','.mpg','.mpeg','.mp4','.mov','.wmv'); // These will be the types of file that will pass the validation.

		$upload_path = '../imgs/opina/'.$id_opina.'/'; // The place the files will be uploaded to (currently a 'files' directory).
		//Preguntamos si nuetro arreglo 'archivos' fue definido
		if (isset ($_FILES["imagen"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["imagen"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['imagen']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_imagen)){
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i],$upload_path . $filename))
					{
						$imagen = $upload_path . $filename;
						
						mkdir('../imgs/opina/'.$id_opina.'/thumb/');
						$thumb_name='../imgs/opina/'.$id_opina.'/thumb/'.$filename;
						$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
						
						$opina->agregar_imagen($id_opina, $filename);
						
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_imagen");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
		
		//$comunidad->agregar_imagen($id_comunidad, $filename);
		
		/** Create blipPHP object. **/
		$blipPHP = new blipPHP("BuzzUDP", "adminbuzz");
		
		if (isset ($_FILES["video"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["video"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['video']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename_video = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_video)){
					//$respond = 'paso 3 '.$i;
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['video']['tmp_name'][$i],$upload_path . $filename_video))
					{
						$video = $upload_path . $filename_video;
						

						/** Upload file **/
						$response = $blipPHP->upload($upload_path.$filename_video, $titulo, $resumen);
						
						$rpta = intval($response->payload->asset->item_id);
						$respond = $blipPHP->info($rpta);
						$id_video = $respond->payload->asset->embedLookup;
						
						if($response != null){
						$opina->agregar_video($id_opina, $id_video);
						}else{
						$opina->agregar_video($id_opina, 'error');
						}
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_video");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
			
		//$comunidad->agregar_video($id_comunidad, $respond);
			
		header("Location: ../admin/cpopina.php"); //redirección
		break;
	
	case 'agregar_recursos':
		
		//incluyendo el archivo de funciones para subir imágenes
		include 'fn_subir_imagenes.php';
			
		$titulo = $_POST['titulo'];
		$cuerpo = $_POST['cuerpo'];
		$link = $_POST['link'];
		$tipo_recurso = $_POST['tipo_recurso'];
		$fecha_envio = date('d/m/Y');
		
		$id_usuario = $_SESSION['id'];
		$usuario = new Usuario($id_usuario, '../');
		$is_admin = $usuario->is_admin;
			
		$recursos = new Recursos();
		$id_recursos = $recursos->agregar($titulo, $cuerpo, $link, $tipo_recurso, $fecha_envio, $is_admin, $id_usuario);
			
		// SUBIR IMAGENES

		// Configuration - Your Options
		$allowed_filetypes_imagen = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.
		
		$upload_path = '../imgs/recursos/'.$id_recursos.'/'; // The place the files will be uploaded to (currently a 'files' directory).
		//Preguntamos si nuetro arreglo 'archivos' fue definido
		if (isset ($_FILES["imagen"])) {
			
			//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
			//obtenemos la cantidad de elementos que tiene el arreglo archivos
			$tot = count($_FILES["imagen"]["name"]);
			//este for recorre el arreglo
			for ($i = 0; $i < $tot; $i++){
			//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
			//para trabajar con este
				$name = $_FILES['imagen']['name'][$i];
				
				$name = strtolower($name);
				
				list($fileN, $fileExt) = explode('.', $name);
				$rand = rand(1, 10000000);
				$filename = $fileN . $rand . '.' . $fileExt;
				$ext = '.' . $fileExt;
				
				// Check if the filetype is allowed.
				if(in_array($ext,$allowed_filetypes_imagen)){
					// Check if we can upload to the specified path, if not DIE and inform the user.
					if(!is_writable($upload_path)){
					header("Location: ../error.php?tipo=permisos");
					die();
					}
					// Upload the file to your specified path.
					if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i],$upload_path . $filename))
					{
						$imagen = $upload_path . $filename;
						
						mkdir('../imgs/recursos/'.$id_recursos.'/thumb/');
						$thumb_name='../imgs/recursos/'.$id_recursos.'/thumb/'.$filename;
						$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
						
						$recursos->agregar_imagen($id_recursos, $filename);
						
						//aumentar el contador
						$contador++;
						
						//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
						}else{
						header("Location: ../error.php?tipo=subir_imagen");
						}
						 
					}else{
					header("Location: ../error.php?tipo=extension");
					}
				}
			//header("Location: ../admin/cpnoticias.php"); //redirección
			}else{
			
			}
		
		//$comunidad->agregar_imagen($id_comunidad, $filename);
			
		header("Location: ../admin/cprecursos.php"); //redirección
		break;
	
	case 'agregar_cosecha':
	
		//incluyendo el archivo de funciones para subir imágenes
		include 'fn_subir_imagenes.php';
		include 'blipPHP.php';	
		
		//contador para ver cuál es la imagen principal
		$contador = 1;
		
		//en todas
		$titulo = $_POST['titulo'];
		$fecha_envio = date('d/m/Y');
		$tipo_cosecha = $_POST['tipo_cosecha'];
		$destacada = 0;
		
		$id_usuario = $_SESSION['id'];
		$usuario = new Usuario($id_usuario, '../');
		$is_admin = $usuario->is_admin;
		
		//para gráficas y boards
		if(($tipo_cosecha == 1)||($tipo_cosecha == 3)){
			$resumen = $_POST['resumen'];
			$cuerpo = $_POST['cuerpo'];
			
			$cosecha1 = new Cosecha();
			$id_cosecha = $cosecha1->agregar_grafica_boards($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario, $tipo_cosecha);
			
			// SUBIR IMAGENES
			// Configuration - Your Options
			$allowed_filetypes = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.

			$upload_path = '../imgs/cosecha/'.$id_cosecha.'/'; // The place the files will be uploaded to (currently a 'files' directory).
			//Preguntamos si nuetro arreglo 'archivos' fue definido
			 if (isset ($_FILES["archivos"])) {
				
				//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
				//obtenemos la cantidad de elementos que tiene el arreglo archivos
				$tot = count($_FILES["archivos"]["name"]);
				//este for recorre el arreglo
				for ($i = 0; $i < $tot; $i++){
				//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
				//para trabajar con este
					$name = $_FILES['archivos']['name'][$i];
					
					$name = strtolower($name);
					
					list($fileN, $fileExt) = explode('.', $name);
					$rand = rand(1, 10000000);
					$filename = $fileN . $rand . '.' . $fileExt;
					$ext = '.' . $fileExt;
					
					// Check if the filetype is allowed.
					if(in_array($ext,$allowed_filetypes)){
						// Check if we can upload to the specified path, if not DIE and inform the user.
						if(!is_writable($upload_path)){
						header("Location: ../error.php?tipo=permisos");
						die();
						}
						// Upload the file to your specified path.
						if(move_uploaded_file($_FILES['archivos']['tmp_name'][$i],$upload_path . $filename))
						{
							$imagen = $upload_path . $filename;
							
							mkdir('../imgs/cosecha/'.$id_cosecha.'/thumb/');
							$thumb_name='../imgs/cosecha/'.$id_cosecha.'/thumb/'.$filename;
							$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
							
							$cosecha1->agregar_imagen($id_cosecha, $filename, $contador);
							
							//aumentar el contador
							$contador++;
							
							//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
							}else{
							header("Location: ../error.php?tipo=subir_imagen");
							}
							 
						}else{
						header("Location: ../error.php?tipo=extension");
						}
					}
				//header("Location: ../admin/cpnoticias.php"); //redirección
				}else{
				
				}
		} //fin gráficas y boards
		
		//para spots y videos
		if($tipo_cosecha == 2){

			$resumen = $_POST['resumen'];
			$cuerpo = $_POST['cuerpo'];
						
			$cosecha2 = new Cosecha();
			$id_cosecha = $cosecha2->agregar_grafica_boards($destacada, $titulo, $resumen, $cuerpo, $fecha_envio, $is_admin, $id_usuario, $tipo_cosecha);
				
			// SUBIR IMAGENES

			// Configuration - Your Options
			$allowed_filetypes_imagen = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.
			$allowed_filetypes_video = array('.avi','.mpg','.mpeg','.mp4','.mov','.wmv'); // These will be the types of file that will pass the validation.

			$upload_path = '../imgs/cosecha/'.$id_cosecha.'/'; // The place the files will be uploaded to (currently a 'files' directory).
			//Preguntamos si nuetro arreglo 'archivos' fue definido
			if (isset ($_FILES["imagen"])) {
				
				//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
				//obtenemos la cantidad de elementos que tiene el arreglo archivos
				$tot = count($_FILES["imagen"]["name"]);
				//este for recorre el arreglo
				for ($i = 0; $i < $tot; $i++){
				//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
				//para trabajar con este
					$name = $_FILES['imagen']['name'][$i];
					
					$name = strtolower($name);
					
					list($fileN, $fileExt) = explode('.', $name);
					$rand = rand(1, 10000000);
					$filename = $fileN . $rand . '.' . $fileExt;
					$ext = '.' . $fileExt;
					
					// Check if the filetype is allowed.
					if(in_array($ext,$allowed_filetypes_imagen)){
						// Check if we can upload to the specified path, if not DIE and inform the user.
						if(!is_writable($upload_path)){
						header("Location: ../error.php?tipo=permisos");
						die();
						}
						// Upload the file to your specified path.
						if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i],$upload_path . $filename))
						{
							$imagen = $upload_path . $filename;
							
							mkdir('../imgs/cosecha/'.$id_cosecha.'/thumb/');
							$thumb_name='../imgs/cosecha/'.$id_cosecha.'/thumb/'.$filename;
							$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
							
							$cosecha2->agregar_imagen_unica($id_cosecha, $filename);
							
							//aumentar el contador
							$contador++;
							
							//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
							}else{
							header("Location: ../error.php?tipo=subir_imagen");
							}
							 
						}else{
						header("Location: ../error.php?tipo=extension");
						}
					}
				//header("Location: ../admin/cpnoticias.php"); //redirección
				}else{
				
				}
			
			//$comunidad->agregar_imagen($id_comunidad, $filename);
			
			/** Create blipPHP object. **/
			$blipPHP = new blipPHP("BuzzUDP", "adminbuzz");
			
			if (isset ($_FILES["video"])) {
				
				//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
				//obtenemos la cantidad de elementos que tiene el arreglo archivos
				$tot = count($_FILES["video"]["name"]);
				//este for recorre el arreglo
				for ($i = 0; $i < $tot; $i++){
				//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
				//para trabajar con este
					$name = $_FILES['video']['name'][$i];
					
					$name = strtolower($name);
					
					list($fileN, $fileExt) = explode('.', $name);
					$rand = rand(1, 10000000);
					$filename_video = $fileN . $rand . '.' . $fileExt;
					$ext = '.' . $fileExt;
					
					// Check if the filetype is allowed.
					if(in_array($ext,$allowed_filetypes_video)){
						//$respond = 'paso 3 '.$i;
						// Check if we can upload to the specified path, if not DIE and inform the user.
						if(!is_writable($upload_path)){
						header("Location: ../error.php?tipo=permisos");
						die();
						}
						// Upload the file to your specified path.
						if(move_uploaded_file($_FILES['video']['tmp_name'][$i],$upload_path . $filename_video))
						{
							$video = $upload_path . $filename_video;
							

							/** Upload file **/
							$response = $blipPHP->upload($upload_path.$filename_video, $titulo, $resumen);
							
							$rpta = intval($response->payload->asset->item_id);
							$respond = $blipPHP->info($rpta);
							$id_video = $respond->payload->asset->embedLookup;
							
							if($response != null){
							$cosecha2->agregar_video($id_cosecha, $id_video);
							}else{
							$cosecha2->agregar_video($id_cosecha, 'error');
							}
							//aumentar el contador
							//$contador++;
							
							//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
							}else{
							header("Location: ../error.php?tipo=subir_video");
							}
							 
						}else{
						header("Location: ../error.php?tipo=extension");
						}
					}
				//header("Location: ../admin/cpnoticias.php"); //redirección
				}else{
				
				}
		} //fin spots y videos
		
		//para links
		if($tipo_cosecha == 4){
			$cuerpo = $_POST['descripcion'];
			$link = $_POST['link'];
			$fecha_envio = date('d/m/Y');
				
			$cosecha3 = new Cosecha();
			$id_cosecha = $cosecha3->agregar_link($titulo, $cuerpo, $link, $fecha_envio, $is_admin, $id_usuario, $tipo_cosecha);
				
			// SUBIR IMAGENES

			// Configuration - Your Options
			$allowed_filetypes_imagen = array('.jpg','.gif','.png','.jpeg'); // These will be the types of file that will pass the validation.
			
			$upload_path = '../imgs/cosecha/'.$id_cosecha.'/'; // The place the files will be uploaded to (currently a 'files' directory).
			//Preguntamos si nuetro arreglo 'archivos' fue definido
			if (isset ($_FILES["imagenlink"])) {
				
				//de se asi, para procesar los archivos subidos al servidor solo debemos recorrerlo
				//obtenemos la cantidad de elementos que tiene el arreglo archivos
				$tot = count($_FILES["imagenlink"]["name"]);
				//este for recorre el arreglo
				for ($i = 0; $i < $tot; $i++){
				//con el indice $i, poemos obtener la propiedad que desemos de cada archivo
				//para trabajar con este
					$name = $_FILES['imagenlink']['name'][$i];
					
					$name = strtolower($name);
					
					list($fileN, $fileExt) = explode('.', $name);
					$rand = rand(1, 10000000);
					$filename = $fileN . $rand . '.' . $fileExt;
					$ext = '.' . $fileExt;
					
					// Check if the filetype is allowed.
					if(in_array($ext,$allowed_filetypes_imagen)){
						// Check if we can upload to the specified path, if not DIE and inform the user.
						if(!is_writable($upload_path)){
						header("Location: ../error.php?tipo=permisos");
						die();
						}
						// Upload the file to your specified path.
						if(move_uploaded_file($_FILES['imagenlink']['tmp_name'][$i],$upload_path . $filename))
						{
							$imagen = $upload_path . $filename;
							
							mkdir('../imgs/cosecha/'.$id_cosecha.'/thumb/');
							$thumb_name='../imgs/cosecha/'.$id_cosecha.'/thumb/'.$filename;
							$thumb=make_thumb($imagen,$thumb_name,WIDTH,HEIGHT);
							
							$cosecha3->agregar_imagen_unica($id_cosecha, $filename);
							
							//aumentar el contador
							//$contador++;
							
							//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
							}else{
							header("Location: ../error.php?tipo=subir_imagen");
							}
							 
						}else{
						header("Location: ../error.php?tipo=extension");
						}
					}
				//header("Location: ../admin/cpnoticias.php"); //redirección
				}else{
				
				}
		} // fin links
		
		header("Location: ../admin/cpcosecha.php"); //redirección
		break;
	
	case 'aprobar':
		$rpta = $_REQUEST['rpta'];
		$seccion = $_REQUEST['seccion'];
		$id = $_REQUEST['id'];
		$destacada = $_REQUEST['destacada'];
		$fecha = $_REQUEST['fecha'];
		
		//viendo la sección
		if($seccion == 1){
			$seccion = 'actividades';
			$id_seccion = 'Id_actividades';
		}
		if($seccion == 2){
			$seccion = 'comunidad';
			$id_seccion = 'Id_comunidad';
		}
		if($seccion == 3){
			$seccion = 'cosecha';
			$id_seccion = 'Id_cosecha';
		}
		if($seccion == 4){
			$seccion = 'noticias';
			$id_seccion = 'Id_noticias';
		}
		if($seccion == 5){
			$seccion = 'opina';
			$id_seccion = 'Id_opina';
		}
		if($seccion == 6){
			$seccion = 'recursos';
			$id_seccion = 'Id_recursos';
		}
		
		if($rpta == 'no'){
		$usuario = new Usuario($_SESSION['id'], '../');
		$usuario->no_aprobar($seccion, $id_seccion, $id);
		//eliminar
		}else{
		//aprobar, ver lo de las destacadas tb
		$usuario2 = new Usuario($_SESSION['id'], '../');
		$usuario2->aprobar($seccion, $id_seccion, $id, $destacada, $fecha);
		}
		
		break;
}
