<?php 
require 'header.php'; 

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

$carrier;
$truck;

// Get carrier and store in session
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editCarrier'])){
    if(isset($_POST['carrierID']) && isset($_POST['carrierUUID'])){
        Session::manageCarrier($_POST['carrierID'], $_POST['carrierUUID']);
        $uuid = $_POST['carrierUUID'];
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("fleet.php");
    }

    $carrier = new Carrier($user->id);
    $carrier->getCarrier($uuid);
}
// Resotre object from session
if(empty($carrier)){
    $carrier = new Carrier($user->id);
    $carrier->getCarrier($_SESSION['carrier_uuid']);
}
// Restore carrier trucks (display all carrier trucks)
if(!empty($carrier)){
    $truck = Fleet::getAllTrucks($carrier->carrier_uuid);
}
// Edit carrier
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['saveCarrier'])){
    $carrier->carrier_name      = $_POST['carrier_name'];
    $carrier->carrier_base      = $_POST['carrier_base'];
    $carrier->carrier_person    = $_POST['carrier_person'];
    $carrier->carrier_phone     = $_POST['carrier_phone'];
    $carrier->carrier_email     = $_POST['carrier_email'];

    if($carrier->editCarrier()){
        $session->message("Dane zmienione poprawnie", "success");
        redirect("manage_carriers.php");
    }else{
        $session->message($carrier->err[0], "error");
        redirect("manage_carriers.php");
    }
}
// Cancel saving changes
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cancelSaving'])){
    Session::clearManageCarrier();
    redirect("fleet.php");
}
// Delete carrier
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteCarrier'])){
    if($carrier->deleteCarrier()){
        if(Fleet::deleteAllTrucks($carrier->carrier_uuid)){
            // Delete all trucks
            Session::clearManageCarrier(); // clear carrier session
            $session->message("Przewoźnik został usunięty", "success");
            redirect("fleet.php");
        }
    }else{
        $session->message($carrier->err[0], "error");
        redirect("manage_carriers.php");
    }
}
// Add new Truck
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addNewTruck'])){
    $truck = new Fleet($carrier->carrier_uuid, $user->id);
    $truck->driver_name     = $_POST['driver_name'];
    $truck->driver_phone    = $_POST['driver_phone'];
    $truck->driver_id       = $_POST['driver_id'];
    $truck->truck_type      = $_POST['truck_type'];
    $truck->truck_ldm       = $_POST['capacity_length'];
    $truck->truck_weight    = $_POST['capacity_weight'];
    $truck->truck_height    = $_POST['capacity_height'];
    $truck->truck_plate     = $_POST['truck_plates'];

    if($truck->setTruck()){
        $carrier->updateCounter("plus");
        $session->message("Pojazd został dodany", "success");
        redirect("manage_carriers.php");
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("manage_carriers.php");
    }
}
// Edit truck
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['edit'])){
    $truckNubmer = (int)$_GET['edit'];
    $getTruck = new Fleet($carrier->carrier_uuid, $user->id);
    $getTruck->getTruck($truckNubmer);
}
// Save changes in truck
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['saveChanges'])){
    $id = (int)$_POST['carrier_id'];
    $t  = new Fleet($carrier->carrier_uuid, $user->id);
    if($t->getTruck($id)){
        // Update data
        $t->driver_name     = $_POST['driver_name'];
        $t->driver_phone    = $_POST['driver_phone'];
        $t->driver_id       = $_POST['driver_id'];
        $t->truck_type      = $_POST['truck_type'];
        $t->truck_ldm       = $_POST['capacity_length'];
        $t->truck_weight    = $_POST['capacity_weight'];
        $t->truck_height    = $_POST['capacity_height'];
        $t->truck_plate     = $_POST['truck_plates'];

        if($t->editTruck()){
            $session->message("Zmiany zostały zapisane", "success");
            redirect("manage_carriers.php");
        }else{
            $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
            redirect("manage_carriers.php");
        }

    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("manage_carriers.php");
    }
}
// Delete truck
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $t  = new Fleet($carrier->carrier_uuid, $user->id);
    $t->getTruck($id);
    if($t->deleteTruck()){
        if($carrier->updateCounter("minus")){
            $session->message("Pojazd został usunięty", "success");
            redirect("manage_carriers.php");
        }else{
            $session->message("Wystąpił błąd. Dane przewoźnika nie zostały zaktualizowane", "error");
            redirect("manage_carriers.php");
        }
    }else{
        $session->message("Wystąpił błąd. Pojazd nie został usunięty", "error");
        redirect("manage_carriers.php");
    }
}

