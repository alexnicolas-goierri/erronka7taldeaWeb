<?php
session_start();
include_once "konexioa.php";

// VERIFICAR LOGIN
if (!isset($_SESSION["user_id"])) {
    header("Location: HASI SAIOA.php");
    exit;
}

$mensaje_exito = "";

/* PROCESAR COMPRA */
if (isset($_POST['ordainketa'])) {
    
    if (!isset($_SESSION['saskia']) || empty($_SESSION['saskia'])) {
        header("Location: katalogoa.php");
        exit;
    }
    
    // OBTENER EL bezero_id del usuario logeado
    $user_id = $_SESSION["user_id"];
    $sql_bezero = "SELECT bezero_id FROM erabiltzaile WHERE id = ?";
    $stmt_bez = $conexion->prepare($sql_bezero);
    $stmt_bez->bind_param("i", $user_id);
    $stmt_bez->execute();
    $result_bez = $stmt_bez->get_result();

    if ($result_bez->num_rows === 0) {
        die("Errorea: Ez zara bezero bat.");
    }

    $bezero_data = $result_bez->fetch_assoc();
    $bezero_id = $bezero_data['bezero_id'];

    if (!$bezero_id) {
        die("Errorea: Ez duzu bezero profila.");
    }

    // COMENZAR TRANSACCIÓN
    $conexion->begin_transaction();

    try {
        // 1. CREAR KARRITO
        $sql_karrito = "INSERT INTO karrito (bezero_id, egoera) VALUES (?, 'erosita')";
        $stmt_karrito = $conexion->prepare($sql_karrito);
        $stmt_karrito->bind_param("i", $bezero_id);
        $stmt_karrito->execute();
        
        $karrito_id = $conexion->insert_id;
        
        // 2. OBTENER DATOS DE LOS PRODUCTOS
        $ids = implode(",", array_keys($_SESSION['saskia']));
        $sql_produktuak = "SELECT id, izena, prezioa FROM produktuak WHERE id IN ($ids)";
        $resultado_compra = $conexion->query($sql_produktuak);
        
        $total_compra = 0;
        
        // 3. INSERTAR CADA PRODUCTO EN karrito_produktuak
        while ($p = $resultado_compra->fetch_assoc()) {
            $produktu_id = $p['id'];
            $kopurua = $_SESSION['saskia'][$produktu_id];
            $prezioa = $p['prezioa'];
            
            $sql_insert = "INSERT INTO karrito_produktuak (karrito_id, produktu_id, kopurua, prezioa_unitarioa) 
                           VALUES (?, ?, ?, ?)";
            $stmt_prod = $conexion->prepare($sql_insert);
            $stmt_prod->bind_param("iiid", $karrito_id, $produktu_id, $kopurua, $prezioa);
            $stmt_prod->execute();
            
            $total_compra += ($prezioa * $kopurua);
        }
        
        // 4. COMMIT
        $conexion->commit();
        
        // 5. MENSAJE DE ÉXITO
        $mensaje_exito = "Erosketa arrakastaz egin da! Guztira: {$total_compra}€";
        
        // 6. LIMPIAR SASKIA
        unset($_SESSION['saskia']);
        
    } catch (Exception $e) {
        $conexion->rollback();
        die("Errorea erosketarekin: " . $e->getMessage());
    }
}

/* PRODUKTUA KENDU */
if (isset($_POST['kendu_id'])) {
    $id = (int)$_POST['kendu_id'];
    if (isset($_SESSION['saskia'][$id])) {
        unset($_SESSION['saskia'][$id]);
    }
}

/* PRODUKTUA JARRI */
if (isset($_POST['produktua_id'])) {
    $id = (int)$_POST['produktua_id'];
    if (!isset($_SESSION['saskia'])) $_SESSION['saskia'] = [];

    if (isset($_SESSION['saskia'][$id])) {
        $_SESSION['saskia'][$id]++;
    } else {
        $_SESSION['saskia'][$id] = 1;
    }
}

