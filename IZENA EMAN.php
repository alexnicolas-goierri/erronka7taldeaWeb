<?php
session_start();
include_once "konexioa.php"; 

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aukera = $_POST["Aukera"] ?? ""; // Bezeroa o Hornitzailea
    $email = $_POST["posta_elektronikoa"] ?? "";
    $pasahitza = $_POST["pasahitza"] ?? "";
    
    // ERABILTZAILE TAULAN gmail ez dela errepikatzen
    $check_sql = "SELECT id FROM erabiltzaile WHERE gmail = ?";
    $check_stmt = $conexion->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $mensaje = "Posta elektroniko hau dagoeneko erregistratuta dago.";
    } else {
        
        $conexion->begin_transaction();
        
        try {
            if ($aukera === "Bezeroa") {
                $izena = $_POST["izena"] ?? "";
                $abizena = $_POST["abizena"] ?? "";
                $nan = $_POST["nan"] ?? "";
                $telefono = $_POST["telefono"] ?? "";
                $helbide = $_POST["helbide"] ?? "";
                
                // 1. BEZERO TAULAN INSERT BAT EGIN
                $sql_bezero = "INSERT INTO bezero (izena, abizena, gmail, pasahitza, NAN, Telefono, Helbide) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_bezero = $conexion->prepare($sql_bezero);
                $stmt_bezero->bind_param("sssssss", $izena, $abizena, $email, $pasahitza, $nan, $telefono, $helbide);
                $stmt_bezero->execute();
                
                $bezero_id = $conexion->insert_id;
                
                // 2. ERABILTZAILE TAULAN ROLA DEFEKTUZ DEFINITU BEZERO ROLA 5
                $sql_erab = "INSERT INTO erabiltzaile (gmail, pass, bezero_id, rol_id) VALUES (?, ?, ?, 5)";
                $stmt_erab = $conexion->prepare($sql_erab);
                $stmt_erab->bind_param("ssi", $email, $pasahitza, $bezero_id);
                $stmt_erab->execute();
                
            } else if ($aukera === "Hornitzailea") {
                $enpresa = $_POST["enpresaren_izena"] ?? "";
                $telefonoa = $_POST["telefonoa"] ?? "";
                
                // 1. HORNITZAILE TAULAN INSERT BAT EGIN
                $sql_horn = "INSERT INTO hornitzaile (enpreza_izena, telefono, gmail) VALUES (?, ?, ?)";
                $stmt_horn = $conexion->prepare($sql_horn);
                $stmt_horn->bind_param("sss", $enpresa, $telefonoa, $email);
                $stmt_horn->execute();
                
                $hornitzaile_id = $conexion->insert_id;
                
               // ERABILTZAILE TAULAN ROLA DEFEKTUZ DEFINITU HORNITZAILE ROLA 
                $sql_erab = "INSERT INTO erabiltzaile (gmail, pass, hornitzaile_id, rol_id) VALUES (?, ?, ?, 1)";
                $stmt_erab = $conexion->prepare($sql_erab);
                $stmt_erab->bind_param("ssi", $email, $pasahitza, $hornitzaile_id);
                $stmt_erab->execute();
            }
            
            $conexion->commit();
            $mensaje = "Erabiltzailea arrakastaz sortu da!";
            
           
            
        } catch (Exception $e) {
            $conexion->rollback();
            $mensaje = "Errorea: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Izena Eman</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="SECONDS AGO LOGO.png"/>
</head>
<body>
   <header>
        <?php include_once "navbar.php"; ?>
    </header> 
    <main>
      <?php if($mensaje) echo "<div class='mensaje'>$mensaje</div>"; ?>
      
      <form method="post">

<label>Bezeroa</label>
<input type="radio" name="Aukera" value="Bezeroa" required>

<label>Hornitzailea</label>
<input type="radio" name="Aukera" value="Hornitzailea">

<input type="text" name="izena" id="izena" placeholder="Izena">
<input type="text" name="abizena" id="abizena" placeholder="Abizena">
<input type="text" name="nan" id="nan" placeholder="NAN">
<input type="text" name="telefono" id="telefono" placeholder="Telefonoa">
<input type="text" name="helbide" id="helbide" placeholder="Helbidea">

<input type="text" name="enpresaren_izena" id="enpresaren_izena" placeholder="Empresa">

<input type="tel" name="telefonoa" id="telefonoa" placeholder="+34...">

<input type="email" name="posta_elektronikoa" id="posta_elektronikoa" placeholder="example@gmail.com" required>
<input type="password" name="pasahitza" id="pasahitza" required>

<button type="reset">Ezabatu</button>
<button type="submit">Sortu</button>

</form>

    </main>
    <footer>
    <h3>ENPRESA KOLABORATIBOAK</h3>
     <div class="sarrera-grid">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/8/82/Dell_Logo.png" alt="Dell">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Lenovo_Global_Corporate_Logo.png/960px-Lenovo_Global_Corporate_Logo.png" alt="Lenovo">
        <img class="logo-empresa" src="img/Apple logo.png" alt="Apple">
        <img class="logo-empresa" src="img/Microsoft logo.png" alt="Microsoft">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Fujitsu-Logo.svg/1024px-Fujitsu-Logo.svg.png" alt="Fujitsu">
        <img class="logo-empresa" src="https://images.icon-icons.com/2699/PNG/512/acer_logo_icon_169649.png" alt="Acer">
        <img class="logo-empresa" src="img/ASUS logo.png" alt="Asus">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Ingram_logo.jpg/1200px-Ingram_logo.jpg" alt="Ingram">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/IBM_logo.svg/960px-IBM_logo.svg.png" alt="IBM">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Intel_logo_2023.svg/2560px-Intel_logo_2023.svg.png" alt="Intel">
    </div>
        <h3>SARE SOZIALAK</h3>
        <a href="#INSTAGRAM"><img src="img/Instagram logo.png"height="60"width="60"alt="Instagram"/></a>
        <a href="#YOUTUBE"><img src="img/YouTube logo.png" height="60" width="60" alt="YouTube"/></a>
        <a href="#X"><img src="img/X logo.png" height="60" width="60" alt="X"/></a>

        <div>
            <h3>INFORMAZIO GEHIGARRIA</h3>
            <p><a href="#KOKAPENA">KOKAPENA</a></p>
            <p><a href="#KONTAKTUAK">KONTAKTUAK</a></p>
            <p><a href="#ITZULKETA POLITIKA">ITZULKETA POLITIKA</a></p>
            <p><a href="#SALMETA BALDINTZAK">SALMETA BALDINTZAK</a></p>
            <p><a href="#LEGE OHARRA ETA COOKIEN POLITIKA">LEGE OHARRA ETA COOKIEN POLITIKA</a></p>
        </div>
    </footer>
    <script>
    const rolAukerak = document.querySelectorAll('input[name="Aukera"]');
    const eremuGuztiak = document.querySelectorAll(
      'input[type="text"], input[type="email"], input[type="tel"]'
    );

    function desgaituDenak() {
      eremuGuztiak.forEach(e => e.disabled = true);
    }

    desgaituDenak();

    rolAukerak.forEach(aukera => {
      aukera.addEventListener('change', () => {
        desgaituDenak();

        if (aukera.value === 'Bezeroa') {
          document.getElementById('izena').disabled = false;
          document.getElementById('abizena').disabled = false;
          document.getElementById('nan').disabled = false;
          document.getElementById('telefono').disabled = false;
          document.getElementById('helbide').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('pasahitza').disabled = false;
        } 
        else if (aukera.value === 'Hornitzailea') {
          document.getElementById('enpresaren_izena').disabled = false;
          document.getElementById('telefonoa').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('pasahitza').disabled = false;
        }
      });
    });
  </script>
</body>
</html>