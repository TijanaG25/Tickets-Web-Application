<?php session_start();
require_once('konekcija.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vesti</title>
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

<section class="container py-5 text-light">
    <h1 class="text-warning mb-5 text-center fw-bold">Najnovije vesti</h1>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card vest-card bg-dark border-0">
                <img src="images/vest1.jpeg" class="card-img-top vest-img" alt="">
                <div class="card-body">
                    <h5 class="card-title text-warning">Jakov Jozinović - konferencija za medije povodom predstojećih koncerata u Sava Centru</h5>
                    <p class="card-text text-light">Muzička scena Srbije svedoči jednom od najsnažnijih koncertnih uspeha u poslednjih nekoliko godina
                         – Jakov Jozinović zakazao je čak šest koncerata u Sava centru, potvrđujući status jednog od najtraženijih izvođača svoje generacije. 
                         Tim povodom danas je u foajeu Sava Centra održana konferencija za medije na kojoj je umetnik govorio o velikom interesovanju publike, 
                         kreativnim planovima i emocijama koje ga prate uoči predstojećih nastupa.</p>
                    <a href="https://tickets.rs/post/jakov_jozinovic_konferencija_za_medije_povodom_predstojecih_koncerata_u_sava_centru_2161" target="_blank" class="btn btn-outline-warning btn-sm">Pročitaj više</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card vest-card bg-dark border-0 h-100">
                <img src="images/vest2.jpeg" class="card-img-top vest-img" alt="">
                <div class="card-body">
                    <h5 class="card-title text-warning">THE 3</h5>
                    <p class="card-text text-light">Najnoviji muzički spektakl očekuje publiku 30. maja 2026. u MTS Dvorani sa početkom u 
                        20.00: koncert ”THE 3” kantautorke Lene Kovačević, glumice Jelene Gavrilović i dramskog soprana Marije Jelić. Ovo 
                        je imerzivni muzičko-scenski projekat koji po prvi put na istoj sceni objedinjuje glasove pop/džez pevačice, glumice 
                        i operske dive, spajajući tri umetničke energije u jednu novu muzičku dimenziju.</p>
                    <a href="https://tickets.rs/post/the_3_2168" target="_blank" class="btn btn-outline-warning btn-sm">Pročitaj više</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card vest-card bg-dark border-0 h-100">
                <img src="images/vest3.png" class="card-img-top vest-img" alt="">
                <div class="card-body">
                    <h5 class="card-title text-warning">Nina Badrić</h5>
                    <p class="card-text text-light">Najlepši deo godine donosi najlepše emocije. Posle dva koncerta koja su izazvala 
                        ogromno interesovanje, Nina Badrić zakazuje i treći datum u Plavoj dvorani Sava Centra - 8. maja 2026, sa 
                        posebnim akustičnim spektaklom Nina Unplugged.</p>
                    <a href="https://tickets.rs/post/nina_badric_2164" target="_blank" class="btn btn-outline-warning btn-sm">Pročitaj više</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card vest-card bg-dark border-0 h-100">
                <img src="images/vest4.jpeg" class="card-img-top vest-img" alt="">
                <div class="card-body">
                    <h5 class="card-title text-warning">UDAJ SE MUŠKI</h5>
                    <p class="card-text text-light">Na sceni vas čekaju: Snežana Savić, Miloš Đorđević, Ratko Tankosić, Stefan Uroš Tešić, 
                        Aleksandar Meda Jovanović i Predrag Kotur u predstavi koja će vas podsetiti zašto su prijatelji važniji od para — 
                        i zašto su ponekad opasniji od neprijatelja.</p>
                    <a href="https://tickets.rs/post/udaj_se_muski_2166" target="_blank" class="btn btn-outline-warning btn-sm">Pročitaj više</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card vest-card bg-dark border-0 h-100">
                <img src="images/vest5.jpeg" class="card-img-top vest-img" alt="">
                <div class="card-body">
                    <h5 class="card-title text-warning">XXVII Guitar Art Festival</h5>
                    <p class="card-text text-light">Guitar Art Festival donosi duh klasike ovog marta u Beogradu
                    Beograd je tradicionalno domaćin 27. izdanja Guitar Art Festivala, jednog od najznačajnijih međunarodnih 
                    festivala gitare u ovom delu sveta. Ovogodišnji festival održava se od 11. do 15. marta 2026. godine pod 
                    sloganom „Classic“, koji simbolično ukazuje na klasičnu muziku, vrhunski umetnički nivo i koncerte najboljih 
                    gitarista današnjice.</p>
                    <a href="https://tickets.rs/post/xxvii_guitar_art_festival_2162" target="_blank" class="btn btn-outline-warning btn-sm">Pročitaj više</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card vest-card bg-dark border-0 h-100">
                <img src="images/vest6.jpeg" class="card-img-top vest-img" alt="">
                <div class="card-body">
                    <h5 class="card-title text-warning">Metallica bend - Black dolazi u Punk Rock Cafe u Smederevskoj Palanci</h5>
                    <p class="card-text text-light">Nakon rasprodatih koncerata u MTS Dvorani, Lisinskom i Cankarevom domu najbolji 
                        evropski Metallica bend - Black dolazi i u Punk Rock Cafe u Smederevskoj Palanci  u kojoj će u subotu, 07.03. 
                        od 22 časa održati koncert za pamćenje. Na repertoaru benda naći će se najveći hitovi Metallica i gotovo ceo 
                        album Black!</p>
                    <a href="https://tickets.rs/post/metallica_bend_black_dolazi_u_punk_rock_cafe_u_smederevskoj_palanci__2160" target="_blank" class="btn btn-outline-warning btn-sm">Pročitaj više</a>
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