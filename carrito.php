<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Manejo del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $producto_id = $_POST['producto_id'];
    $action = isset($_POST['action']) ? $_POST['action'] : 'add';

    if ($action === 'update') {
        // Actualizar la cantidad del producto
        $cantidad = intval($_POST['cantidad']);
        if ($cantidad > 0) {
            $stmt = $pdo->prepare("UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?");
            $stmt->execute([$cantidad, $usuario_id, $producto_id]);
        } else {
            // Si la cantidad es 0, eliminar el producto
            $stmt = $pdo->prepare("DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?");
            $stmt->execute([$usuario_id, $producto_id]);
        }
    } elseif ($action === 'delete') {
        // Eliminar el producto del carrito
        $stmt = $pdo->prepare("DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?");
        $stmt->execute([$usuario_id, $producto_id]);
    } elseif ($action === 'add') {
        // Agregar producto al carrito
        $stmt = $pdo->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + 1");
        $stmt->execute([$usuario_id, $producto_id]);
    }
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
    <link rel="stylesheet" href="carrito.css">
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($carrito as $item): ?>
                <?php $subtotal = $item['cantidad'] * $item['precio']; ?>
                <?php $total += $subtotal; ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td>
                        <form action="carrito.php" method="POST" style="display:inline;">
                            <input type="hidden" name="producto_id" value="<?= $item['producto_id'] ?>">
                            <input type="hidden" name="action" value="update">
                            <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" style="width: 50px;">
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($item['precio']) ?>€</td>
                    <td><?= $subtotal ?>€</td>
                    <td>
                        <form action="carrito.php" method="POST" style="display:inline;">
                            <input type="hidden" name="producto_id" value="<?= $item['producto_id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Total: <?= $total ?>€</h2>
    <h4><a href="index.php">Volver</a></h4>
</body>
</html>