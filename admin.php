<?php
session_start();
if(!isset($_SESSION['id']) || $_SESSION['uloga'] !== 'administrator'){
    header("Location: prijava.php");
    exit();
}
require_once("konekcija.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" type="image/png" href="images/admin.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/styleAdmin.css">
</head>
<body>
    <div class="admin-header">
        <?php
        echo "<h2>Admin - {$_SESSION['ime']}</h2>";
        ?>
        <div>
            <a href="index.php">Pogledaj sajt</a>
            <a href="logout.php">Odjava</a>
        </div>
    </div>
    <div class="admin-container">
        <div class="admin-sidebar">
            <h4>Meni</h4>
            <ul>
                <li><a href="#" onclick="prikazi('dogadjaji')">Događaji</a></li>
                <li><a href="#" onclick="prikazi('korisnici')">Upravljanje korisnicima</a></li>
                <li><a href="#" onclick="prikazi('mesta')">Mesta događaja</a></li>
                <li><a href="#" onclick="prikazi('kupovine')">Kupovine</a></li>
            </ul>
        </div> 
        <div class="admin-main">
            <div id="dogadjaji" class="admin-section">
                <h3>Događaji</h3>
                <div class="card">
                    <button class="btn btn-admin" onclick="toggleForm('spisakDogadjaja')">Spisak svih događaja</button>
                    <div id="spisakDogadjaja" class="admin-dropdown" style="display:none;">
                        <br>
                        <?php
                            $ispis=mysqli_query($dbc,"SELECT d.id as dogadjaj_id,d.organizator, d.naziv as dogadjaj_naziv,d.datum_vreme,s.naziv as sala_naziv,a.grad,count(m.id) as ukupno_mesta,count(case when k.id is null then 1 end) as broj_slobodnih
                                                    FROM dogadjaji d join sale s on d.idSale=s.id
                                                                    join adrese a on s.idAdrese=a.id
                                                                    join mesta m on s.id=m.idSale
                                                                    left join karte k on m.id=k.idMesta and k.idDogadjaja=d.id
                                                    WHERE d.status='aktivan'
                                                    GROUP BY d.id,d.naziv,d.datum_vreme,s.naziv,a.grad
                                                    ORDER BY d.datum_vreme;");
                            while($red=mysqli_fetch_assoc($ispis)){
                                echo"<p><b>{$red['dogadjaj_naziv']}</b> {$red['organizator']} - {$red['datum_vreme']} - {$red['sala_naziv']} ({$red['grad']})<br>
                                        - <b>ukupno mesta: {$red['ukupno_mesta']}</b>          - broj slobodnih mesta: {$red['broj_slobodnih']}</p><br>";
                            }
                        ?>
                        <br>
                    </div>
                    <button class="btn btn-admin" onclick="toggleForm('dodajDogadjaj')">Dodaj događaj</button>
                    <div id="dodajDogadjaj" class="admin-dropdown" style="display:none;">
                        <br>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form method="POST" action="dodajDogadjaj.php" enctype="multipart/form-data">
                            <div class="mb-2">
                                <input type="text" name="naziv" class="form-control" placeholder="Unesite naziv događaja" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="organizator" class="form-control" placeholder="Unesite organizatora" required>
                            </div>
                            <div  class="mb-2">
                                <input type="date" name="datum" class="form-control" required>
                            </div>
                            <div  class="mb-2">
                                <input type="time" name="vreme" class="form-control" required>
                            </div>
                            <div  class="mb-2">
                                <label class="form-label">Unesite sliku za događaj</label>
                                <input type="file" name="slika" class="form-control" required>
                            </div>
                            <div  class="mb-2">
                                <select name="idSale" class="form-select" required>
                                    <option value="">---Izaberite mesto događaja---</option>
                                    <?php
                                    $rez=mysqli_query($dbc,"SELECT s.id,s.naziv,a.grad
                                                            FROM sale s join adrese a on s.idAdrese=a.id");
                                    if(mysqli_num_rows($rez) != 0){
                                        while($red=mysqli_fetch_assoc($rez)){
                                            echo "<option value='{$red['id']}'>{$red['naziv']} - {$red['grad']}</option>";
                                        }
                                    }
                                    else
                                        echo" <option value=''>---Nema unetih mesta---</option>";
                                    ?>
                                </select>
                            </div>
                           <div class="mb-2">
                                <input type="number" name="cena" class="form-control" placeholder="Unesite cenu (RSD)" min="0" required>
                            </div>
                             <div  class="mb-2">
                                <select name="idKategorije" class="form-select" required>
                                    <option value="">---Izaberite kategoriju---</option>
                                    <?php
                                    $rez=mysqli_query($dbc,"SELECT *
                                                            FROM kategorije");
                                    if(mysqli_num_rows($rez) != 0){
                                        while($red=mysqli_fetch_assoc($rez)){
                                            echo "<option value='{$red['id']}'>{$red['naziv']}</option>";
                                        }
                                    }
                                    else
                                        echo" <option value=''>---Nema unetih kategorija---</option>";
                                    ?>
                                </select>
                            </div>
                            <div  class="mb-2">
                                <select name="idVrste" class="form-select" required>
                                    <option value="">---Izaberite vrstu---</option>
                                    <?php
                                    $rez=mysqli_query($dbc,"SELECT v.id,v.naziv
                                                            FROM vrste v");
                                    if(mysqli_num_rows($rez) != 0){
                                        while($red=mysqli_fetch_assoc($rez)){
                                            echo "<option value='{$red['id']}'>{$red['naziv']}</option>";
                                        }
                                    }
                                    else
                                       echo" <option value=''>---Nema vrsta---</option>";
                                    ?>
                                </select>
                            </div>
                            <div  class="mb-2">
                                <textarea  name="opis" class="form-control" rows="4" placeholder="Unesite kratak opis događaja" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Sačuvaj</button>
                        </form>
                        <br>
                    </div>
                    <button class="btn btn-admin" onclick="toggleForm('obrisiDogadjaj')">Obriši događaj</button>
                    <div id="obrisiDogadjaj" class="admin-dropdown" style="display:none;">
                        <br>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form method="POST" action="obrisiDogadjaj.php">
                            <div class="mb-2">
                                <input type="text" name="naziv" class="form-control" placeholder="Unesite naziv događaja" required>
                            </div>
                            <div class="mb-2">
                                <input type="date" name="datum" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <input type="time" name="vreme" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Obriši</button>
                            <br>
                        </form>
                        <br>
                    </div>
                    <button class="btn btn-admin" onclick="toggleForm('izmeniDogadjaj')">Izmeni događaj</button>
                    <div id="izmeniDogadjaj" class="admin-dropdown" style="display:none;">
                        <br>
                        <form method="POST">
                            <div class="mb-2">
                                <input type="text" name="nazivPronadji" class="form-control" placeholder="Unesite naziv događaja" required>
                            </div>
                            <div class="mb-2">
                                <input type="date" name="datumPronadji" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <input type="time" name="vremePronadji" class="form-control" required>
                            </div>
                            <button type="submit" name="pronadji" class="btn btn-primary w-100">Pronađi</button>
                            <br>
                        </form>
                        <?php
                        if(isset($_POST['pronadji'])){
                            $naziv=$_POST['nazivPronadji'];
                            $datum=$_POST['datumPronadji'];
                            $vreme=$_POST['vremePronadji'];
                            $datum_vreme=$datum.' '.$vreme;
                            $upit=mysqli_prepare($dbc,"SELECT *
                                                        FROM dogadjaji
                                                        WHERE naziv=? and datum_vreme=?");
                            mysqli_stmt_bind_param($upit,"ss",$naziv,$datum_vreme);
                            mysqli_stmt_execute($upit);
                            $rez=mysqli_stmt_get_result($upit);
                            if($row=mysqli_fetch_assoc($rez)){
                        ?>
                        <br>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form method="POST" action="izmeniDogadjaj.php" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-2">
                                <input type="text" name="naziv" value="<?= $row['naziv'] ?>" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <input type="datetime-local" name="datum_vreme" value="<?= date('Y-m-d\TH:i', strtotime($row['datum_vreme'])) ?>" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <input type="number" name="cena" value="<?= $row['cena'] ?>" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <textarea name="opis" class="form-control" required> <?= $row['opis'] ?></textarea>
                            </div>
                            <button type="submit" name="sacuvaj" class="btn btn-success w-100">Sačuvaj izmene</button>
                        </form>
                        <br>
                        <?php
                            }
                            mysqli_stmt_close($upit);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div id="korisnici" class="admin-section">
                <h3>Korisnici</h3>
                <div class="card">
                    <button class="btn btn-admin mb-3" onclick="toggleForm('obrisiKorisnika')">Obrisi korisnika</button>
                    <div id="obrisiKorisnika" class="admin-dropdown" style="display:none;">
                    <br>
                    <?php
                        if(isset($_SESSION['error'])){
                            echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
                            unset($_SESSION['error']);
                        }
                        ?>
                    <form method="POST" action="obrisiKorisnika.php">
                        <div class="mb-2">
                                <input type="text" name="ime" class="form-control" placeholder="Unesite ime korisnika" required>
                        </div>
                        <div class="mb-2">
                                <input type="text" name="prezime" class="form-control" placeholder="Unesite prezime korisnika" required>
                        </div>
                        <div class="mb-2">
                                <input type="email" name="email" class="form-control" placeholder="Unesite email korisnika" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Obriši korisnika</button>
                        <br>
                    </form>
                    <br>
                    </div>
                    <button class="btn btn-admin mb-3" onclick="toggleForm('prikaziKorisnike')">Svi korisnici</button>
                    <div id="prikaziKorisnike" class="admin-dropdown" style="display:none;">
                        <br>
                        <?php
                            $rez = mysqli_query($dbc, "SELECT * FROM osobe");
                            echo "<table border='1' cellpadding='5'>";
                            echo "<tr>
                                    <th>Ime</th>
                                    <th>Prezime</th>
                                    <th>Email</th>
                                    <th>Telefon</th>
                                    <th>Datum registracije</th>
                                    <th>Uloga</th>
                                    <th>Status</th>
                                </tr>";
                            while($row = mysqli_fetch_assoc($rez)){
                                echo "<tr>
                                        <td>{$row['ime']}</td>
                                        <td>{$row['prezime']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['telefon']}</td>
                                        <td>{$row['datum_registracije']}</td>
                                        <td>{$row['uloga']}</td>
                                        <td>{$row['status']}</td>
                                    </tr>";
                                }

                            echo "</table>";
                        ?>
                        <br>
                    </div>
                    <button class="btn btn-admin mb-3" onclick="toggleForm('postaviAdmina')">Postavi admina</button>
                    <div id="postaviAdmina" class="admin-dropdown" style="display:none;">
                        <br>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form method="POST" action="postaviAdmina.php">
                            <div class="mb-2">
                                <input type="text" name="ime" class="form-control" placeholder="Unesite ime korisnika" required>
                        </div>
                        <div class="mb-2">
                                <input type="text" name="prezime" class="form-control" placeholder="Unesite prezime korisnika" required>
                        </div>
                        <div class="mb-2">
                                <input type="email" name="email" class="form-control" placeholder="Unesite email korisnika" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Postavi za admina</button>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
            <div id="mesta" class="admin-section">
                <h3>Dodaj mesto događaja</h3>
                <div class="card p-3">
                    <button class="btn btn-admin mb-3" onclick="toggleForm('dodajMesto')">Dodaj mesto</button>
                    <div id="dodajMesto" class="admin-dropdown" style="display:none;">
                    <br>
                    <form method="POST" action="dodajMesto.php">
                        <div class="mb-2">
                            <input type="text" name="naziv" class="form-control" placeholder="Unesite naziv mesta događaja" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="grad" class="form-control" placeholder="Unesite grad" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="ulica" class="form-control" placeholder="Unesite ulicu" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="brojSale" class="form-control" placeholder="Unesite broj" required>
                        </div>
                        <h5>Mesta u sali</h5>
                            <div id="mestaContainer">
                                <div class="mb-2 mesto-row">
                                    <input type="number" name="red[]" class="form-control mb-1" placeholder="Red" required>
                                    <input type="number" name="broj[]" class="form-control mb-2" placeholder="Broj mesta" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" onclick="dodajMesto()">Dodaj još mesta</button>
                            <br>
                        <button type="submit" class="btn btn-success w-100">Dodaj mesto</button>
                    </form>
                    <br>
                    </div>
                </div>
            </div>
            <div id="kupovine" class="admin-section">
                <h3>Kupovine</h3>
                <div class="card">
                <button class="btn btn-admin mb-3" onclick="toggleForm('prikaziKupovine')">Prikazi sve kupovine</button>
                <div id="prikaziKupovine" class="admin-dropdown" style="display:none;">
                    <br>
                    <?php
                    $rez = mysqli_query($dbc, "SELECT k.id AS kupovina_id,DATE(k.datum_kupovine) AS datum,k.datum_kupovine,k.ukupnaCena,o.ime,o.prezime,d.naziv AS dogadjaj,s.naziv AS sala,m.red,m.broj,ka.cena
                                            FROM kupovine k JOIN osobe o ON k.idKorisnika = o.id
                                                            JOIN stavkeKupovine sk ON k.id = sk.idKupovine
                                                            JOIN karte ka ON sk.idKarte = ka.id
                                                            JOIN dogadjaji d ON ka.idDogadjaja = d.id
                                                            JOIN mesta m ON ka.idMesta = m.id
                                                            JOIN sale s ON m.idSale = s.id
                                                            ORDER BY k.datum_kupovine DESC");
                    $trenutniDatum = null;
                    $kupovina = null;
                    while($red = mysqli_fetch_assoc($rez)){
                        if($trenutniDatum != $red['datum']){
                            if($trenutniDatum != null){
                                echo "</div>";
                            }
                            $trenutniDatum = $red['datum'];
                            echo "<div class='mt-4'>";
                        }
                        if($kupovina != $red['kupovina_id']){
                            if($kupovina != null){
                                echo "</tbody></table></div></div>";
                            }
                            $kupovina = $red['kupovina_id'];
                            echo "<div class='card shadow-sm mb-3'>
                                    <div class='card-header bg-primary text-white'>Kupovina #{$red['kupovina_id']}</div>
                                    <div class='card-body'>
                                        <p><b>Korisnik:</b> {$red['ime']} {$red['prezime']} <br>
                                            <b>Vreme kupovine:</b> {$red['datum_kupovine']} <br>
                                            <b>Ukupna cena:</b> {$red['ukupnaCena']} RSD</p>
                                    <table class='table table-bordered table-sm'>
                                        <thead class='table-light'>
                                            <tr><th>Događaj</th>
                                                <th>Sala</th>
                                                <th>Red</th>
                                                <th>Mesto</th>
                                                <th>Cena</th>
                                            </tr>
                                        </thead>
                                    <tbody>";
                        }
                        echo "<tr>
                            <td>{$red['dogadjaj']}</td>
                            <td>{$red['sala']}</td>
                            <td>{$red['red']}</td>
                            <td>{$red['broj']}</td>
                            <td>{$red['cena']} RSD</td></tr>";
                        }
                    if($kupovina != null){
                        echo "</tbody></table></div></div>";
                        }
                    if($trenutniDatum != null){
                        echo "</div>";
                    }
                    ?>
                    <br>
                </div>
                </div>
            </div>
        </div> 
    </div>
<script>
    document.querySelectorAll('.admin-section').forEach(sec => sec.style.display = 'none');
    document.getElementById('dogadjaji').style.display = 'block';
    function prikazi(id) {
        const sekcija = document.querySelectorAll('.admin-section');
        sekcija.forEach(sec => sec.style.display = 'none');
        document.getElementById(id).style.display = 'block';
    }
    function toggleForm(id) {
        const element = document.getElementById(id);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
    function dodajMesto() {
        const container = document.getElementById('mestaContainer');
        const div = document.createElement('div');
        div.classList.add('mesto-row', 'mb-2');
        div.innerHTML = `
            <input type="number" name="red[]" class="form-control mb-1" placeholder="Red" required>
            <input type="number" name="broj[]" class="form-control mb-2" placeholder="Broj mesta" required>`;
        container.appendChild(div);
    }
</script>
</body>
</html>