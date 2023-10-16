<?php

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                         :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                       :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2022                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

function deuerro() {
echo '<html lang="pt-br">';
echo '<head>';
echo '  <title>Pet Delivery</title>';
echo '  <meta name="viewport" content="width=device-width, initial-scale=1">';
echo '  <link rel="stylesheet" href="css/w3.css">';
echo '</head>';
echo '<body class="w3-container w3-center">';
echo '  <img class="w3-round" src="uberpet.png" width="350px"><br><br>';
echo '  Nome do bairro incorreto!<br><br><a href="index.php">Recomeçar</a>';
echo '</body>';
echo '</html>';
}

function pegacoordenadas($endereco) {
$baseUrl = 'https://nominatim.openstreetmap.org/search?format=json&limit=1';
$cidade = urlencode('manaus amazonas ');
$httpOptions = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Nominatim-Test"
    ]
];
$streamContext = stream_context_create($httpOptions);
$json = file_get_contents( "{$baseUrl}&q={$cidade}{$endereco}" , false, $streamContext);
$decoded = json_decode($json, true);
if ( empty($decoded) ) {
  deuerro();
  exit;
}

$partida = $decoded[0]["name"];
$lat = $decoded[0]["lat"];
$lng = $decoded[0]["lon"];
$a = [$partida, $lat, $lng];
return $a;
}

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <title>DrivePets</title>
</head>
<body>
        <!-- MENU Superior --> 
        <div class="topnav" id="MenuPetDrive">
          <a href="index.html" class="active"><img src="imgs/logo-pet-delivery-novo-branco.png" width="120px"></a>
          <a href="#help">Sobre</a>
          <a href="#servicos">Serviços</a>
          <a href="#contato">Contato</a>
          <a href="simule.php">Simule sua Corrida</a>
          <a href="javascript:void(0);" class="icon" onclick="myFunction()"><i class="fa fa-bars"></i></a>
        </div>
        <!-- Fim do Menu Superior -->

        <div class="topo">
          <div class="topo-left">
            <h1>Seu Pet andando pela cidade com toda a SEGURANÇA!</h1>
            <p>Bem-vindo ao site do DrivePet, sua escolha segura para o transporte de animais de estimação</p>
            <button><a href="#/">Saiba mais..</a></button>
          </div>
          <div class="topo-left"><div class="container__center"></div></div>
        </div>

<?php
if (! isset($_POST['partida'])) {
?>
<div>
<form class="simular" action="simule.php" method="post">
  <label for="porte">Qual o porte do seu pet?</label>
  <select id="porte" name="porte">
    <option value="pequeno">Pequeno</option>
    <option value="medio">Médio</option>
    <option value="grande">Grande</option>
  </select><br><br>
<!--
  <label for="data">Qual a data da viagem?</label>
  <input type="date" id="data" name="data"><br><br> -->
  <label for="partida">Informe o bairro de partida:</label>
  <input type="text" id="partida" name="partida"><br><br>
  <label for="destino">Informe o bairro de destino:</label>
  <input type="text" id="destino" name="destino"><br><br>
  <input type="submit" value="Calcular Taxa">
</form>
</div>
<?php
} else {

$partida = $_POST['partida'];
$destino = $_POST['destino'];
$porte   = $_POST['porte'];

if (empty($partida) or empty($destino)) {
  deuerro();
  exit;  
}

$resposta = pegacoordenadas(urlencode($partida));
$partida = $resposta[0];
$latpartida = $resposta[1];
$lngpartida = $resposta[2];

$resposta = pegacoordenadas(urlencode($destino));
$destino = $resposta[0];
$latdestino = $resposta[1];
$lngdestino = $resposta[2];

$distancia = round(distance($latpartida, $lngpartida, $latdestino, $lngdestino, "K"),0);
$distancia = intval($distancia);
$reaisporkm = 2.75;
$taxaportamnho = 1.3;
$valorminimo = 10;
$corrida   = $distancia * $reaisporkm;

// Se o porte do pet for médio ou grande, acrescer taxa extra por conta do tamanho
if ($porte == "medio" or $porte == "grande") {
  $corrida = $corrida * $taxaportamnho;
}

// Se o cálculo da corrida não atingir o valor mínimo, alterar para o valor mínimo
if ( $corrida <= $valorminimo ) {
  $corrida = $corrida + $valorminimo;
} 

?>

<!-- Mostra o cálculo da simulação da corrida -->
<div align="center">
<?php
echo '<h3>Cálculo da corrida</h3>';
echo '<div style="font-family: verdana; font-size: 14px;">';
echo '<br>';
echo 'Porte do pet: <b>' . strtoupper($porte) . '</b><br>';
echo 'Local de partida: <b>' . $partida . '</b><br>';
echo 'Local de destino: <b>' . $destino . '</b><br>';
echo 'Distância entre partida e destino: <b>' . $distancia . ' km' . '</b><br>';
echo 'Valor da corrida do Pet Delivery: R$ <b>' . number_format($corrida,2,",",".") . '</b>';
?>
</div>

</div>
<?php
}

?>

        <!-- Contato -->
        <form id="contato">
            <div class="form__row">
                <input type="text" id="name">
                <label for="name">Nome </label>
            </div>
            <div class="form__row">
                <input type="text" id="last-Name">
                <label for="last-Name"> Sobrenome</label>
            </div>
            <div class="form__row">
                <input type="email" id="email">
                <label for="email">Email</label>
            </div>
            <div class="form__row">
                <input type="number" id="phone">
                <label for="phone">Telefone</label>
            </div>
            <div class="form__row">
                <textarea  id="textarea" cols="30" rows="10" placeholder="Deixe seu Comentario "></textarea>
            </div>
            <button type="submit">Envie sua mensagem </button>
        </form>

        <footer>
            <div class="footer__cont center__row ">
                <p>2023 &copyDrivePets</p>
                <div>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </footer>

<script>
function myFunction() {
  var x = document.getElementById("MenuPetDrive");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>


</body>
</html>
