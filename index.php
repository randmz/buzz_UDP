<?php
ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);

include 'menu.php';
include 'footer.php';
include 'header.php';
require 'codigos/clases.php';

//sacando la última destacada
$home1 = new Home();
$home1->get_destacada(0);
$usuario1 = new Usuario($home1->id_usuario,'');

//sacando la última noticia
$noticia1 = new Noticia();
$noticia1->get_noticias(0);
$usuario2 = new Usuario($noticia1->id_usuario,'');

//sacando la última actividad
$actividad1 = new Actividades();
$actividad1->get_actividades(0);
$usuario3 = new Usuario($actividad1->id_usuario,'');

//sacando la última comunidad
$comunidad1 = new Comunidad();
$comunidad1->get_comunidades(0);
$usuario4 = new Usuario($comunidad1->id_usuario,'');

//sacando la última comunidad
$opina1 = new Opina();
$opina1->get_opina(0);
$usuario5 = new Usuario($opina1->id_usuario,'');

//sacando el último recurso
$recursos1 = new Recursos();
$recursos1->get_recursos2(0);
$usuario6 = new Usuario($recursos1->id_usuario,'');

//sacando las cosechas
$cosecha1 = new Cosecha();
$cosecha1->get_cosecha2(0);
$usuario7 = new Usuario($cosecha1->id_usuario,'');

$cosecha2 = new Cosecha();
$cosecha2->get_cosecha2(1);
$usuario8 = new Usuario($cosecha2->id_usuario,'');

$cosecha3 = new Cosecha();
$cosecha3->get_cosecha2(2);
$usuario9 = new Usuario($cosecha3->id_usuario,'');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<script type="text/javascript" src="js/jquery.pngfix.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
		$("img[@src$=png], #Wrap").pngfix();			
		// $.miseAlphaImageLoader("sdsd");
	});
</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/ico" href="favicon.ico"/>
<!--[if IE]>
 <style type="text/css">
  body {word-wrap: break-word;}
 </style>
<![endif]-->

</head>

