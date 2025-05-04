<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Manejo del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)
    ON DUPLICATE KEY UPDATE cantidad = cantidad + 1");
    $stmt->execute([$usuario_id, $producto_id]);
}

// Obtener productos del carrito
$stmt = $pdo->prepare("SELECT c.*, p.nombre, p.precio FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>
    <h1>Carrito de Compras</h1>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($carrito as $item): ?>
                <?php $subtotal = $item['cantidad'] * $item['precio']; ?>
                <?php $total += $subtotal; ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= htmlspecialchars($item['cantidad']) ?></td>
                    <td><?= htmlspecialchars($item['precio']) ?>€</td>
                    <td><?= $subtotal ?>€</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Total: <?= $total ?>€</h2>

    <h4>
        <a href="index.php">Volver</a>
    </h4>
</body>
</html>