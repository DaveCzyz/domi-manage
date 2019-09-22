<?php 
// 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg    = $session->message;
$errors = [];


?>

<div style="display: none">
    <input id="origin-input" class="controls" type="text" placeholder="Enter an origin location">
    <input id="destination-input" class="controls" type="text" placeholder="Enter a destination location">
</div>

<div id="map"></div>


















<script src="js/google_maps.js"> </script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=???libraries=places&callback=initMap"></script>
<?php require 'footer.php';?>
