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


// Get group and related loads 
$relatedLoad = "";
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editGroup'])){
    $l = new Loads();
    $l->getOneGroup($_POST['id'], $_POST['load_id'], $user->id);
    if($l->related_loads != 0){
        $relatedLoad = $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($load_id));
    }else{
        $relatedLoad = "";
    }
}
// Save changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['saveGroup'])){
    if($_POST['id'] != "" && $_POST['load_id'] != ""){
        $l = new Loads();
        $l->getOneGroup($_POST['id'], $_POST['load_id'], $user->id);
        $l->customer        = $_POST['customer'];
        $l->origin_name     = $_POST['origin_name'];
        $l->origin_country  = $_POST['origin_country'];
        $l->destination_name    = $_POST['destination_name'];
        $l->destination_country = $_POST['destination_country'];
        $l->editLoad($_POST['id'], $_POST['load_id']);
        if($l->related_loads != 0){
            $relatedLoad = $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($_POST['load_id']));
        }  
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "alert");
        redirect("loads.php");
    }
}
// Cancel saving changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cancelSaving'])){
    redirect("loads.php");
}
// Delete group
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteGroup'])){
    $l = new Loads();
    $l->getOneGroup($_POST['id'], $_POST['load_id'], $user->id);

    if($l->related_loads != 0){
        ActiveLoads::deleteRelatedLoads($user->id, $_POST['load_id']);
    }
    if(Loads::deleteLoad($user->id, $_POST['id'], $_POST['load_id'])){
        $session->message("Grupa została usunięta", "success");
        redirect("loads.php");
    };
}
// Add new realted load
// if(){

// };

?>

<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Edytuj grupę ładunków</h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>