<body>
<div id="Wrap">
  <div class="separaheader"></div>
  <?php echo $header;?>
  <div id="botonera"><ul class="menu"><?php echo $menu;?></ul></div>
  <div id="Destacanoticiaindex" class="color">
    <div id="videodestacadoindex">
      <iframe src="http://blip.tv/play/<?php echo $home1->video;?>.html" width="720" height="350" frameborder="0" allowfullscreen></iframe><embed type="application/x-shockwave-flash" src="http://a.blip.tv/api.swf#<?php echo $home1->video;?>" style="display:none"></embed>
      </div><div class="left"></div>
   
    <div id="cajatexto1">
      <div class="textotitulo1"><a href="<?php echo $home1->link;?>.php?id=<?php echo $home1->id;?>"><?php echo $home1->titulo;?></a></div></div>
  </div>
  <div class="alpha rojo left margenizq">
    <div class="left"><a href="noticias.php"><img src="imgs/noticias.png" alt="Las últimas noticias de Buzz!" width="170" height="51" border="0" /></a></div><div class="bordederecho1"></div>
    <div class="contieneindex left">
      <div class="fotoancha left"><a href="noticiasinterno2.php?id=<?php echo $noticia1->id_noticia;?>"><img src="<?php echo 'imgs/noticias/'.$noticia1->id_noticia.'/'.$noticia1->imagen;?>" width="330" height="170" border="0" /></a></div>
      <div class="textoindex left"><div class="textotitulomini texto250"><a href="noticiasinterno2.php?id=<?php echo $noticia1->id_noticia;?>"><?php echo $noticia1->titulo;?></a></div><div class="textoexplica"><a href="noticiasinterno2.php?id=<?php echo $noticia1->id_noticia;?>"><?php echo 'Publicado por '.$usuario2->nombre.', el '.$noticia1->fecha_publicacion.'<br /><br />';?><?php echo $noticia1->resumen;?></a></div></div>
    </div>
  </div>
  <div class="beta naranjo left margender">
    <div class="left"><a href="actividades.php"><img src="imgs/actividades.png" alt="Las actividades que Buzz tiene para ti!" width="206" height="51" border="0" /></a></div><div class="bordederecho1"></div><div class="miniindex left">
      <div class="contieneminifoto"><a href="actividadesinterno.php?id=<?php echo $actividad1->id_actividades;?>"><img src="imgs/actividades/<?php echo $actividad1->id_actividades;?>/<?php echo $actividad1->imagen;?>" width="250" height="150" border="0" /></a></div><div class="textotitulomini texto250"><a href="actividadesinterno.php?id=<?php echo $actividad1->id_actividades;?>"><?php echo $actividad1->titulo;?></a></div></div>
  </div>
  <div id="twitterfront" class="left margenizq"><script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 300,
  height: 505,
  theme: {
    shell: {
      background: '#333333',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#000000',
      links: '#0707eb'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('Publicidad_UDP').start();
</script></div>
  <div class="alpha azul left margender">
    <div class="left"><a href="comunidaduniversitaria.php"><img src="imgs/comunidad.png" alt="Conoce a toda la comundad de BZ!" width="206" height="51" border="0" /></a></div><div class="bordederecho1"></div><div class="contieneindex left">
      <div class="fotoancha left"><a href="comunidadinterno.php?id=<?php echo $comunidad1->id_comunidad;?>"><img src="imgs/comunidad/<?php echo $comunidad1->id_comunidad;?>/<?php echo $comunidad1->imagen;?>" width="330" height="170" border="0" /></a></div>
      <div class="textoindex left">
        <div class="textotitulomini texto250"><a href="comunidadinterno.php?id=<?php echo $comunidad1->id_comunidad;?>"><?php echo $comunidad1->titulo;?></a></div><div class="textoexplica"><a href="comunidadinterno.php?id=<?php echo $comunidad1->id_comunidad;?>"><?php echo 'Publicado por '.$usuario4->nombre.', el '.$comunidad1->fecha_publicacion.'<br /><br />';?><?php echo $comunidad1->resumen;?></a></div></div>
    </div>
  </div>
  <div class="beta calipso left margender2">
    <div class="left"><a href="opina.php"><img src="imgs/opina2.png" width="118" height="51" border="0" /></a></div><div class="bordederecho1"></div><div class="miniindex left">
      <div class="contieneminifoto"><a href="opinainterno.php?id=<?php echo $opina1->id_opina;?>"><img src="imgs/opina/<?php echo $opina1->id_opina;?>/<?php echo $opina1->imagen;?>" width="250" height="150" /></a></div><div class="textotitulomini texto250"><a href="opinainterno.php?id=<?php echo $opina1->id_opina;?>"><?php echo $opina1->titulo;?></a></div></div>
  </div>
  <div class="beta celeste left margender2">
    <div class="left"><a href="recursos.php"><img src="imgs/Recursos.png" alt="Documentos, Videos y más en BZ" width="173" height="51" border="0" /></a></div><div class="bordederecho1"></div><div class="miniindex left"><div class="contieneminifoto"><a href="recursos.php"><img src="<?php echo 'imgs/recursos/'.$recursos1->id_recursos.'/'.$recursos1->imagen;?>" width="250" height="150" border="0" /></a></div><div class="textotitulomini texto250"><a href="recursos.php"><?php echo $recursos1->titulo;?></a></div></div>
  </div>
  <div id="gamma" class="left margenizq">
    <div class="left" id="espacio920"><a href="cosecha.php"><img src="imgs/cosecha.png" alt="Los mejores trabajos de Publicidad UDP" width="167" height="51" border="0" /></a></div>
	<div class="miniindex left">
	<div class="contieneminifoto">
	<?php if($cosecha1->tipo_cosecha != 4){?>
	<a href="cosechainterno.php?tipo=<?php echo $cosecha1->tipo_cosecha;?>&id=<?php echo $cosecha1->id_cosecha;?>">
	<?php }else{?>
	<a href="cosecha.php">
	<?php }?>
	<img src="<?php echo 'imgs/cosecha/'.$cosecha1->id_cosecha.'/'.$cosecha1->imagen;?>" width="250" height="150" border="0" /></a></div><div class="textotitulomini texto250">
	<?php if($cosecha1->tipo_cosecha != 4){?>
	<a href="cosechainterno.php?tipo=<?php echo $cosecha1->tipo_cosecha;?>&id=<?php echo $cosecha1->id_cosecha;?>">
	<?php }else{?>
	<a href="cosecha.php">
	<?php }?><?php echo $cosecha1->titulo;?></a></div></div>
	<div class="miniindex left">
	<div class="contieneminifoto">
	<?php if($cosecha2->tipo_cosecha != 4){?>
	<a href="cosechainterno.php?tipo=<?php echo $cosecha2->tipo_cosecha;?>&id=<?php echo $cosecha2->id_cosecha;?>">
	<?php }else{?>
	<a href="cosecha.php">
	<?php }?>
	<img src="<?php echo 'imgs/cosecha/'.$cosecha2->id_cosecha.'/'.$cosecha2->imagen;?>" width="250" height="150" border="0" /></a></div><div class="textotitulomini texto250">
	<?php if($cosecha2->tipo_cosecha != 4){?>
	<a href="cosechainterno.php?tipo=<?php echo $cosecha2->tipo_cosecha;?>&id=<?php echo $cosecha2->id_cosecha;?>">
	<?php }else{?>
	<a href="cosecha.php">
	<?php }?><?php echo $cosecha2->titulo;?></a></div></div>
	<div class="miniindex left">
	<div class="contieneminifoto">
	<?php if($cosecha3->tipo_cosecha != 4){?>
	<a href="cosechainterno.php?tipo=<?php echo $cosecha3->tipo_cosecha;?>&id=<?php echo $cosecha3->id_cosecha;?>">
	<?php }else{?>
	<a href="cosecha.php">
	<?php }?>
	<img src="<?php echo 'imgs/cosecha/'.$cosecha3->id_cosecha.'/'.$cosecha3->imagen;?>" width="250" height="150" border="0" /></a></div><div class="textotitulomini texto250">
	<?php if($cosecha3->tipo_cosecha != 4){?>
	<a href="cosechainterno.php?tipo=<?php echo $cosecha3->tipo_cosecha;?>&id=<?php echo $cosecha3->id_cosecha;?>">
	<?php }else{?>
	<a href="cosecha.php">
	<?php }?><?php echo $cosecha3->titulo;?></a></div></div>
  </div>
  <div id="footer"><?php echo $footer;?></div>
  <div id="cierra"></div>
</div>
</body>
</html>
