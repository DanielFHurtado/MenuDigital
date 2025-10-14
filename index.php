<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pancho Rápidas - Menú Digital</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="topbar">✨ Bienvenido a Pancho Rápidas - Escanea, elige y ordena 🍟</div>

  <header class="hero">
    <div class="hero-overlay"></div>
    <img src="hero-burger.jpg" class="hero-bg" alt="Hero Pancho Rápidas" onerror="this.style.display='none'">
    <div class="hero-content">
      <img src="logo.png" alt="Logo Pancho Rápidas" class="logo" onerror="this.style.display='none'">
      <div class="hero-text">
        <h1>Pancho Rápidas</h1>
        <p>Las mejores salchipapas, alitas y bebidas — directo a tu mesa</p>
        <a href="#menu" class="btn-primary">Ver menú</a>
      </div>
    </div>

    <nav class="categories" aria-label="Categorías">
      <div id="category-list" class="category-list"></div>
    </nav>
  </header>

  <main id="menu" class="container">
    <!-- aquí se renderiza el menú desde menu.json -->
  </main>

  <!-- carrito flotante -->
  <button id="cart-toggle" class="cart-toggle" aria-label="Abrir carrito">
    🛒 <span id="cart-count" class="badge">0</span>
  </button>

  <aside id="cart-panel" class="cart-panel" aria-hidden="true">
    <div class="cart-header">
      <h3>🛒 Mi Pedido</h3>
      <button id="cart-close" class="close" aria-label="Cerrar">✕</button>
    </div>

    <ul id="cart-items" class="cart-items"></ul>

    <div class="cart-footer">
      <div class="total-line">
        <span>Total:</span>
        <strong id="cart-total">$0</strong>
      </div>

      <label for="mesa">Mesa</label>
      <input id="mesa" type="number" min="1" placeholder="N° mesa" />

      <label for="notes">Instrucciones (opcional)</label>
      <textarea id="notes" rows="2" placeholder="Sin cebolla, salsas aparte..."></textarea>

      <div style="display:flex;gap:8px;margin-top:10px;">
        <button id="clear-cart" class="btn-secondary">Vaciar</button>
        <button id="send-order" class="send-btn">📲 Enviar por WhatsApp</button>
      </div>
    </div>
  </aside>

  <div id="toast" class="toast" aria-live="polite"></div>

  <script src="script.js" defer></script>
</body>
</html>