<div class="row justify-content-center">
    <!-- Display form with group of loads -->
    <div class="col-3">
            <!-- Card -->
            <div class="card">
                <!-- Card body -->
                <div class="card-body">
                    <!-- Material form register -->
                    <form action="manage_loads.php" method="POST">
                        <p class="h4 text-center py-4">Edytuj grupę <input class="btn btn-danger btn-sm" name="deleteGroup" type="submit" value="Usuń"></p>

                        <!-- Hidden input for ID -->
                        <input type="hidden" name="id" value="<?php echo $l->id;?>">
                        <!-- Hidden input for Load_ID -->
                        <input type="hidden" name="load_id" value="<?php echo $l->load_id;?>">

                        <!-- Material input Customer -->
                        <div class="md-form">
                            <input type="text" id="editCustomer" name="customer" class="form-control" value="<?php echo $l->customer; ?>">
                            <label for="editCustomer" class="font-weight-light active">Klient / Nazwa grupy</label>
                        </div>

                        <!-- Material input Origin Name -->
                        <div class="md-form">
                            <input type="text" id="editOriginName" name="origin_name" class="form-control" value="<?php echo $l->origin_name; ?>">
                            <label for="editOriginName" class="font-weight-light active">Miejsce załadunku</label>
                        </div>

                        <!-- Material input Origin Country -->
                        <div class="md-form">
                            <label for="editOriginCountry" class="font-weight-light active">Kraj załadunku</label>
                            <input type="text" id="editOriginCountry" name="origin_country" class="form-control" value="<?php echo $l->origin_country; ?>">
                            <ul class="list-group" id="editOriginResult"></ul>
                        </div>

                        <!-- Material input Destination Name -->
                        <div class="md-form">
                            <input type="text" id="editDestinationName" name="destination_name" class="form-control" value="<?php echo $l->destination_name; ?>">
                            <label for="editDestinationName" class="font-weight-light active">Miejsce rozładunku</label>
                        </div>

                        <!-- Material input Destination Country -->
                        <div class="md-form">
                            <label for="editDestinationCountry" class="font-weight-light active">Kraj rozładunku</label>
                            <input type="text" id="editDestinationCountry" name="destination_country" class="form-control" value="<?php echo $l->destination_country; ?>">
                            <ul class="list-group" id="editDestinationResult"></ul>
                        </div>

                        <div class="text-center py-4 mt-3">
                            <input class="btn btn-success green darken-1 btn-sm" name="saveGroup" type="submit" value="Zapisz zmiany">
                            <button class="btn btn-warning btn-sm" name="cancelSaving" type="submit">Anuluj</button>
                        </div>
                    </form>
                    <!-- Material form register -->
                </div>
                <!-- Card body -->
            </div>
            <!-- Card -->
    </div><!-- end col-5 -->

    <!-- Display related loads with group -->
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <!-- Card Title -->
                <p class="h4 text-center py-4">Ładunki powiązane z grupą <button id="addNewLoad" class="btn btn-success green darken-1 btn-sm" >Dodaj</button></p>

                <div class="row justify-content-center" id="addNewRelatedLoad">
                    <div class="col-6">
                        <!-- form for add new load -->
                        <form action="manage_loads.php" method="POST">
                            <p class="h5 mb-4 text-center">Dodaj nowy ładunek</p>

                            <!-- Realted with-->
                            <input type="hidden" value="<?php echo $l->load_id; ?>">

                            <!-- Origin city -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Origin city -->
                                    <label for="loadOriginCity" class="text-left">Miasto załadunku</label>
                                    <input type="text" id="loadOriginCity" name="loadOriginCity" class="form-control" required>
                                </div>
                            </div>

                            <!-- Origin -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Origin Country -->
                                    <label for="loadOriginCountry" class="text-left">Kraj załadunku</label>
                                    <input type="text" id="loadOriginCountry" name="loadOriginCountry" class="form-control" value="<?php echo $l->origin_country; ?>" required>
                                    <ul class="list-group" id="loadOriginResult"></ul>
                                </div>
                                <div class="col">
                                    <!-- Origin Postcode-->
                                    <label for="loadOriginPostcode">Kod pocztowy</label>
                                    <input type="text" id="loadOriginPostcode" name="loadOriginPostcode" class="form-control" required>
                                </div>
                            </div>

                            <!-- Destination City -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Destination city -->
                                    <label for="loadDestinationCity" class="text-left">Miasto rozładunku</label>
                                    <input type="text" id="loadDestinationCity" name="loadDestinationCity" class="form-control" required>
                                </div>
                            </div>
                            
                            <!-- Destination -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Destination country -->
                                    <label for="loadDestinationCountry" class="text-left">Kraj rozładunku</label>
                                    <input type="text" id="loadDestinationCountry" name="loadDestinationCountry" class="form-control" value="<?php echo $l->destination_country; ?>" required>
                                    <ul class="list-group" id="loadDestinationResult"></ul>
                                </div>
                                <div class="col">
                                    <!-- Destination postcode-->
                                    <label for="loadDestinationPostcode">Kod pocztowy</label>
                                    <input type="text" id="loadDestinationPostcode" name="loadDestinationPostcode" class="form-control" required>
                                </div>
                            </div>


                            <!-- Details -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Details weight -->
                                    <label for="weightDetails" class="text-left">Waga towaru (t)</label>
                                    <input type="text" id="weightDetails" name="weightDetails" class="form-control" required>
                                </div>
                                <div class="col">
                                    <!-- Details lenght-->
                                    <label for="lengthDetails">Długość towaru (m)</label>
                                    <input type="text" id="lengthDetails" name="lengthDetails" class="form-control" required>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="form-row mb-4">

                                <div class="col">
                                    <!-- Details trailer -->
                                    <label for="trailerDetails" class="text-left">Rodzaj naczepy</label>
                                    <select id="trailerDetails" class="browser-default custom-select">
                                        <option value="1">Plandeka - standard</option>
                                        <option value="1">Plandeka - mega</option>
                                        <option value="3">Zestaw 7+7</option>
                                        <option value="3">Platforma</option>
                                        <option value="2">Izoterma</option>
                                        <option value="3">Chłodnia</option>
                                        <option value="3">Coilmulda</option>
                                    </select>
                                </div>
                                
                                <!-- empty separator -->
                                <div class="col"></div>
                            </div>

                            <div class="text-center"> 
                                <!-- Sign in button -->
                                <input class="btn btn-success green darken-1 btn-sm" name="addNewLoad" type="submit" value="Dodaj">
                                <button id="cancelNewLoad" class="btn btn-warning btn-sm">Anuluj</button>
                            </div>  

                        </form><!-- end form -->
                    </div><!-- ned col-4 -->
                </div><!-- end row -->

                <!-- If related loads not exists - do not show anythig -->
                <?php if($relatedLoad == "" && !is_array($relatedLoad)) : ?>
                    <p class="text-center">Brak</p>
                <?php endif; ?>
            
                <!-- If related loads exist - display all those load -->
                <?php if($relatedLoad != "" && is_array($relatedLoad)) : ?>
                    <table class="table table-borderless">
                        <tr>
                            <th>Lp.</th>
                            <th>Zał./Rozł.</th>
                            <th>Miasto</th>
                            <th>Kod pocztowy</th>
                            <th>Kraj</th>
                            <th>Naczepa</th>
                            <th>Waga</th>
                            <th>LDM</th>
                        </tr>
                    
                        <?php for($i = 0; $i < count($relatedLoad); ++$i) : ?>
                            <tr class="border-top">
                                <th scope="row"><?php echo $i + 1; ?></th>
                                <td>Załadunek</td>
                                <td><?php echo $relatedLoad[$i]->origin_name ;?></td>
                                <td><?php echo $relatedLoad[$i]->origin_postcode ;?></td>
                                <td><?php echo $relatedLoad[$i]->origin_country ;?></td>     
                                <td><?php echo $relatedLoad[$i]->trailer ;?></td>                           
                                <td><?php echo $relatedLoad[$i]->weight . " t" ;?></td>
                                <td><?php echo $relatedLoad[$i]->length . " m" ;?></td>
                            </tr>
                            <tr>
                            <th scope="row"></th>
                                <td>Rozładunek</td>
                                <td><?php echo $relatedLoad[$i]->destination_name ;?></td>
                                <td><?php echo $relatedLoad[$i]->destination_postcode ;?></td>
                                <td><?php echo $relatedLoad[$i]->destination_country ;?></td>  
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td><a class=" btn btn-success green darken-1 btn-sm">Edytuj</a></td>
                                <td><a class=" btn btn-danger btn-sm">Usuń</a></td>
                            </tr>
                        <?php endfor; ?>

                    </table>
                <?php endif; ?>

            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-5 -->

</div><!-- end row -->

<script src="js/liveSearch.js"></script>
<?php require 'footer.php';?>