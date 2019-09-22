
<header>

  <nav class="navbar navbar-expand-lg navbar-dark green darken-1 scrolling-navbar">

    <a class="navbar-brand" href="#"><strong>Navbar</strong></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "dashboard.php"){ echo "active"; } ?>">
          <a class="nav-link" href="dashboard.php">Home</a>
        </li>

        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "fleet.php"){ echo "active"; } ?>">
          <a class="nav-link" href="fleet.php">Flota</a>
        </li>

        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "carriers.php"){ echo "active"; } ?>">
          <a class="nav-link" href="carriers.php">Baza przewoźników</a>
        </li>

        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "loads.php"){ echo "active"; } ?>">
          <a class="nav-link" href="loads.php">Ładunki</a>
        </li>

        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "load_offer.php"){ echo "active"; } ?>">
          <a class="nav-link" href="load_offer.php">Wystaw ładunek</a>
        </li>

        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "currency.php"){ echo "active"; } ?>">
          <a class="nav-link" href="currency.php">Kursy NBP</a>
        </li>

      </ul>

      <!-- Sign In / Log out buttons -->
      <ul class="navbar-nav nav-flex-icons">
          <?php if(!$session->isLogged()) : ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php">Zaloguj</a>
            </li>
          <?php endif; ?>

        <?php if($session->isLogged()) : ?>
          <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == "user_profile.php"){ echo "active"; } ?>">
            <a class="nav-link" href="user_profile.php">Panel użytkownika</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Wyloguj</a>
          </li>
        <?php endif; ?>
      </ul>
      <!-- end -->
      
    </div>
  </nav>

</header>
<!--Main Navigation-->