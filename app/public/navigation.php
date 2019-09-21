
<header>

  <nav class="navbar navbar-expand-lg navbar-dark green darken-1 scrolling-navbar">

    <a class="navbar-brand" href="#"><strong>Navbar</strong></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>

        <li class="nav-item">
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