<?php 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg    = $session->message;
$errors = [];

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Get user loads


// Add new group of loads to database
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addLoadGroup'])){
    print_r($_POST['customerName']);

}

?>

<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Zarządzaj bieżącymi ładunkami</h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>

<div class="row justify-content-center" id="loadGroup" style="display:none">
    <div class="col-8">
        <!-- Loads group form -->
        <form class="border border-light p-5" action="loads.php" method="POST">

            <p class="h4 mb-4 text-center">Dodaj nową grupe ładunków</p>

            <!-- Customer -->
            <div class="form-row mb-4">
                <div class="col">
                    <!-- Customer name -->
                    <label for="customerName" class="text-left">Nazwa klienta</label>
                    <input type="text" id="customerName" name="customerName" class="form-control" required>
                </div>
            </div>

            <!-- Origin -->
            <div class="form-row mb-4">
                <div class="col">
                    <!-- Origin City -->
                    <label for="originCity" class="text-left">Miejsce załadunku</label>
                    <input type="text" id="originCity" name="originCity" class="form-control" required>
                </div>
                <div class="col">
                    <!-- Origin country -->
                    <label for="originCountry">Kraj załadunku</label>
                    <input type="text" id="originCountry" name="originCountry" class="form-control" required>
                    <ul class="list-group" id="originResult"></ul>
                </div>
            </div>

            <!-- Destination -->
            <div class="form-row mb-4">
                <div class="col">
                    <!-- Destination city -->
                    <label for="destinationCity" class="text-left">Miejsce rozładunku</label>
                    <input type="text" id="destinationCity" name="destinationCity" class="form-control" required>
                </div>
                <div class="col">
                    <!-- Destination Country-->
                    <label for="destinationCountry">Kraj rozładunku</label>
                    <input type="text" id="destinationCountry" name="destinationCountry" class="form-control" required>
                    <ul class="list-group" id="destinationResult"></ul>
                </div>
            </div>

            <div class="form-row mb-4">
                <div class="col-6 text-right">
                    <input class="btn btn-warning warning-color btn-block my-4" id="cancelLoadGroup" type="submit" value="Anuluj">
                </div>
                <div class="col-6">
                    <input class="btn btn-success green darken-1 btn-block my-4" id="addLoadGroup" name="addLoadGroup" type="submit" value="Dodaj">
                </div>
            </div>

        </form>
        <!-- Loads group form -->
    </div>
</div>


<div class="row justify-content-center">
    <div class="col-3 text-center">
        <button type="button" id="addNewLoadGroup" class="btn btn-success green darken-1">Dodaj ładunek</button>
    </div>
</div>

<!-- All AJAX request for countries live search are in script below -->
<script src="js/liveSearch.js"></script>
<?php require 'footer.php';?>