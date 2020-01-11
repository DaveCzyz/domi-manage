<?php 
require 'header.php'; 

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Get user fleet

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
<div class="row justify-content-center" id="loadGroup">
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
                            <label for="carrierContact" class="text-left">Osoba kontaktowa</label>
                            <input type="text" id="carrierContact" name="carrierContact" class="form-control" required>
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
