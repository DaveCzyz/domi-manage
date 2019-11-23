<?php 
require 'header.php'; 

// Check if user are logged
if(!$session->isLogged()){
    redirect("index.php");
}

// Display system messages
$session->displayMessage();
$msg_status = $session->status;
$msg        = $session->message;

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Get user loads
$groupLoads = Loads::getGroups($userID);

// Add new group of loads to database
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addLoadGroup'])){
    $loadGroup                      = new Loads();
    $loadGroup->user_id             = $userID;
    $loadGroup->customer            = $_POST['customerName'];
    $loadGroup->origin_name         = $_POST['originCity'];
    $loadGroup->origin_country      = $_POST['originCountry'];
    $loadGroup->destination_name    = $_POST['destinationCity'];
    $loadGroup->destination_country = $_POST['destinationCountry'];
    $loadGroup->active_loads        = "NO";
    // Save new group
    if($loadGroup->addNewGroup()){
        $session->message("Nowa grupa została utworzona poprawnie", "success");
        redirect("loads.php");  
    }else{
        $session->message($user->err[0], "error");
        redirect("loads.php"); 
    }
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

            <p class="h4 mb-4 text-center">Dodaj nową grupę ładunków</p>

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
                    <input class="btn btn-warning warning-color btn-block my-4" id="cancelLoadGroup" type="button" value="Anuluj">
                </div>
                <div class="col-6">
                    <input class="btn btn-success green darken-1 btn-block my-4" id="addLoadGroup" name="addLoadGroup" type="submit" value="Dodaj">
                </div>
            </div>

        </form>
        <!-- Loads group form -->
    </div>
</div><!-- end -->

<!-- Add new group of loads -->
<div class="row justify-content-center">
    <div class="col-3 text-center">
        <button type="button" id="addNewLoadGroup" class="btn btn-success green darken-1">Dodaj grupę</button>
    </div>
</div><!-- end-->








<!-- Display avaiable loads-->
<div class="row justify-content-center">
    <div class="col-5 border">
        <!-- Column tittle -->
        <h4>Grupy ładunków</h4>

        <?php foreach($groupLoads as $key => $value): ?>
            <form method="POST" action="manage_loads.php">
                <!-- Hidden input for ID -->
                <input type="hidden" name="id" value="<?php echo $value['id'];?>">
                <!-- Hidden input for load ID -->
                <input type="hidden" name="load_id" value="<?php echo $value['load_id'];?>">

                <!-- Load group card -->
                <div class="card">
                    <!-- Load group customer name-->
                    <div class="card-header green darken-1 text-white">
                        <?php echo $value['customer']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $value['origin_name']; ?> - <?php echo $value['destination_name']; ?></h5>
                        <i class="card-text"><?php echo $value['origin_country']; ?> <span class="flag-icon flag-icon-<?php echo strtolower($value['origin_iso']); ?>"></span> - <?php echo $value['destination_country']; ?> <span class="flag-icon flag-icon-<?php echo strtolower($value['destination_iso']); ?>"></span></i>
                        <br>
                        <input type="submit" name="addGroup" class="btn btn-success green darken-1 btn-sm" value="Wystaw">
                        <input type="submit" name="editGroup" class="btn btn-success green darken-1 btn-sm" value="Edytuj">
                        <a href="" id="seeMore" data-loadID="<?php echo $value['load_id'];?>" class="btn btn-success green darken-1 btn-sm">Rozwiń (<?php echo $value['related_loads']; ?>)</a>
                        <input type="submit" name="deleteGroup" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to do that?');" value="Usuń">
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
    </div>














    <div class="col-1"></div>

    <div class="col-5 border">
        <h4>Twoje aktywne ładunki</h4>
    </div>

</div><!-- end-->









<!-- AJAX request for 'see more' button in group of loads -->
<script src="js/groupManage.js"></script>
<!-- All AJAX request for countries live search are in script below -->
<script src="js/liveSearch.js"></script>
<?php require 'footer.php';?>