// Si ya se procesó la compra, mostrar solo el mensaje
if ($mensaje_exito) {
    ?>
    <!DOCTYPE html>
    <html lang="eu">
    <head>
        <meta charset="UTF-8">
        <title>Erosketa Eginda</title>
        <link rel="stylesheet" href="CSS_Erronka.css">
    </head>
    <body>
        <header>
            <?php include_once "navbar.php"; ?>
        </header>
        
        <main class="confirmacion-main">
            <h1>Eskerrik asko!</h1>
            <h2><?= $mensaje_exito ?></h2>
            
            <p>Zure erosketa arrakastaz gorde da.</p>
            
            <div class="confirmacion-botones">
                <a href="katalogoa.php" class="btn-katalogo">Katalogora itzuli</a>
                <a href="SARRERA.php" class="btn-hasiera">Hasierara itzuli</a>
            </div>
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
        </footer>
    </body>
    </html>
    <?php
    exit;
}

// Si el carrito está vacío
if (!isset($_SESSION['saskia']) || empty($_SESSION['saskia'])) {
    ?>
    <!DOCTYPE html>
    <html lang="eu">
    <head>
        <meta charset="UTF-8">
        <title>Karritoa</title>
        <link rel="stylesheet" href="CSS_Erronka.css">
    </head>
    <body>
        <header>
            <?php include_once "navbar.php"; ?>
        </header>
        
        <h1 class="karrito-hutsik">Karritoa hutsik dago</h1>
        <p class="karrito-link"><a href="katalogoa.php">Katalogora itzuli</a></p>
        
        <footer>
            <h3>ENPRESA KOLABORATIBOAK</h3>
            <div class="sarrera-grid">
                <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/8/82/Dell_Logo.png" alt="Dell">
                <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Lenovo_Global_Corporate_Logo.png/960px-Lenovo_Global_Corporate_Logo.png" alt="Lenovo">
                <img class="logo-empresa" src="img/Apple logo.png" alt="Apple">
                <img class="logo-empresa" src="img/Microsoft logo.png" alt="Microsoft">
            </div>
        </footer>
    </body>
    </html>
    <?php
    exit;
}

// Mostrar el carrito
$ids = implode(",", array_keys($_SESSION['saskia']));
$sql = "SELECT id, izena, prezioa, argazkia FROM produktuak WHERE id IN ($ids)";
$resultado = $conexion->query($sql);

$total = 0;
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Karritoa</title>
    <link rel="stylesheet" href="CSS_Erronka.css">
</head>
<body>
    <header>
        <?php include_once "navbar.php"; ?>
    </header>
    
    <h1 class="karrito-titulo">Karritoa</h1>

    <table border="1" class="karrito-tabla">
        <thead>
            <tr>
                <th>Irudia</th>
                <th>Produktua</th>
                <th>Kantitatea</th>
                <th>Prezioa</th>
                <th>Subtotal</th>
                <th>Ekintza</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($p = $resultado->fetch_assoc()): 
                $id = $p['id'];
                $kantitatea = $_SESSION['saskia'][$id];
                $subtotal = $p['prezioa'] * $kantitatea;
                $total += $subtotal;
            ?>
            <tr>
                <td><img src="<?= htmlspecialchars($p['argazkia']) ?>" class="karrito-img"></td>
                <td><?= htmlspecialchars($p['izena']) ?></td>
                <td><?= $kantitatea ?></td>
                <td><?= $p['prezioa'] ?> €</td>
                <td><?= number_format($subtotal, 2) ?> €</td>
                <td>
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="kendu_id" value="<?= $id ?>">
                        <button type="submit">Kendu</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="karrito-total">Guztira</td>
                <td colspan="2"><?= number_format($total, 2) ?> €</td>
            </tr>
        </tfoot>
    </table>

    <form method="POST" class="karrito-form">
        <button type="submit" name="ordainketa" class="ordaindu-btn">Ordaindu</button>
    </form>

    <p class="karrito-link"><a href="katalogoa.php">Katalogora itzuli</a></p>
    
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
    </footer>
</body>
</html>