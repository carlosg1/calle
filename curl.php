<?php
/*
 * curl.php
 * consulta al geoserver por los datos de una capa con getFeatureInfo
 * y devuelve los resultados en formato JSON
 *
 * Developer: Lic. Carlos Garcia - carlosgctes@gmail.com
 * Fecha actualizacion: 28/03/2019
 *
 */
// arma la url de los datos recibidos por GET
$url = '';

foreach($_GET as $clave => $valor){
  $t = strpos($valor, '?') ? '' : '&';
  $url .= $clave == 'url' ? $valor : $clave . '=' . $valor . $t;
}

// elimina el ultimo & (ampersand) de la cadena
$url = trim($url, '&');

// lee los datos utilizando la libreria curl y
// devuelve los datos en formato JSON
$curl=curl_init($url);

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($curl, CURLOPT_MUTE, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

$return_data = curl_exec($curl);

$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

$content=$return_data;

if ($http_status=="401")
{
  $content="error:401";
}


echo $content;


 ?>
