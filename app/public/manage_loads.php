<?php 
require 'header.php'; 

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Store related loads
$relatedLoad = "";
// Get group and related loads 
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editGroup'])){
    $l = new Loads();
    if($l->getOneGroup($_POST['id'], $_POST['load_id'], $user->id)){
        Session::manageLoads($_POST['id'], $_POST['load_id']);
        if($l->related_loads != 0){
            $relatedLoad = $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($_POST['load_id']));
        }else{
            $relatedLoad = "";
        }
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        Session::clearManageLoads();
        redirect("loads.php");
    }
}
// Store load_id in session
if(isset($_SESSION['load_id']) && isset($_SESSION['load_uuid'])){
    $id         = $_SESSION['load_id'];
    $load_id    = $_SESSION['load_uuid'];
    $l          = new Loads();
    $l->getOneGroup($id, $load_id, $user->id);
    if($l->related_loads != 0){
        $relatedLoad = $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($load_id));
    }
}
// Save changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['saveGroup'])){
    if($_POST['id'] == $l->id && $_POST['load_id'] == $l->load_id){
        $l->customer            = $_POST['customer'];
        $l->origin_name         = $_POST['origin_name'];
        $l->origin_country      = $_POST['origin_country'];
        $l->destination_name    = $_POST['destination_name'];
        $l->destination_country = $_POST['destination_country'];
        if($l->editLoad()){
            $session->message("Zmiany zostały zapisane", "success");
            redirect("manage_loads.php");
        }else{
            $session->message($this->err[0], "alert");
            redirect("manage_loads.php");
        }
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("manage_loads.php");
    }
}
// Cancel saving changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cancelSaving'])){
    Session::clearManageLoads();
    redirect("loads.php");
}
// Delete group
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteGroup'])){
    // Delete from button in loads.php
    if(isset($_POST['id']) && isset($_POST['load_id'])){
        ActiveLoads::deleteRelatedLoads($user->id, $_POST['load_id']);
        Loads::deleteLoad($user->id, $_POST['id'], $_POST['load_id']);
        Session::clearManageLoads();
        $session->message("Grupa została usunięta", "success");
        redirect("loads.php");

    }else{
        // Delete from button in manage_loads.php
        if($l->related_loads != 0){
            ActiveLoads::deleteRelatedLoads($user->id, $l->load_id);
        } 
        if(Loads::deleteLoad($user->id, $l->id, $l->load_id)){
            $session->message("Grupa została usunięta", "success");
            Session::clearManageLoads();
            redirect("loads.php");
        }else{
            $session->message("Błąd podczas usuwania grupy. Spróbuj ponownie", "error");
            redirect("manage_loads.php");
        }
    }
}
// Add new realted load
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addNewLoad'])){
        $rl = new ActiveLoads($l->load_id, $user->id);
        // Origin fields
        $rl->origin_name          = $_POST['loadOriginCity'];
        $rl->origin_country       = $_POST['loadOriginCountry'];
        $rl->origin_postcode      = $_POST['loadOriginPostcode'];
        // Destination fields
        $rl->destination_name     = $_POST['loadDestinationCity'];
        $rl->destination_country  = $_POST['loadDestinationCountry'];
        $rl->destination_postcode = $_POST['loadDestinationPostcode'];
        // Load details
        $rl->trailer              = $_POST['trailerDetails'];
        $rl->weight               = $_POST['weightDetails'];
        $rl->length               = $_POST['lengthDetails'];
    
        if($rl->addNewLoad()){
            if($l->updateCounter("plus")){
                $session->message("Ładunek został dodany", "success");
                redirect("manage_loads.php");          
            };
        }else{
            $session->message($rl->err[0], "error");
            redirect("manage_loads.php.php");
        }
};
// Delete specify related load
if(isset($_GET['delete'])){
    $load_id = $_GET['delete'];
    if(ActiveLoads::deleteOneLoad($user->id, $load_id)){
        if($l->updateCounter("minus")){
            $session->message("Ładunek został usunuęty", "success");
            redirect("manage_loads.php");
        }
    }else{
        $session->message("Wystąpił błąd poczas usuwania", "error");
        redirect("manage_loads.php.php");
    }
}
// Edit specify related load
if(isset($_GET['edit'])){
    $load_id = $_GET['edit'];
    $rl_edit = new ActiveLoads($l->load_id, $user->id);
    $rl_edit->showOneRelatedLoad($load_id, $user->id);
    if(!empty($rl_edit->err)){
        $session->message($rl_edit->err[0], "error");
        redirect("manage_loads.php");
    }
}
// Save changes for related load
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['saveChanges'])){
    $load_id = $_POST['load_id'];
    $rl_edit = new ActiveLoads($l->load_id, $user->id);
    $rl_edit->showOneRelatedLoad($load_id, $user->id);

    $rl_edit->origin_name       = $_POST['loadOriginCity'];
    $rl_edit->origin_country    = $_POST['loadOriginCountry'];
    $rl_edit->origin_postcode   = $_POST['loadOriginPostcode'];

    $rl_edit->destination_name      = $_POST['loadDestinationCity'];
    $rl_edit->destination_country   = $_POST['loadDestinationCountry'];
    $rl_edit->destination_postcode  = $_POST['loadDestinationPostcode'];

    $rl_edit->trailer    = $_POST['trailerDetails'];
    $rl_edit->weight     = $_POST['weightDetails'];
    $rl_edit->length     = $_POST['lengthDetails'];

    if($rl_edit->editLoad()){
        $session->message("Edycja zakończona sukcesem", "success");
        redirect("manage_loads.php");
    }else{
        $session->message($rl_edit->err[0], "error");
        redirect("manage_loads.php");
    }


}
// Cancel saving changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cancelSaveChanges'])){
    redirect('manage_loads.php');
}


