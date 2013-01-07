<?php
include('Image.php');
$img = new Image('12.jpg');
$value = $img->resizeImage(120,null);
if($value){
	echo "<br>Imagen ".$value." generada satisfactoriamente<br>";
}else{
	echo "<br>Imagen no generada, revisar errores<br>";
}
