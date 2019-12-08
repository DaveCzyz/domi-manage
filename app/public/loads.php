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
// Filtr groups
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['sortGroups'])){
    $conditions = [$_POST['filtr_customer'], $_POST['filtr_origin_country'], $_POST['filtr_destination_country']];
    $tmp_arr = $groupLoads;
    for($i = 0; $i < count($conditions); $i++){
        if($conditions[$i] != "all"){
            $cust = $tmp_arr;
            unset($tmp_arr);
            foreach($cust as $key => $value){
                if(array_search($conditions[$i], $value)){
                    $tmp_arr[] = $value;
                }
            }
        }
    }
    if(empty($tmp_arr)){
        echo "brak wyników";
    }else{
        $groupLoads = $tmp_arr;
    }
}


?>

<!-- Notification for not connected account with Trans platform -->
<?php if($user->trans_id == "") : ?>
    <div class="alert alert-danger text-center" role="alert">
        Twoje konto nie jest połączone z platformą Trans. <br>
        Nie możesz wystawiać ładunków. Przejdz do panelu użytkownika.
    </div>
<?php endif; ?>

<!-- Page tittle and system message -->
<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Zarządzaj bieżącymi ładunkami <button type="button" id="addNewLoadGroup" class="btn btn-sm btn-success green darken-1">Dodaj grupę</button></h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>

<!-- Add new group of loads -->
<div class="row justify-content-center" id="loadGroup" style="display:none">
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <!-- Loads group form -->
                <form action="loads.php" method="POST">

                    <p class="h4 mb-4 text-center">Dodaj nową grupę ładunków</p>

                    <!-- Customer -->
                    <div class="form-row mb-4">
                        <div class="col">
                            <!-- Customer name -->
                            <label for="customerName" class="text-left">Nazwa klienta / grupy</label>
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
        </div>
    </div>
</div><!-- end -->

<!-- Display all loads-->
<div class="row justify-content-center">

    <!-- Display all groups of loads -->
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <!-- Column tittle -->
                <p class="h4 text-center py-2">Grupy ładunków <button class="btn btn-success green darken-1 btn-sm">Filtruj</button></p>

                    <!-- Sort groups -->
                    <form action="loads.php" method="POST">

                        <!-- Sort groups by customer name -->
                        <label for="sortGroupsByCustomer">Sortuj po - nazwa grupy</label>
                        <select name="filtr_customer" id="sortGroupsByCustomer" class="browser-default custom-select">
                            <option value="all">Wszystkie</option>
                            <?php foreach($groupLoads as $key => $value) : ?>
                                <option value="<?php echo $value['customer']; ?>"> <?php echo $value['customer']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <!-- Sort groups by origin country -->
                        <label for="sortGroupsByOriginCountry">Sortuj po - kraj załadunku</label>
                        <select name="filtr_origin_country" id="sortGroupsByOriginCountry" class="browser-default custom-select">
                            <option value="all">Wszystkie</option>
                            <?php foreach($groupLoads as $key => $value) : ?>
                                <option value="<?php echo $value['origin_country']; ?>"> <?php echo $value['origin_country']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Sort groups by destination country -->
                        <label for="sortGroupsByDestinationCountry">Sortuj po - kraj rozładunku</label>
                        <select name="filtr_destination_country" id="sortGroupsByDestinationCountry" class="browser-default custom-select">
                            <option value="all">Wszystkie</option>
                            <?php foreach($groupLoads as $key => $value) : ?>
                                <option value="<?php echo $value['destination_country']; ?>"> <?php echo $value['destination_country']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <input type="submit" name="sortGroups" class="btn btn-success green darken-1 btn-sm" value="Szukaj">

                    </form> <!-- end sort groups -->

                <?php if(!empty($groupLoads)) : ?>
                
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
                                    <i class="card-text"><span class="flag-icon flag-icon-<?php echo strtolower($value['origin_iso']); ?>"></span> <?php echo $value['origin_country']; ?> - <span class="flag-icon flag-icon-<?php echo strtolower($value['destination_iso']); ?>"></span> <?php echo $value['destination_country']; ?></i>
                                    <br>
                                    <input type="submit" name="addGroup" class="btn btn-success green darken-1 btn-sm" value="Wystaw">
                                    <input type="submit" name="editGroup" class="btn btn-success green darken-1 btn-sm" value="Edytuj">
                                    <a href="#" data-loadID="<?php echo $value['load_id'];?>" class="seeMore btn btn-success green darken-1 btn-sm">Rozwiń (<?php echo $value['related_loads']; ?>)</a>
                                    <input type="submit" name="deleteGroup" class="btn btn-danger btn-sm" onclick="return confirm('Czy napewno chcesz usunąć wybraną grupę ładunków? Zmian nie można cofnąć.');" value="Usuń">
                                </div>
                                <ul style="display:none" id="group-<?php echo $value['load_id'];?>"></ul>
                            </div>
                        </form>
                    <?php endforeach; ?>

                <?php elseif(empty($groupLoads)) : ?>       
                    <p class="text-center">Brak</p>
                <?php endif; ?>

            </div>
        </div>
    </div><!-- end -->

    <!-- Dispaly All active loads -->
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <!-- Column tittle -->
                <p class="h4 text-center py-2">Aktywne ładunku</p>
                <p class="text-center">Brak</p>
            </div>
        </div>
    </div>

</div><!-- end-->








<!-- AJAX request for 'see more' button in group of loads -->
<script src="js/groupManage.js"></script>
<!-- All AJAX request for countries live search are in script below -->
<script src="js/liveSearch.js"></script>
<?php require 'footer.php';?>