?>


<?php if($user->trans_id == "") : ?>
    <div class="alert alert-danger text-center" role="alert">
        Twoje konto nie jest połączone z platformą Trans. <br>
        Nie możesz wystawiać ładunków. Przejdz do panelu użytkownika.
    </div>
<?php endif; ?>


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
                <p class="h4 text-center py-4">Ładunki powiązane z grupą 
                    <?php if(!isset($_GET['edit'])) : ?>
                        <button id="addNewLoad" class="btn btn-success green darken-1 btn-sm" >Dodaj</button>
                    <?php endif; ?>
                </p>

                <!-- Add / Edit new related load -->
                <div class="row justify-content-center" id="addNewRelatedLoad" <?php if(!isset($_GET['edit'])){ echo "style='display:none'"; } ?> >
                    <div class="col-8">
                        <!-- form for add new load -->
                        <form action="manage_loads.php" method="POST">
                            <p class="h5 mb-4 text-center">
                                <?php if(isset($_GET['edit'])) : ?>
                                    Edytuj ładunek
                                <?php else : ?>
                                    Dodaj nowy ładunek
                                <?php endif; ?>
                            </p>
                            <!-- load uuid -->
                            <?php if(isset($_GET['edit'])) : ?>
                                <input type="hidden" name="load_id" value="<?php echo $rl_edit->load_id; ?>">
                            <?php endif; ?>
                            <!-- Origin city -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Origin city -->
                                    <label for="loadOriginCity" class="text-left">Miasto załadunku</label>
                                    <input type="text" id="loadOriginCity" name="loadOriginCity" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->origin_name."'"; } ?> required>
                                </div>
                            </div>

                            <!-- Origin -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Origin Country -->
                                    <label for="loadOriginCountry" class="text-left">Kraj załadunku</label>
                                    <input type="text" id="loadOriginCountry" name="loadOriginCountry" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->origin_country.", ".$rl_edit->origin_iso."'"; } ?> required>
                                    <ul class="list-group" id="loadOriginResult"></ul>
                                </div>
                                <div class="col">
                                    <!-- Origin Postcode-->
                                    <label for="loadOriginPostcode">Kod pocztowy <i id="postcodeFormatOrigin"></i></label>
                                    <input type="text" id="loadOriginPostcode" name="loadOriginPostcode" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->origin_postcode."'"; } ?> required>
                                </div>
                            </div>

                            <!-- Destination City -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Destination city -->
                                    <label for="loadDestinationCity" class="text-left">Miasto rozładunku</label>
                                    <input type="text" id="loadDestinationCity" name="loadDestinationCity" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->destination_name."'"; } ?> required>
                                </div>
                            </div>
                            
                            <!-- Destination -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Destination country -->
                                    <label for="loadDestinationCountry" class="text-left">Kraj rozładunku</label>
                                    <input type="text" id="loadDestinationCountry" name="loadDestinationCountry" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->destination_country.", ". $rl_edit->destination_iso ."'"; } ?> required>
                                    <ul class="list-group" id="loadDestinationResult"></ul>
                                </div>
                                <div class="col">
                                    <!-- Destination postcode-->
                                    <label for="loadDestinationPostcode">Kod pocztowy <i id="postcodeFormatDestination"></i></label>
                                    <input type="text" id="loadDestinationPostcode" name="loadDestinationPostcode" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->destination_postcode."'"; } ?> required>
                                </div>
                            </div>


                            <!-- Details -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Details weight -->
                                    <label for="weightDetails" class="text-left">Waga towaru (t)</label>
                                    <input type="text" id="weightDetails" name="weightDetails" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->weight."'"; } ?> required>
                                </div>
                                <div class="col">
                                    <!-- Details lenght-->
                                    <label for="lengthDetails">Długość towaru (m)</label>
                                    <input type="text" id="lengthDetails" name="lengthDetails" class="form-control" <?php if(isset($_GET['edit'])){ echo "value='".$rl_edit->length."'"; } ?> required>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="form-row mb-4">

                                <div class="col">
                                    <!-- Details trailer -->
                                    <label for="trailerDetails" class="text-left">Rodzaj naczepy</label>
                                    <select id="trailerDetails" name="trailerDetails" class="browser-default custom-select">
                                        <?php if(isset($_GET['edit'])) : ?>
                                            <option value="1" <?php if($rl_edit->trailer == 1){ echo "selected"; } ?> >Plandeka - standard</option>
                                            <option value="2" <?php if($rl_edit->trailer == 2){ echo "selected"; } ?>>Plandeka - mega</option>
                                            <option value="3" <?php if($rl_edit->trailer == 3){ echo "selected"; } ?>>Zestaw 7+7</option>
                                            <option value="4" <?php if($rl_edit->trailer == 4){ echo "selected"; } ?>>Platforma</option>
                                            <option value="5" <?php if($rl_edit->trailer == 5){ echo "selected"; } ?>>Izoterma</option>
                                            <option value="6" <?php if($rl_edit->trailer == 6){ echo "selected"; } ?>>Chłodnia</option>
                                            <option value="7" <?php if($rl_edit->trailer == 7){ echo "selected"; } ?>>Coilmulda</option>
                                        <?php else : ?>
                                            <option value="1" >Plandeka - standard</option>
                                            <option value="2">Plandeka - mega</option>
                                            <option value="3">Zestaw 7+7</option>
                                            <option value="4">Platforma</option>
                                            <option value="5">Izoterma</option>
                                            <option value="6">Chłodnia</option>
                                            <option value="7">Coilmulda</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- empty separator -->
                                <div class="col"></div>
                            </div>

                            <div class="text-center"> 
                                <!-- Sign in button -->
                                <?php if(isset($_GET['edit'])) : ?>
                                    <input class="btn btn-success green darken-1 btn-sm" name="saveChanges" type="submit" value="Zapisz zmiany">
                                    <input class="btn btn-warning btn-sm" name="cancelSaveChanges" type="submit" value="Anuluj">
                                <?php else : ?>
                                    <input class="btn btn-success green darken-1 btn-sm" name="addNewLoad" type="submit" value="Dodaj">
                                    <button id="cancelNewLoad" class="btn btn-warning btn-sm">Anuluj</button>
                                <?php endif; ?>
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
                                <tr class="border-top border-bottom">
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
                                    <td><a href="manage_loads.php?edit=<?php echo $relatedLoad[$i]->load_id ;?>" class="btn btn-success green darken-1 btn-sm">Edytuj</a></td>
                                    <td><a href="manage_loads.php?delete=<?php echo $relatedLoad[$i]->load_id ;?>" class="btn btn-danger btn-sm">Usuń</a></td>
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