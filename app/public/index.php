<?php 
require 'header.php'; 

$session->displayMessage();
$msg    = $session->message;
$errors = [];

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login-submit'])){
    $user = User::verifyUser($_POST['email'], $_POST['password']);
    if($user == 1){
        redirect("dashboard.php");
    }else{
        foreach($user->err as $e){
            $errors[] = $e;
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register-submit'])){
    // Temporary store for inputs
    $_SESSION['tmp_firstName']      = $_POST['first_name'];
    $_SESSION['tmp_lastName']       = $_POST['last_name'];
    $_SESSION['tmp_email']          = $_POST['email'];
    $_SESSION['tmp_confirmEmail']   = $_POST['confirm_email'];

    // Create new user
    $user = new User();
    $user->firstName   = $_POST['first_name'];
    $user->lastName    = $_POST['last_name'];

    if(User::compareData($_POST['email'], $_POST['confirm_email'])){
        $user->email = $_POST['email'];
    }else{
        $errors[] = "Adresy email nie są takie same";
    }

    if(User::compareData($_POST['password'], $_POST['confirm_password'])){
        $user->password = $_POST['password'];
    }else{
        $errors[] = "Hasła nie są takie same";
    }

    if(empty($errors)){
        if($user->validateNewUser()){
            global $session;
            unset($_SESSION['tmp_firstName'], $_SESSION['tmp_lastName'], $_SESSION['tmp_email'],$_SESSION['tmp_confirmEmail']);
            $session->message("Na podany adres został wysłany link aktywacyjny");
            redirect("index.php");
        }else{
            foreach($user->err as $e){
                $errors[] = $e;
            }
        }
    }
}


?>

<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Platforma do zarządania ładunkami</h3>
        <?php 
        if(!empty($errors)){
            foreach($errors as $v){
                Session::throwMessage("error", $v);
            }
        } 
        if(!empty($msg)){
            Session::throwMessage("success", $msg);
        }
        ?>
    </div>
</div>

<div class="row justify-content-center">

    <div class="col-4">

        <!-- Default form login -->
        <form class="text-center border border-light  p-5" method="POST">

            <p class="h4 mb-4">Zaloguj się</p>

            <!-- Email -->
            <input type="email" class="form-control mb-4" name="email" placeholder="E-mail" required>

            <!-- Password -->
            <input type="password" class="form-control mb-4" name="password" placeholder="Password" required>

            <div class="d-flex justify-content-around">
                <div>
                    <!-- Forgot password -->
                    <a href="">Forgot password?</a>
                </div>
            </div>

            <!-- Sign in button -->
            <button class="btn btn-info green darken-1 btn-block my-4" name="login-submit">Zaloguj</button>
    
        </form>
        <!-- Default form login -->

    </div>






    <div class="col-4">
        <!-- Default form register -->
        <form class="text-center border border-light p-5" method="POST">

            <p class="h4 mb-4">Zarejestruj się</p>

            <div class="form-row mb-4">
                <div class="col">
                    <!-- First name -->
                    <input type="text" class="form-control" name="first_name" placeholder="First name" value="<?php if(isset($_SESSION['tmp_firstName'])){ echo $_SESSION['tmp_firstName'];}?>">
                </div>
                <div class="col">
                    <!-- Last name -->
                    <input type="text" class="form-control" name="last_name" placeholder="Last name" value="<?php if(isset($_SESSION['tmp_lastName'])){ echo $_SESSION['tmp_lastName'];}?>">
                </div>
            </div>

            <!-- E-mail -->
            <input type="email" id="defaultRegisterFormEmail" class="form-control mb-4" name="email" placeholder="E-mail" value="<?php if(isset($_SESSION['tmp_email'])){ echo $_SESSION['tmp_email'];}?>"  required>

            <!-- Confirm Email -->
            <input type="email" class="form-control mb-4" name="confirm_email" placeholder="E-mail" value="<?php if(isset($_SESSION['tmp_confirmEmail'])){ echo $_SESSION['tmp_confirmEmail'];}?>" required>

            <!-- Password -->
            <input type="password" id="defaultRegisterFormPassword" class="form-control" name="password" placeholder="Password" required>
            <small id="defaultRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                Minimum 8 znaków
            </small>

            <!-- Confirm Password -->
            <input type="password" class="form-control mb-4" name="confirm_password" placeholder="Password" required>

            <!-- Sign up button -->
            <button class="btn btn-info green darken-1 my-4 btn-block" name="register-submit">Zarejestruj</button>

        </form>
        <!-- Default form register -->
    </div>

<!-- End div -->
</div>



<?php require 'footer.php';?>