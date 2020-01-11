<?php 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg_status = $session->status;
$msg        = $session->message;

$id = $_SESSION['user_id'];
$user = new User();
$user->getUser($id);

// Request for changes user password
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['changeUserPassword'])){
    if($user->changePassword($_POST['oldPass'], $_POST['newPass'], $_POST['confNewPass'])){
        $session->message("Hasło zmienione poprawnie", "success");
        redirect("user_profile.php");
    }else{
        $session->message($user->err[0], "error");
        redirect("user_profile.php");
    }
}
// Save general user data
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editUserData'])){
    $user->firstName = $_POST['firstName'];
    $user->lastName  = $_POST['lastName'];
    $user->email     = $_POST['email'];
    $user->phone     = $_POST['phone'];
    if($user->setUser()){
        $session->message("Dane zmienione poprawnie", "success");
        redirect("user_profile.php");
    }else{
        $session->message($user->err[0], "error");
        redirect("user_profile.php");
    }
}
// Update data for Trans connection
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editTransConnection'])){
    $user->trans_id     = $_POST['trans_id'];
    $user->trans_pass   = $_POST['transPassword'];
    if( $user->changeTransData()){
        $session->message("Dane zmienione poprawnie", "success");
        redirect("user_profile.php");
    }else{
        $session->message($user->err[0], "error");
        redirect("user_profile.php");
    }
}
// Delete account
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteUser'])){
    if($user->deleteUser($_POST['deleteAccount'])){
        $session->logout();
        $session->message("Konto zostało usunięte", "success");
        redirect("index.php");
    }
}
?>

<!-- Display system messages -->
<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Panel użytkownika</h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>
<!-- Edit general data -->
<div class="row justify-content-center">

    <!-- Form for change basic user data -->
    <div class="col-4">
    <button id="toogleChangeUserData" class="btn btn-success my-4 btn-block green darken-1">Edytuj dane</button>

        <form id="changeUserData" class="text-center border border-light p-5" style="display:none;" method="POST" action="user_profile.php">

            <!-- First name -->
            <div class="form-row mb-4">
                <label for="editFirstName">Imię</label>
                <input type="text" id="editFirstName" name="firstName" class="form-control border border-success" value="<?php echo $user->firstName; ?>" required>
            </div>

            <!-- Last name -->
            <div class="form-row mb-4">
                <label for="editLastName">Nazwisko</label>
                <input type="text" id="editLastName" name="lastName" class="form-control border border-success" value="<?php echo $user->lastName; ?>" required>
            </div>

            <!-- Email -->
            <div class="form-row mb-4">
                <label for="editEmail">E-mail</label>
                <input type="email" id="editEmail" name="email" class="form-control border border-success" value="<?php echo $user->email; ?>" required>
            </div>

            <!-- Phone -->
            <div class="form-row mb-4">
                <label for="editPhone">Numer telefonu</label>
                <input type="text" id="editPhone" phone="phone" name="phone" class="form-control border border-success" value="<?php echo $user->phone; ?>">
                <small id="editPhone" class="form-text text-muted mb-4">
                    Wymagany przy korzystaniu z bramki SMS
                </small>
            </div>

            <!-- Sign up button -->
            <input class="btn my-4 btn-success btn-block green darken-1" name="editUserData" type="submit" value="Zapisz zmiany">

        </form>
    <!-- End -->
    </div>

</div>
<!-- Edit Trans connection -->
<div class="row justify-content-center">

    <!-- Form for connect account with Trans -->
    <div class="col-4">
        <button id="toogleChangeTransData" class="btn btn-success my-4 btn-block green darken-1">Połącz z platformą Trans</button>

        <form id="changeTransData" class="text-center border border-light p-5" style="display:none;" method="POST" action="user_profile.php">

            <?php if($user->trans_id != "" && $user->trans_pass != "") : ?>
                <p class="text-info font-weight-bold">Trans id oraz hasło zostały zapisane</p>
            <?php endif; ?>

            <!-- Trans ID -->
            <div class="form-row mb-4">
                <label for="editTransID">Trans ID</label>
                <input type="text" id="editTransID" name="trans_id" class="form-control border border-success" value="<?php echo $user->trans_id; ?>">
            </div>

            <!-- Trans Password -->
            <div class="form-row mb-4">
                <label for="editTransPasword">Hasło</label>
                <input type="password" id="editTransPasword" name="transPassword" class="form-control border border-success">
                <small id="editTransPasword" class="form-text text-muted mb-4">
                    Trans-id oraz hasło są wymagane w przypadku wystawiania ładunków 
                    za pośrednictwem tej platformy
                </small>
            </div>
            <!-- Sign up button -->
            <input class="btn my-4 btn-success btn-block green darken-1" name="editTransConnection" type="submit" value="Zapisz zmiany">

        </form>
        <!-- End -->
    </div>

</div>
<!-- Edit password -->
<div class="row justify-content-center">

    <!-- Change password -->
    <div class="col-4">
    <button id="toogleChangePassword" class="btn btn-success my-4 btn-block green darken-1">Zmień hasło</button>

        <form id="changePassword" class="text-center border border-light p-5" style="display:none;" method="POST" action="user_profile.php">

            <!-- Old Password -->
            <div class="form-row mb-4">
                <label for="editPass">Stare hasło</label>
                <input type="password" id="editPass" name="oldPass" class="form-control border border-success">
            </div>

            <!-- New Password -->
            <div class="form-row mb-4">
                <label for="editNewPass">Nowe hasło</label>
                <input type="password" id="editNewPass" name="newPass" class="form-control border border-success">
            </div>

            <!-- Confirm Password -->
            <div class="form-row mb-4">
                <label for="confNewPass">Potwierdź nowe hasło</label>
                <input type="password" id="confNewPass" name="confNewPass" class="form-control border border-success">
                <small id="confNewPass" class="form-text text-muted mb-4">
                    Hasło musi zawierać minimum 8 znaków
                </small>
            </div>
            <!-- Sign up button -->
            <button class="btn my-4 btn-success btn-block green darken-1" name="changeUserPassword" type="submit">Zapisz zmiany</button>

        </form>
        <!-- End -->
    </div>

</div>
<!-- Delete account -->
<div class="row justify-content-center">

    <!-- Delete Account -->
    <div class="col-4">
    <button id="toggleDeleteAccount" class="btn btn-warning my-4 btn-block">Usuń konto</button>

        <form id="deleteAccount" class="text-center border border-light p-5" style="display:none;" method="POST" action="">

            <!-- Confirm Password -->
            <div class="form-row mb-4">
                <label for="delteUser">Wpisz hasło</label>
                <input type="password" id="delteUser" name="deleteAccount" class="form-control border border-warning" required>
                <small id="delteUser" class="form-text text-muted mb-4">
                    Przywrócenie konta nie będzie możliwe
                </small>
            </div>
            <!-- Sign up button -->
            <input class="btn my-4 btn-warning btn-block" name="deleteUser" type="submit" value="Usuń konto">

        </form>
        <!-- End -->
    </div>

</div>

<?php require 'footer.php';?>