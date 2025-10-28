<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['cedula'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú - Pancho Rápidas</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="topbar">
  Bienvenido <?= htmlspecialchars($_SESSION['nombre']) ?>
  <a href="logout.php" style="
      float:right;
      background:#c0392b;
      color:#fff;
      padding:6px 10px;
      border-radius:6px;
      text-decoration:none;
      margin-left:10px;
  ">Cerrar sesión</a>
</div>


  <!-- HERO -->
  <section class="hero">
    <img src="logo.png" alt="Pancho Rápidas" class="hero-bg">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <img src="logo.png" alt="Logo" class="logo">
      <div class="hero-text">
        <h1>Pancho Rápidas</h1>
        <p>Tu menú digital. ¡Haz tu pedido fácilmente!</p>
        <a href="#menu" class="btn-primary">Ver Menú</a>
      </div>
    </div>
  </section>

  <!-- CATEGORÍAS -->
  <section class="categories">
    <div id="category-list" class="category-list"></div>
  </section>

  <!-- MENÚ -->
  <main id="menu" class="container"></main>

  <!-- BOTÓN CARRITO -->
  <button id="cart-toggle" class="cart-toggle">
    🛒 <span id="cart-count" class="badge">0</span>
  </button>

  <!-- PANEL CARRITO -->
  <aside id="cart-panel" class="cart-panel" aria-hidden="true">
    <div class="cart-header">
      <h2>Tu Pedido</h2>
      <button id="cart-close">✕</button>
    </div>

    <ul id="cart-items" class="cart-items"></ul>

    <div class="cart-footer">
      <div>Total: <span id="cart-total">$0</span></div>
      <input type="text" id="mesa" placeholder="Número de mesa">
      <textarea id="notes" placeholder="Notas o instrucciones (opcional)"></textarea>
      <button id="send-order" class="send-btn">📲 Enviar Pedido</button>
      <button id="clear-cart" class="btn-secondary">Vaciar carrito</button>
    </div>
  </aside>

  <!-- TOAST -->
  <div id="toast" class="toast"></div>

  <!-- SCRIPTS -->
  <script src="script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
