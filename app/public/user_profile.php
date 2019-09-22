<?php 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg_status = $session->status;
$msg        = $session->message;


if(isset($_SESSION['user_id'])){
    $id = $_SESSION['user_id'];
    $user = new User();
    $userData = $user->getUser($id);

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editTransConnection'])){
        // dodanie transa
    }

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

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirmDeleteUser'])){
        // do usuniecia konta
    }

}


?>

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

<div class="row justify-content-center">

    <!-- Form for change basic user data -->
    <div class="col-4">
    <button id="toogleChangeUserData" class="btn btn-success my-4 btn-block green darken-1">Edytuj dane</button>

        <form id="changeUserData" class="text-center border border-light p-5" style="display:none;" action="">

            <!-- First name -->
            <div class="form-row mb-4">
                <label for="editFirstName">Imię</label>
                <input type="text" id="editFirstName" name="firstName" class="form-control border border-success" value="<?php echo $userData['firstName']; ?>">
            </div>

            <!-- Last name -->
            <div class="form-row mb-4">
                <label for="editLastName">Nazwisko</label>
                <input type="text" id="editLastName" name="lastName" class="form-control border border-success" value="<?php echo $userData['lastName']; ?>">
            </div>

            <!-- Email -->
            <div class="form-row mb-4">
                <label for="editEmail">E-mail</label>
                <input type="email" id="editEmail" name="email" class="form-control border border-success" value="<?php echo $userData['email']; ?>">
            </div>

            <!-- Phone -->
            <div class="form-row mb-4">
                <label for="editPhone">Numer telefonu</label>
                <input type="text" id="editPhone" phone="phone" class="form-control border border-success" value="<?php echo $userData['phone']; ?>">
                <small id="editPhone" class="form-text text-muted mb-4">
                    Wymagany przy korzystaniu z bramki SMS
                </small>
            </div>

            <!-- Sign up button -->
            <button class="btn my-4 btn-success btn-block green darken-1" name="editUserGeneralChanges" type="submit">Zapisz zmiany</button>

        </form>
    <!-- End -->
    </div>

</div>

<div class="row justify-content-center">

    <!-- Form for connect account with Trans -->
    <div class="col-4">
    <button id="toogleChangeTransData" class="btn btn-success my-4 btn-block green darken-1">Połącz z platformą Trans</button>

        <form id="changeTransData" class="text-center border border-light p-5" style="display:none;" action="">

            <!-- Trans ID -->
            <div class="form-row mb-4">
                <label for="editTransID">Trans ID</label>
                <input type="text" id="editTransID" name="trans_id" class="form-control border border-success" value="<?php echo $userData['trans_id']; ?>">
            </div>

            <!-- Trans Password -->
            <div class="form-row mb-4">
                <label for="editTransPasword">Nazwisko</label>
                <input type="text" id="editTransPasword" name="transPassword" class="form-control border border-success" value="<?php echo $userData['trans_id']; ?>">
                <small id="editTransPasword" class="form-text text-muted mb-4">
                    Trans-id oraz hasło są wymagane w przypadku wystawiania ładunków 
                    za pośrednictwem tej platformy
                </small>
            </div>
            <!-- Sign up button -->
            <button class="btn my-4 btn-success btn-block green darken-1" name="editTransConnection" type="submit">Zapisz zmiany</button>

        </form>
        <!-- End -->
    </div>

</div>

<div class="row justify-content-center">

    <!-- Change password -->
    <div class="col-4">
    <button id="toogleChangePassword" class="btn btn-success my-4 btn-block green darken-1">Zmień hasło</button>

        <form id="changePassword" class="text-center border border-light p-5" style="display:none;" method="POST" action="">

            <!-- Old Password -->
            <div class="form-row mb-4">
                <label for="editPass">Stare hasło</label>
                <input type="text" id="editPass" name="oldPass" class="form-control border border-success" value="<?php echo $userData['trans_id']; ?>">
            </div>

            <!-- New Password -->
            <div class="form-row mb-4">
                <label for="editNewPass">Nowe hasło</label>
                <input type="text" id="editNewPass" name="newPass" class="form-control border border-success" value="<?php echo $userData['trans_id']; ?>">
            </div>

            <!-- Confirm Password -->
            <div class="form-row mb-4">
                <label for="confNewPass">Potwierdź nowe hasło</label>
                <input type="text" id="confNewPass" name="confNewPass" class="form-control border border-success" value="<?php echo $userData['trans_id']; ?>">
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

<div class="row justify-content-center">

    <!-- Delete Account -->
    <div class="col-4">
    <button id="toggleDeleteAccount" class="btn btn-warning my-4 btn-block">Usuń konto</button>

        <form id="deleteAccount" class="text-center border border-light p-5" style="display:none;" action="">

            <!-- Confirm Password -->
            <div class="form-row mb-4">
                <label for="delteUser">Wpisz hasło</label>
                <input type="password" id="delteUser" name="deleteAccount" class="form-control border border-warning" value="<?php echo $userData['trans_id']; ?>">
                <small id="delteUser" class="form-text text-muted mb-4">
                    Przywrócenie konta nie będzie możliwe
                </small>
            </div>
            <!-- Sign up button -->
            <button class="btn my-4 btn-warning btn-block" name="confirmDeleteUser" type="submit">Usuń konto</button>

        </form>
        <!-- End -->
    </div>

</div>


<?php require 'footer.php';?>