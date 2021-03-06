<?php 
require 'header.php'; 

// Check login session
if(!$session->isLogged()){
    redirect("index.php");
}

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

$getCarrier = Carrier::getAllCarriers($user->id);
$plans      = Planning::getPlans($user->id);

// Add new carrier
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addCarrier'])){
    $carrier = new Carrier($user->id);

    $carrier->carrier_name      = $_POST['carrierName'];
    $carrier->carrier_base      = $_POST['carrierBase'];
    $carrier->carrier_person    = $_POST['carrierPerson'];
    $carrier->carrier_phone     = $_POST['carrierPhone'];
    $carrier->carrier_email     = $_POST['carrierEmail'];

    if($carrier->setCarrier()){
        $session->message("Przewoźnik został dodany", "success");
        redirect("fleet.php");
    }else{
        $session->message($carrier->err[0], "error");
        redirect("fleet.php");
    }
}

// Read selected plan
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['getPlan'])){
    if(empty($_GET['getPlan'])){
        return false;
    }

    $i = $_GET['getPlan'];
    $planName;
    $trucks = [];
    $week = date("W");

    if($group = Planning::getTrucks($i)){
        $planName = $group[0]['plan_name'];
        foreach($group as $key => $value){
            $trucks[] = Planning::getTruckDetais($value['truck_uuid']);
        }
    }

    
}

// Read selected week
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['week'])){

}


?>



<!-- Page tittle and system message -->
<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Zarządzanie flotą 
            <button type="button" id="addNewCarrier" class="btn btn-sm btn-success green darken-1">Dodaj przewoźnika</button> 
        </h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>
<!-- end -->

<!-- Add new carrier -->
<div class="row justify-content-center" id="newCarrier" style="display:none">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
                <!-- Carrier form -->
                <form action="fleet.php" method="POST">

                    <p class="h4 mb-4 text-center">Dodaj przewoźnika</p>


                    <!-- Name & City -->
                    <div class="form-row mb-4">
                        <div class="col">
                            <!-- Carrier name -->
                            <label for="carrierName" class="text-left">Nazwa przewoźnika</label>
                            <input type="text" id="carrierName" name="carrierName" class="form-control" required>
                        </div>
                        <div class="col">
                            <!-- Carrier base -->
                            <label for="carrierBase">Miasto</label>
                            <input type="text" id="carrierBase" name="carrierBase" class="form-control" required>
                        </div>
                    </div>

                    <!-- Person & Phone -->
                    <div class="form-row mb-4">
                        <div class="col">
                            <!-- Carrier contact -->
                            <label for="carrierPerson" class="text-left">Osoba kontaktowa</label>
                            <input type="text" id="carrierPerson" name="carrierPerson" class="form-control" required>
                        </div>
                        <div class="col">
                            <!-- Carrier phone -->
                            <label for="carrierPhone">Numer telefonu </label>
                            <input type="text" id="carrierPhone" name="carrierPhone" class="form-control" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-row mb-4">
                        <div class="col">
                            <!-- Carrier email -->
                            <label for="carrierEmail" class="text-left">E-mail</label>
                            <input type="email" id="carrierEmail" name="carrierEmail" class="form-control" required>
                        </div>
                        <div class="col">
                            <!-- empty field -->
                        </div>
                    </div>

                    <div class="form-row mb-4">
                        <div class="col-6 text-right">
                            <input class="btn btn-warning warning-color btn-block my-4" id="cancelCarrier" type="button" value="Anuluj">
                        </div>
                        <div class="col-6">
                            <input class="btn btn-success green darken-1 btn-block my-4" id="addCarrier" name="addCarrier" type="submit" value="Dodaj">
                        </div>
                    </div>

                </form>
                <!-- end form -->
            </div>
        </div>
    </div>
</div><!-- end -->

