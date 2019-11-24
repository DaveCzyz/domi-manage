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

// Edit group 
$currentLoad = "";
$relatedLoad = "";

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editGroup'])){
    $id      = $_POST['id'];
    $load_id = $_POST['load_id'];
    $user_id = $user->id;

    $currentLoad = Loads::getOneGroup($id, $load_id, $user_id);

    if($currentLoad['related_loads'] > 0){
        $relatedLoad = json_decode(ActiveLoads::showRelatedLoads($currentLoad['load_id']));
    }
}

// print_r($relatedLoad);
// var_dump($relatedLoad);
// Edit single load


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
    <div class="col-5">
        <?php if($currentLoad != "" && is_array($currentLoad)) : ?>
            <!-- Card -->
            <div class="card">
                <!-- Card body -->
                <div class="card-body">
                    <!-- Material form register -->
                    <form>
                    <p class="h4 text-center py-4">Edytuj grupę</p>

                    <!-- Material input text -->
                    <div class="md-form">
                        <input type="text" id="materialFormCardNameEx" class="form-control" value="<?php echo $currentLoad['customer']; ?>">
                        <label for="materialFormCardNameEx" class="font-weight-light active">Klient / Nazwa grupy</label>
                    </div>

                    <!-- Material input email -->
                    <div class="md-form">
                        <input type="text" id="materialFormCardEmailEx" class="form-control" value="<?php echo $currentLoad['origin_name']; ?>">
                        <label for="materialFormCardEmailEx" class="font-weight-light active">Miejsce załadunku</label>
                    </div>

                    <!-- Material input email -->
                    <div class="md-form">
                        <input type="text" id="materialFormCardConfirmEx" class="form-control" value="<?php echo $currentLoad['origin_country']; ?>">
                        <label for="materialFormCardConfirmEx" class="font-weight-light active">Kraj załadunku</label>
                    </div>

                    <!-- Material input password -->
                    <div class="md-form">
                        <input type="text" id="materialFormCardPasswordEx" class="form-control" value="<?php echo $currentLoad['destination_name']; ?>">
                        <label for="materialFormCardPasswordEx" class="font-weight-light active">Miejsce rozładunku</label>
                    </div>

                    <!-- Material input password -->
                    <div class="md-form">
                        <input type="text" id="materialFormCardPasswordEx" class="form-control" value="<?php echo $currentLoad['destination_country']; ?>">
                        <label for="materialFormCardPasswordEx" class="font-weight-light active">Kraj rozładunku</label>
                    </div>

                    <div class="text-center py-4 mt-3">
                        <button class="btn btn-cyan" type="submit">Register</button>
                    </div>
                    </form>
                    <!-- Material form register -->
                </div>
                <!-- Card body -->
            </div>
            <!-- Card -->
        <?php endif; ?>
    </div><!-- end col-5 -->

    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <!-- Card Title -->
                <p class="h4 text-center py-4">Ładunki powiązane z grupą</p>

                <?php if($relatedLoad == "" && !is_array($relatedLoad)) : ?>
                    Brak
                <?php endif; ?>
            
                <?php if($relatedLoad != "" && is_array($relatedLoad)) : ?>
                    <table class="table table-hover table-borderless">
                        <tr>
                            <th>Lp.</th>
                            <th>Miasto</th>
                            <th>Kod pocztowy</th>
                            <th>Kraj</th>
                        </tr>
                    
                        <?php for($i = 0; $i < count($relatedLoad); ++$i) : ?>
                            <tr class="border-top">
                                <th scope="row"><?php echo $i + 1; ?></th>
                                <td><?php echo $relatedLoad[$i]->origin_name ;?></td>
                                <td><?php echo $relatedLoad[$i]->origin_postcode ;?></td>
                                <td><?php echo $relatedLoad[$i]->origin_country ;?></td>                                
                            </tr>
                            <tr>
                            <th scope="row"></th>
                                <td><?php echo $relatedLoad[$i]->destination_name ;?></td>
                                <td><?php echo $relatedLoad[$i]->destination_postcode ;?></td>
                                <td><?php echo $relatedLoad[$i]->destination_country ;?></td>                                
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td><a class=" btn btn-success btn-sm">Edytuj</a></td>
                                <td><a class=" btn btn-warning btn-sm">Usuń</a></td>
                            </tr>
                        <?php endfor; ?>

                    </table>
                <?php endif; ?>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-5 -->

</div><!-- end row -->



















<?php require 'footer.php';?>