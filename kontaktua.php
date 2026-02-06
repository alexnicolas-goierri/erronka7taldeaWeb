<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <link rel="stylesheet" href="CSS_erronka.css">
</head>
<body>

   <header>
        <?php include_once "navbar.php" ?>
    </header>
    <br>

    <main>
        <form action="#" method="post">
            <h2>Kontaktua</h2>

            <label for="nombre">izena</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">email</label>
            <input type="email" id="email" name="email" required>

            <label for="gaia">gaia</label>
            <select id="gaia" name="gaia">
                <option value="kontsulta">Kontsulta</option>
                <option value="soporte">Soporte</option>
                <option value="beste">beste</option>
            </select>

            <label for="mezua">mezua</label>
            <input type="text" id="mezua" name="mezua" required>

            <button type="submit">bidali</button>
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
        <div class="sarrera-grid">
            <a href="#INSTAGRAM"><img class="logo-empresa" src="img/Instagram logo.png" alt="Instagram"/></a>
            <a href="#YOUTUBE"><img class="logo-empresa" src="img/YouTube logo.png" alt="YouTube"/></a>
            <a href="#X"><img class="logo-empresa" src="img/X logo.png" alt="X"/></a>
        </div>

        <h3>INFORMAZIO GEHIGARRIA</h3>
        <p><a href="#KOKAPENA">KOKAPENA</a></p>
        <p><a href="#KONTAKTUAK">KONTAKTUAK</a></p>
        <p><a href="#ITZULKETA_POLITIKA">ITZULKETA POLITIKA</a></p>
        <p><a href="#SALMETA_BALDINTZAK">SALMETA BALDINTZAK</a></p>
        <p><a href="#LEGE_OHARRA_ETA_COOKIEN_POLITIKA">LEGE OHARRA ETA COOKIEN POLITIKA</a></p>
    </footer>

</body>
</html>