?>

<!-- Display system messages -->
<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Edytuj przewoźnika</h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>


<div class="row justify-content-center">
    <!-- Display carrier details -->
    <div class="col-3">

            <!-- Card -->
            <div class="card">

                <!-- Card body -->
                <div class="card-body">

                    <!-- Carrier card -->
                    <form action="manage_carriers.php" method="POST">
                        <p class="h4 text-center py-4">Edytuj grupę <input class="btn btn-danger btn-sm" name="deleteCarrier" type="submit" value="Usuń"></p>

                        <!-- Hidden input for ID -->
                        <input type="hidden" name="carrier_id" value="<?php echo $carrier->id;?>">
                        <!-- Hidden input for Load_ID -->
                        <input type="hidden" name="carrier_uuid" value="<?php echo $carrier->carrier_uuid;?>">

                        <!-- Carrier name -->
                        <div class="md-form">
                            <input type="text" id="editCarrier" name="carrier_name" class="form-control" value="<?php echo $carrier->carrier_name; ?>">
                            <label for="editCarrier" class="font-weight-light active">Nazwa przewoźnika</label>
                        </div>

                        <!-- Carrier base -->
                        <div class="md-form">
                            <input type="text" id="carrierBase" name="carrier_base" class="form-control" value="<?php echo $carrier->carrier_base; ?>">
                            <label for="carrierBase" class="font-weight-light active">Miasto </label>
                        </div>

                        <!-- Carrier Person -->
                        <div class="md-form">
                            <label for="carrierPerson" class="font-weight-light active">Os. kontaktowa </label>
                            <input type="text" id="carrierPerson" name="carrier_person" class="form-control" value="<?php echo $carrier->carrier_person; ?>">
                        </div>

                        <!-- Carrier Phone    -->
                        <div class="md-form">
                            <input type="text" id="carrierPhone" name="carrier_phone" class="form-control" value="<?php echo $carrier->carrier_phone; ?>">
                            <label for="carrierPhone" class="font-weight-light active">Telefon </label>
                        </div>

                        <!-- Carrier Email    -->
                        <div class="md-form">
                            <label for="carrierEmail" class="font-weight-light active">Email </label>
                            <input type="text" id="carrierEmail" name="carrier_email" class="form-control" value="<?php echo $carrier->carrier_email; ?>">
                        </div>

                        <div class="text-center py-4 mt-3">
                            <input class="btn btn-success green darken-1 btn-sm" name="saveCarrier" type="submit" value="Zapisz zmiany">
                            <button class="btn btn-warning btn-sm" name="cancelSaving" type="submit">Anuluj</button>
                        </div>
                    </form>
                    <!-- Material form register -->
                </div>
                <!-- Card body -->
            </div>
            <!-- Card -->
    </div><!-- end col-5 -->

    <!-- Display added trucks -->
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <!-- Card Title -->
                <p class="h4 text-center py-4">Pojazdy powiązne z przewoźnikiem 
                    <?php if(!isset($_GET['editCarrier'])) : ?>
                        <button id="addNewTruck" class="btn btn-success green darken-1 btn-sm" >Dodaj</button>
                    <?php endif; ?>
                </p>

                <!-- Add / Edit new truck -->
                <div class="row justify-content-center" id="newTruck" 
                    <?php if(!isset($_GET['edit'])){ echo "style='display:none'"; }; ?> > 
                    <div class="col-8">

                        <!-- form for add new load -->
                        <form action="manage_carriers.php" method="POST">
                            <p class="h5 mb-4 text-center">
                                <?php if(isset($_GET['edit'])) : ?>
                                    Edytuj pojazd
                                <?php else : ?>
                                    Dodaj nowy pojazd
                                <?php endif; ?>
                            </p>

                            <!-- truck uuid -->
                            <?php if(isset($_GET['edit'])) : ?>
                                <input type="hidden" name="carrier_id" value="<?php echo $getTruck->id; ?>">
                            <?php endif; ?>

                            <!-- Driver name -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Origin city -->
                                    <label for="driverName" class="text-left">Kierowca </label>
                                    <input type="text" id="driverName" name="driver_name" class="form-control"
                                        <?php if(isset($_GET['edit'])){ echo "value=".$getTruck->driver_name; };?>
                                    >
                                </div>
                            </div>

                            <!-- Driver Contact -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Driver ID -->
                                    <label for="driverID" class="text-left">Numer dowodu</label>
                                    <input type="text" id="driverID" name="driver_id" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->driver_id."'"; } ?> >
                                </div>
                                <div class="col">
                                    <!-- Driver Phone-->
                                    <label for="driverPhone">Telefon</label>
                                    <input type="text" id="driverPhone" name="driver_phone" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->driver_phone."'"; } ?> >
                                </div>
                            </div>

                            <!-- Truck plates -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <label for="truckPlates" class="text-left">Numery rejestracyjne </label>
                                    <input type="text" id="truckPlates" name="truck_plates" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->truck_plate."'"; } ?> required>
                                </div>
                            </div>
                            
                            <!-- Capacity -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Weight -->
                                    <label for="capacityWeight" class="text-left">Ładowność (t)</label>
                                    <input type="text" id="capacityWeight" name="capacity_weight" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->truck_weight."'"; } ?> required>
                                </div>
                                <div class="col">
                                    <!-- Length -->
                                    <label for="capacityLength">Ładowność (m)</label>
                                    <input type="text" id="capacityLength" name="capacity_length" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->truck_ldm."'"; } ?> required>
                                </div>
                            </div>


                            <!-- Details -->
                            <div class="form-row mb-4">
                                <div class="col">
                                    <!-- Truck type -->
                                    <label for="truckType" class="text-left">Rodzaj</label>
                                    <input type="text" id="truckType" name="truck_type" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->truck_type."'"; } ?> required>
                                </div>
                                <div class="col">
                                    <!-- Truck height -->
                                    <label for="capacityHeight">Wysokość (m)</label>
                                    <input type="text" id="capacityHeight" name="capacity_height" class="form-control" 
                                    <?php if(isset($_GET['edit'])){ echo "value='".$getTruck->truck_height."'"; } ?> required>
                                </div>
                            </div>


                            <div class="text-center"> 
                                <!-- Sign in button -->
                                <?php if(isset($_GET['edit'])) : ?>
                                    <input class="btn btn-success green darken-1 btn-sm" name="saveChanges" type="submit" value="Zapisz zmiany">
                                    <input class="btn btn-warning btn-sm" name="cancelSaveChanges" type="submit" value="Anuluj">
                                <?php else : ?>
                                    <input class="btn btn-success green darken-1 btn-sm" name="addNewTruck" type="submit" value="Dodaj">
                                    <button id="cancelNewTruck" class="btn btn-warning btn-sm">Anuluj</button>
                                <?php endif; ?>
                            </div>  

                        </form><!-- end form -->
                    </div><!-- ned col-4 -->
                </div><!-- end row -->

                <!-- If truck doesnt exists - do not show anythig -->
                <?php if($carrier->carrier_trucks == "0" && empty($truck)) : ?>
                    <p class="text-center">Brak</p>
                <?php endif; ?>
            
                <!-- If trucks exist - display all those truck -->
                <?php if(!empty($truck) && is_array($truck)) : ?>

                    <table class="table table-borderless">
                        <tr>
                            <th>Lp.</th>
                            <th>Kierowca</th>
                            <th>Telefon</th>
                            <th>Nr. dowodu</th>
                            <th>Pojazd</th>
                            <th>Nr. pojazdu</th>
                            <th>Długość</th>
                            <th>Waga</th>
                            <th>Wys. naczepy</th>
                        </tr>
                            
                        <?php for($i = 0; $i < count($truck); ++$i) : ?>
                        <tr class="border-top border-bottom">
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $truck[$i]['driver_name'] ;?></td>
                            <td><?php echo $truck[$i]['driver_phone'] ;?></td>
                            <td><?php echo $truck[$i]['driver_id'] ;?></td>
                            <td><?php echo $truck[$i]['truck_type'] ;?></td>
                            <td><?php echo $truck[$i]['truck_plate'] ;?></td>
                            <td><?php echo $truck[$i]['truck_ldm'] ;?></td>
                            <td><?php echo $truck[$i]['truck_weight'] ;?></td>
                            <td><?php echo $truck[$i]['truck_height'] ;?></td>
                        </tr>
   
                        <tr>
                            <th scope="row"></th>
                            <td><a href="manage_carriers.php?edit=<?php echo $truck[$i]['id'] ;?>" class="btn btn-success green darken-1 btn-sm">Edytuj</a></td>
                            <td><a href="manage_carriers.php?delete=<?php echo $truck[$i]['id'] ;?>" class="btn btn-danger btn-sm">Usuń</a></td>
                        </tr>
                        <?php endfor; ?>

                    </table>
                <?php endif; ?>

            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-5 -->

</div><!-- end row -->



<?php require 'footer.php';?>