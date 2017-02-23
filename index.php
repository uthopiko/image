<?php
include('Image.php');
$img = new Image('12.jpg');
$value = $img->resizeImage(120,null);
if($value){
	echo "<br>Imagen ".$value." generada satisfactoriamente<br>";
	echo "<br>Imagen2 ".$value." generada2 satisfactoriamente2<br>";
	echo "<br>Imagen2 ".$value." generada2 satisfactoriamente2<br>";
	echo "<br>Imagen2 ".$value." generada2 satisfactoriamente2<br>";
}else{
	echo "<br>Imagen no generada, revisar errores<br>";
	echo "<br>Imagen2 no generada2, revisar errores<br>";
	echo "<br>Imagen2 no generada2, revisar errores<br>";
	echo "<br>Imagen2 no generada2, revisar errores<br>";
}
