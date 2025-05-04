<?php
require 'config.php';
session_start();

// Obtener productos desde la base de datos
$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>

<header>
    <div class="logo">
        <h1>Mi Tienda Pokémon</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="carrito.php">Carrito</a></li>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>


    <!-- CONTENIDO -->
    <main>
        <h2>Catálogo de Productos</h2>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <p>Bienvenido, usuario.</p>
        <?php endif; ?>
        <div id="productos">
            <?php foreach ($productos as $producto): ?>
                <div class="producto">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" width="100">
                    <p>Precio: <?= htmlspecialchars($producto['precio']) ?>€</p>
                    <form action="carrito.php" method="POST">
                        <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                        <button type="submit">Añadir al Carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; <?= date('Y') ?> Mi Tienda Pokémon. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
