<?php session_start();
require_once('konekcija.php');
$vrsta = isset($_GET['vrsta']) ? $_GET['vrsta'] : 'Svi događaji';
$gradFilter = isset($_GET['grad']) ? $_GET['grad'] : '';
$uslovGrad = "";
if(!empty($gradFilter)){
    $gradFilter = mysqli_real_escape_string($dbc, $gradFilter);
    $uslovGrad = " AND a.grad = '$gradFilter' ";
}
if($vrsta == "Svi događaji") {
    $upit = "SELECT d.* 
              FROM dogadjaji d JOIN vrste v ON d.idVrste = v.id
                                JOIN kategorije k ON v.idKategorije = k.id
                                JOIN sale s ON s.id=d.idSale
                                JOIN adrese a ON a.id=s.idAdrese
                                WHERE k.naziv = 'Pozorište' AND d.status='aktivan' and datum_vreme>now() $uslovGrad
                                ORDER BY d.datum_vreme ASC";
} 
else {
    $vrsta = mysqli_real_escape_string($dbc, $vrsta);
    $upit = "SELECT d.* 
              FROM dogadjaji d JOIN vrste v ON d.idVrste = v.id
                            JOIN kategorije k ON v.idKategorije = k.id
                            JOIN sale s ON s.id=d.idSale
                            JOIN adrese a ON a.id=s.idAdrese
              WHERE k.naziv = 'Pozorište' AND v.naziv = '$vrsta' AND d.status='aktivan'and datum_vreme>now() $uslovGrad
              ORDER BY d.datum_vreme ASC";
}
$rez = mysqli_query($dbc, $upit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pozorište</title>
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
<div class="container mt-4">
    <form method="GET" class="row align-items-center g-3">
      <?php
      if(isset($_GET['vrsta'])) {
        echo '<input type="hidden" name="vrsta" value="' . $_GET['vrsta']. '">';
        }
      ?>
        <div class="col-md-4">
            <select name="grad" class="form-select">
                <option value="">-- Izaberi grad --</option>
                <?php
                $gradoviUpit = "SELECT DISTINCT a.grad 
                                FROM adrese a JOIN sale s ON a.id = s.idAdrese
                                              JOIN dogadjaji d ON s.id = d.idSale
                                WHERE d.status='aktivan' AND d.datum_vreme > NOW()
                                ORDER BY a.grad ASC";
                $gradoviRez = mysqli_query($dbc, $gradoviUpit);
                while($g = mysqli_fetch_assoc($gradoviRez)){
                    $selected = ($gradFilter == $g['grad']) ? "selected" : "";
                    echo "<option value='{$g['grad']}' $selected>{$g['grad']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning w-100">Filtriraj</button>
        </div>
    </form>
</div>
<div class="container my-5">
    <h2 class="text-warning mb-4">Događaji</h2>
    <?php
    if (mysqli_num_rows($rez) > 0) {
        while ($d = mysqli_fetch_assoc($rez)) {
            $naziv = $d['naziv'];
            $organizator=$d['organizator'];
            $datum = date('d.m.Y', strtotime($d['datum_vreme']));
            $vreme = date('H:i', strtotime($d['datum_vreme']));
            $id = (int)$d['id'];
            $slika = htmlspecialchars($d['slika']);
            echo "<div class='row align-items-center mb-4 p-3 shadow-sm bg-dark text-light rounded'>";
            echo "<div class='col-md-8'>";
                echo "<h4 class='text-warning'>{$naziv}</h4>";
                echo "<p><strong> {$organizator} </strong></p>";
                echo "<p><strong>Datum:</strong> {$datum}</p>";
                echo "<p><strong>Vreme:</strong> {$vreme}</p>";
                echo "<a href='kupovina.php?id={$id}' class='btn btn-warning mt-2'>Kupi kartu</a>";
            echo "</div>";
            echo "<div class='col-md-4 text-end'>";
                echo "<img src='images/{$slika}'style='width:250px; height:200px; object-fit:cover;'class='rounded shadow'>";
            echo "</div>";
            echo "</div>";
        }
        } 
    else {
        echo "<p class='text-muted'>Trenutno nema dostupnih događaja.</p>";
    }
?>
</div>
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