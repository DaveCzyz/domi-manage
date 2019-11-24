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


// Read group and related loads 
$relatedLoad = "";
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editGroup'])){
    $id      = $_POST['id'];
    $load_id = $_POST['load_id'];
    $user_id = $user->id;

    $l = new Loads();
    $l->getOneGroup($id, $load_id, $user_id);

    if($l->related_loads != 0){
        $relatedLoad = $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($load_id));
    }else{
        $relatedLoad = "";
    }
}


// Save changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['saveGroup'])){
    if($_POST['id'] != "" && $_POST['load_id'] != ""){
        $id         = $_POST['id'];
        $load_id    = $_POST['load_id'];

        $l = new Loads();
        $l->getOneGroup($id, $load_id, $user->id);

        $l->customer        = $_POST['customer'];
        $l->origin_name     = $_POST['origin_name'];
        $l->origin_country  = $_POST['origin_country'];
        $l->destination_name    = $_POST['destination_name'];
        $l->destination_country = $_POST['destination_country'];

        $l->editLoad($id, $load_id);

        if($l->related_loads != 0){
            $relatedLoad = $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($load_id));
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
    $user_id         = $user->id;
    $load_id         = $_POST['load_id'];
    $id              = $_POST['id'];

    $l = new Loads();
    $l->getOneGroup($id, $load_id, $user_id);

    if($l->related_loads != 0){
        ActiveLoads::deleteRelatedLoads($user_id, $load_id);
    }

    if(Loads::deleteLoad($user_id, $id, $load_id)){
        $session->message("Grupa została usunięta", "success");
        redirect("loads.php");
    };

}


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

    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <!-- Card Title -->
                <p class="h4 text-center py-4">Ładunki powiązane z grupą</p>

                <?php if($relatedLoad == "" && !is_array($relatedLoad)) : ?>
                    <p class="text-center">Brak</p>
                <?php endif; ?>
            
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