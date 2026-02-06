<?php 
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS_Erronka.css?v=2">
    <title>Document</title>
</head>
<body>
    <nav>
         <div class="nav">
            <button class="sidebarboton" onclick="toggleSidebar()">☰ Menú</button>
            <a href="SARRERA.php">
               <img src="img/TechnoPlus.png" height="125" width="125" alt="SecondsAgo logo"></a>
            <form action="KATALOGOA.php" method="get" class="bilatzailea">
                <input type="text" name="keywords" placeholder="Bilatu produktuak..." />
                <button type="submit" name="search" value="1" >BILATU</button>
            </form>
            <a href="KATALOGOA.php">KATALOGOA</a>
            <a href="SARRERA.php">SARRERA</a>
            
            
            <?php if (isset($_SESSION["user_id"])): ?>
                <div class="user-info">
                    <span>Kaixo, <?= htmlspecialchars($_SESSION["user_nombre"]) ?>!</span>
                    <a href="itxi_saioa.php" class="logout-btn">Itxi saioa</a>
                </div>
            <?php else: ?>
                <a href="HASI SAIOA.php">SAIOA HASI</a>
                <a href="IZENA EMAN.php">LOG-IN</a>
            <?php endif; ?>
            
            <a href="karritoa.php"><img src="img/saskia.png" alt="" height="50%" width="50%" class="saskia"></a>
            <div class="zenbaki-saskia">
                <span id="saskia">
                    <?= isset($_SESSION['saskia']) ? count($_SESSION['saskia']) : 0 ?> 
                </span>
            </div>
        </div>
        </nav>
    <div class="sidebar" id="sidebar"> 
        <p><a href="SARRERA.php">KOKAPENA</a></p>
        <p><a href="kontaktua.php">KONTAKTUAK</a></p>
        <p><a href="izen_politika.php">ITZULKETA POLITIKA</a></p>
        <p><a href="salmenta_baldintzak.php">SALMETA BALDINTZAK</a></p>
        <p><a href="lege_oharrak.php">LEGE OHARRA ETA COOKIEN POLITIKA</a></p>
    </div>
    
    <script>
            function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>