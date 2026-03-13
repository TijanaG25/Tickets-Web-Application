<?php session_start();
require_once('konekcija.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Česta pitanja</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<!--NAVBAR-->
<nav class="navbar navbar-expand-lg navbar-dark px-4">
  <a class="navbar-brand" href="index.php">
    <img src="images/logo.png" alt="Prime Tickets" class="d-inline-block align-text-top" height="50">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">KONCERTI</a>
        <ul class="dropdown-menu">
          <?php 
          $koncerti=['Pop','Rock','Dj','Hard and Heavy','Džez i bluz','Klasična muzika','Opera','Elektro','Narodna muzika','Hip hop','Svi događaji'];
          foreach($koncerti as $k) {
              echo "<li><a class='dropdown-item' href='koncerti.php?vrsta=".urlencode($k)."'>$k</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">POZORIŠTA</a>
        <ul class="dropdown-menu">
          <?php 
          $pozorista=['Balet','Dečije predstave','Komedije','Mjuzikli','One man show','Stand up','Predstava','Svi događaji'];
          foreach($pozorista as $p) {
              echo "<li><a class='dropdown-item' href='pozorista.php?vrsta=".urlencode($p)."'>$p</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">SPORT</a>
        <ul class="dropdown-menu">
          <?php 
          $sport=['Košarka','Vaterpolo','Svi događaji'];
          foreach($sport as $s) {
              echo "<li><a class='dropdown-item' href='sport.php?vrsta=".urlencode($s)."'>$s</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">FESTIVALI</a>
        <ul class="dropdown-menu">
          <?php 
          $festivali=['Muzika','Film','Svi događaji'];
          foreach($festivali as $f) {
              echo "<li><a class='dropdown-item' href='festivali.php?vrsta=".urlencode($f)."'>$f</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">MUZEJI</a>
        <ul class="dropdown-menu">
          <?php 
          $muzeji=['Istorija','Tura sa vodičem','Svi događaji'];
          foreach($muzeji as $m) {
              echo "<li><a class='dropdown-item' href='muzeji.php?vrsta=".urlencode($m)."'>$m</a></li>";
          }
          ?>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vesti.php">VESTI</a>
      </li>
      <li class="nav-item search-item position-relative">
          <a class="nav-link" href="#" id="searchToggle"><i class="bi bi-search"></i></a>
          <input type="text" id="searchInput" class="form-control position-absolute" placeholder="Pretraži..." />
      </li>
      <li class="nav-item dropdown">
        <?php
            if(isset($_SESSION['uloga'])) {
             if($_SESSION['uloga'] === 'administrator') {
                  echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i>Admin</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="admin.php">Admin panel</a></li>
                            <li><a class="dropdown-item" href="profil.php">Moj profil</a></li></ul>';
                } 
            else {
                echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i>Profil</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php">Moj profil</a></li>
                    </ul>';
            }
            } 
            else {
                echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i></a>
                <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="prijava.php">Prijava</a></li>
                        <li><a class="dropdown-item" href="registracija.php">Registracija</a></li>
                </ul>';
            }
        ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="korpa.php"><i class="bi bi-cart-fill"></i></a>
      </li>
    </ul>
  </div>
</nav>
<section class="container py-5">
    <div class="text-center mb-5">
        <h1 class="text-warning fw-bold">Često postavljana pitanja</h1>
    </div>
    <div class="accordion accordion-flush" id="faqAccordion">
        <div class="accordion-item bg-dark text-light border-warning">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark text-warning" type="button" data-bs-toggle="collapse" data-bs-target="#q1">Kako mogu da kupim kartu?</button>
            </h2>
            <div id="q1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">Odaberite događaj, kliknite na dugme "Kupi kartu", izaberite mesto i završite kupovinu.</div>
            </div>
        </div>
        <div class="accordion-item bg-dark text-light border-warning">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark text-warning" type="button"data-bs-toggle="collapse"data-bs-target="#q2">Da li mogu da otkažem kartu?</button>
            </h2>
            <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">Povraćaj novca zavisi od organizatora događaja.Molimo vas da proverite uslove prilikom kupovine.</div>
            </div>
        </div>
        <div class="accordion-item bg-dark text-light border-warning">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark text-warning"type="button"data-bs-toggle="collapse" data-bs-target="#q3">Kako preuzimam kartu?</button>
            </h2>
            <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">Nakon kupovine, karta će biti preuzeta na vaš uređaj</div>
            </div>
        </div>
        <div class="accordion-item bg-dark text-light border-warning">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark text-warning"type="button"data-bs-toggle="collapse"data-bs-target="#q4">Da li je kupovina sigurna?</button>
            </h2>
            <div id="q4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body"> Da. Prime Tickets koristi sigurne protokole zaštite podataka i enkripciju plaćanja.
                </div>
            </div>
        </div>
    </div>
</section>
<!--FOOTER-->
<footer class="footer mt-5">
 <div class="footer-sponsors">
  <div class="sponsor-track">
    <a href="https://www.bancaintesa.rs/"><img src="images/intesa.jpg" alt="Sponsor 1"></a>
    <a href="https://mts.rs/"><img src="images/mts.png" alt="Sponsor 2"></a>
    <a href="https://www.posted.co.rs/"><img src="images/postanska.png" alt="Sponsor 3"></a>
    <a href="https://www.raiffeisenbank.rs/sr/stanovnistvo.html"><img src="images/raiffeisen.png" alt="Sponsor 4"></a>
    <a href="https://tickets.rs"><img src="images/tickets.png" alt="Sponsor 5"></a>
    <a href="https://www.yettel.rs/"><img src="images/yettel.png" alt="Sponsor 6"></a>
    <a href="https://www.bancaintesa.rs/"><img src="images/intesa.jpg" alt="Sponsor 1"></a>
    <a href="https://mts.rs/"><img src="images/mts.png" alt="Sponsor 2"></a>
    <a href="https://www.posted.co.rs/"><img src="images/postanska.png" alt="Sponsor 3"></a>
    <a href="https://www.raiffeisenbank.rs/sr/stanovnistvo.html"><img src="images/raiffeisen.png" alt="Sponsor 4"></a>
    <a href="https://tickets.rs"><img src="images/tickets.png" alt="Sponsor 5"></a>
    <a href="https://www.yettel.rs/"><img src="images/yettel.png" alt="Sponsor 6"></a>
    <a href="https://www.bancaintesa.rs/"><img src="images/intesa.jpg" alt="Sponsor 1"></a>
    <a href="https://mts.rs/"><img src="images/mts.png" alt="Sponsor 2"></a>
    <a href="https://www.posted.co.rs/"><img src="images/postanska.png" alt="Sponsor 3"></a>
    <a href="https://www.raiffeisenbank.rs/sr/stanovnistvo.html"><img src="images/raiffeisen.png" alt="Sponsor 4"></a>
    <a href="https://tickets.rs"><img src="images/tickets.png" alt="Sponsor 5"></a>
    <a href="https://www.yettel.rs/"><img src="images/yettel.png" alt="Sponsor 6"></a>
  </div>
</div>
  <div class="footer-content container py-4 text-light">
    <div class="row">
      <div class="col-md-6 mb-3">
        <h5>Linkovi</h5>
        <ul class="list-unstyled">
          <li><a href="oNama.php" class="footer-link" style="color: #f1d66c !important;text-decoration: none !important;"><strong>O nama</strong></a></li>
          <li><a href="faq.php" class="footer-link" style="color: #f1d66c !important;text-decoration: none !important;"><strong>FAQ</strong></a></li>
        </ul>
      </div>
      <div class="col-md-6 mb-3">
        <h5>Pratite nas</h5>
        <div class="social-icons mt-2">
          <a href="https://www.instagram.com/viserbgd/"><i class="bi bi-instagram"></i></a>
          <a href="https://www.youtube.com/@avt_viser_atuss"><i class="bi bi-youtube"></i></a>
          <a href="https://x.com/VISERBgd"><i class="bi bi-twitter"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom text-center py-2 text-muted">
    &copy; 2026 Prime Tickets. Sva prava zadržana.
  </div>
</footer>
<script>
    const searchToggle = document.getElementById('searchToggle');
    const searchItem = document.querySelector('.search-item');
    const searchInput = document.getElementById('searchInput');
    searchToggle.addEventListener('click', function(e) {
        e.preventDefault();
        searchItem.classList.toggle('open');
        if(searchItem.classList.contains('open')) {
            searchInput.focus();
        } else {
            searchInput.value = '';
        }
    });
    document.addEventListener('click', function(e){
        if(!searchItem.contains(e.target)) {
            searchItem.classList.remove('open');
            searchInput.value = '';
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>