<?php

	if (!isset($_SESSION)) { session_start(); }

        $url=$_GET['url'];

        $_SESSION["imagencadena"] = randomText(5);
        $imagenCadena = $_SESSION["imagencadena"];
// Verificamos que el usuario inicio sesión
if (! isset($imagenCadena)){
// El usuario no inicio sessión header("HTTP/1.0 405"); // Recurso no permitido
	return;
}

        $alto = 32;
        $espacio= 25;
        $ancho = $espacio * strlen($imagenCadena);
        $fuente = $url.'arial.ttf';
        $fontsize = '20';
	$img = @imagecreatetruecolor($ancho, $alto);

        imagealphablending($img,false);
        //$fondo = imagecolorallocate($img, 255, 255, 255);
	//fondo blanco transparente (valor alfa: 0 opaco > 127 transparente)
        $fondot = imagecolorallocatealpha($img, 255, 255, 255, 127);
        //Hacemos nuestro rectángulo para tapar el fondo (transparente)
	imagefilledrectangle ( $img , 0 , 0 , $ancho, $alto , $fondot);

	$blanco = imagecolorallocate($img, 100, 100, 100);
	$negro = @imagecolorallocate($img, 0, 0, 0);
	for ($i=0, $l = strlen($imagenCadena); $i < $l; $i++){
                if ($i>0 && $i % 2) {
                    $ang = 20;
                } else { 
                    $ang = -10;
                }  
		@imagettftext($img, $fontsize, $ang, $i*$espacio - 2, 26, $negro, $fuente, $imagenCadena[$i]);
		@imagettftext($img, $fontsize, $ang, $i*$espacio , 24, $blanco, $fuente, $imagenCadena[$i]);
	}
        //noise: points in pixeles
	for ($i=0; $i < 60*strlen($imagenCadena); $i++){
             $x= rand(0, $ancho);
             $y= rand(0, $alto);
             imagesetpixel($img, $x,$y, $negro);
        } 


header("Content-type: image/png");
imagesavealpha($img,true);
@imagepng($img);


// Liberamos recursos
@imagedestroy($img); 

function randomText($length) {
	$key = ' ';
        $key1 = ' ';
	//$cadena = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
        $cadena="AaBbCcDdEeFfGgHhJjKkMmNnPpQqRrSsTtUuVvWwXxYyZx2345689";
	for($i=0;$i<$length;$i++) {

	      $key .= $cadena{rand(0, strlen($cadena)-1)};

	}
	return $key.$key1;
 
}
?>