<!-- Display carriers -->
<div class="row">
        <!-- if not carriers exist -->
        <?php if(empty($getCarrier)) : ?>
            <div class="col text-center">
                <p class="font-weight-bold">Brak</p>
            </div>
        <?php endif; ?>
        <!-- end -->

        <!-- Show carriers -->
        <?php if(!empty($getCarrier)) : ?>
            <?php foreach($getCarrier as $key => $value) : ?>

                <div class="col-3">

                    <form action="manage_carriers.php" method="POST">
                        <div class="card">
                            <div class="card-body">
                                <!-- Carrier name -->
                                <h5 class="card-title">
                                    <?php echo $value['carrier_name']; ?>
                                    <button type="button" class="showCarrierDetails btn btn-green light-green lighten-1 btn-sm float-right">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </h5>
                                <!-- Carrier base -->
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?php echo $value['carrier_base'];?>
                                </h6>
                                <!-- Carrier details -->
                                <div class="col-12 p-0" style="display:none">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-user"></i>
                                                </td>
                                                <td>
                                                    <?php echo $value['carrier_person'];?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-phone">
                                                </td>
                                                <td>
                                                    <?php echo $value['carrier_phone'];?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-envelope"></i>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?php echo $value['carrier_email'];?>"><?php echo $value['carrier_email'];?></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <p class="mb-0">Pojazdy: <?php echo $value['carrier_trucks']; ?></p>

                                    <input type="hidden" name="carrierID" value="<?php echo $value['id'];?>">
                                    <input type="hidden" name="carrierUUID" value="<?php echo $value['carrier_uuid'];?>">
                                    <input class="btn btn-success btn-sm" type="submit" name="editCarrier" value="Edytuj / Dodaj">
                                </div>
                            </div>

                        </div>
                    </form>

                </div>

            <?php endforeach; ?>
        <?php endif; ?>
        <!-- end -->
</div>

<div class="row justify-content-center">
    <div class="col-4 text-center">
        <h3 class="text-center">Planowanie
        <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm">Launch</a>
        </h3>
    </div>

    <div class="col-12 text-center">
        <div class="col-4">
            <!-- Select plan -->
            <select id="choosePlan" class="browser-default custom-select">
                <?php if(!empty($plans) && $planName == "") : ?>
                    <option selected disabled>Wybierz plan</option>
                    <?php foreach($plans as $key => $value) : ?>
                        <option value="<?php echo $value['plan_uuid'];?>"><?php echo $value['plan_name'];?></option>
                    <?php endforeach;?>
                <?php endif; ?>

                <?php if(!empty($plans) && $planName != "") : ?>
                    <option selected disabled>Wybrane: <?php echo $planName; ?></option>
                    <?php foreach($plans as $key => $value) : ?>
                        <option value="<?php echo $value['plan_uuid'];?>"><?php echo $value['plan_name'];?></option>
                    <?php endforeach;?>
                <?php endif; ?>

            </select>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <?php if(!empty($trucks)) : ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Przewoźnik / baza</th>
                    <th>Kierowca</th>
                    <th>Nr. pojazdu</th>
                    <th>Typ</th>
                    <th>Waga</th>
                    <th>LDM</th>
                    <th>Wys.</th>
                </tr>
            </thead>
        <?php foreach($trucks as $key => $value) : ?>
            <tr>
                <?php if($c = Planning::getCarrier($value['related_with'])) : ?>
                    <td>
                        <?php echo $c['carrier_name']; ?>
                        <br>
                        <?php echo $c['carrier_base']; ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?php echo $value['driver_name']; ?>
                    <br>
                    tel. <?php echo $value['driver_phone']; ?>
                    <br>
                    id. <?php echo $value['driver_id']; ?>
                </td>
                <td><?php echo $value['truck_plate']; ?></td>
                <td><?php echo $value['truck_type']; ?></td>
                <td><?php echo $value['truck_weight']; ?></td>
                <td><?php echo $value['truck_ldm']; ?></td>
                <td><?php echo $value['truck_height']; ?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    <?php endif;?>
</div>



<!-- modal window for create new plan -->
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="manage_planning.php" method="POST">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Dodaj nowy plan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <input type="text" id="defaultForm-email" name="planName" class="form-control validate">
                        <label data-error="wrong" data-success="right" for="defaultForm-email">Nazwa planu</label>
                        <i>Po dodaniu nowego planu, wejdź w konto przewoźnika i dodaj wybrane pojazdy do planu.</i>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <input type="submit" name="addPlan" value="Dodaj">
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'footer.php';?>