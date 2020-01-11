<?php 
require 'header.php'; 

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Add new carrier
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addCarrier'])){
    $carrier = new Carrier($user->id);

    $carrier->carrier_name      = $_POST['carrierName'];
    $carrier->carrier_base      = $_POST['carrierBase'];
    $carrier->carrier_person    = $_POST['carrierPerson'];
    $carrier->carrier_phone     = $_POST['carrierPhone'];
    $carrier->carrier_email     = $_POST['carrierEmail'];

    if($carrier->setCarrier()){
        echo 'ok';
    }else{
        echo "nie ok";
    }


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

<!-- Add new carrier -->
<div class="row justify-content-center" id="newCarrier" style="display:none">
    <div class="col-8">
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
                <!-- Loads group form -->
            </div>
        </div>
    </div>
</div><!-- end -->

<?php require 'footer.php';?>