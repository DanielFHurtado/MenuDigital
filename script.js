// Número de WhatsApp fijo (incluye código de país)
const WHATSAPP_NUMBER = "573133493689";

// ===========================================================
//  REFERENCIAS DEL DOM
// ===========================================================

// Elementos del HTML usados en la interfaz
const categoryListEl = document.getElementById("category-list");
const menuEl = document.getElementById("menu");
const cartToggle = document.getElementById("cart-toggle");
const cartPanel = document.getElementById("cart-panel");
const cartClose = document.getElementById("cart-close");
const cartItemsEl = document.getElementById("cart-items");
const cartTotalEl = document.getElementById("cart-total");
const cartCountEl = document.getElementById("cart-count");
const toastEl = document.getElementById("toast");
const sendBtn = document.getElementById("send-order");
const clearBtn = document.getElementById("clear-cart");

// ===========================================================
//  ESTADO DE LA APLICACIÓN
// ===========================================================

// Objeto que representa el carrito { id: cantidad }
let cart = loadCart(); 
// Objeto con la información del menú cargado desde menu.json
let menuData = {};     

// ===========================================================
//  UTILIDADES
// ===========================================================

// Da formato de moneda colombiana (ej. $12.500)
const formatCurrency = n => "$" + n.toLocaleString("es-CO");

// ===========================================================
//  FUNCIÓN PRINCIPAL DE INICIO
// ===========================================================

async function init() {
  try {
    // Se carga el menú desde el archivo JSON
    const resp = await fetch("menu.json");
    menuData = await resp.json();
  } catch (e) {
    showToast("No se pudo cargar el menú (menu.json).");
    console.error(e);
    return;
  }

  // Renderiza las secciones principales
  renderCategories();
  renderMenu();
  renderCart();
}

// ===========================================================
// RENDERIZAR CATEGORÍAS
// ===========================================================

function renderCategories() {
  categoryListEl.innerHTML = "";
  const cats = Object.keys(menuData); // Extrae nombres de categorías

  cats.forEach((cat, idx) => {
    const btn = document.createElement("button");
    btn.className = "cat-btn";
    btn.textContent = cat;

    // Al hacer clic, hace scroll a la sección correspondiente
    btn.onclick = () => {
      document.getElementById("section-" + slug(cat))
        ?.scrollIntoView({ behavior: "smooth", block: "start" });

      // Marca como activa la categoría seleccionada
      document.querySelectorAll(".cat-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    };

    // Marca la primera categoría como activa por defecto
    if (idx === 0) btn.classList.add("active");
    categoryListEl.appendChild(btn);
  });
}

// ===========================================================
// 🍔 RENDERIZAR MENÚ COMPLETO
// ===========================================================

function renderMenu() {
  menuEl.innerHTML = "";
  const container = document.createElement("div");
  container.className = "container-inner";

  // Recorre las categorías y sus productos
  Object.entries(menuData).forEach(([category, items]) => {
    const section = document.createElement("section");
    section.id = "section-" + slug(category);

    // Título de la sección
    const title = document.createElement("div");
    title.className = "section-title";
    title.innerHTML = `<strong>${category}</strong>`;
    section.appendChild(title);

    const grid = document.createElement("div");
    grid.className = "products-grid";

    // Recorre los productos de cada categoría
    items.forEach(item => {
      const card = document.createElement("article");
      card.className = "card";

      // Imagen del producto
      const img = document.createElement("div");
      img.className = "card-img";
      if (item.img) {
        const i = document.createElement("img");
        i.src = item.img;
        i.alt = item.name;
        i.style.width = "100%";
        i.style.height = "100%";
        i.style.objectFit = "cover";
        i.onerror = () => { i.style.display = "none"; };
        img.appendChild(i);
      }
      card.appendChild(img);

      // Nombre del producto
      const h = document.createElement("h3");
      h.textContent = item.name;
      card.appendChild(h);

      // Descripción (si existe)
      if (item.desc) {
        const pdesc = document.createElement("p");
        pdesc.textContent = item.desc;
        card.appendChild(pdesc);
      }

      // Precio
      const pr = document.createElement("div");
      pr.className = "price";
      pr.textContent = formatCurrency(item.price);
      card.appendChild(pr);

      // Botón para agregar al carrito
      const actions = document.createElement("div");
      actions.className = "actions";

      const addBtn = document.createElement("button");
      addBtn.className = "add-btn";
      addBtn.textContent = "Agregar";
      addBtn.onclick = () => { addToCart(item.id); };

      actions.appendChild(addBtn);
      card.appendChild(actions);
      grid.appendChild(card);
    });

    section.appendChild(grid);
    menuEl.appendChild(section);
  });
}

// ===========================================================
// 🛒 FUNCIONALIDAD DEL CARRITO
// ===========================================================

// Carga carrito guardado en localStorage
function loadCart() {
  try {
    const raw = localStorage.getItem("pancho_cart_v2");
    return raw ? JSON.parse(raw) : {};
  } catch (e) { return {}; }
}

// Guarda el carrito actual
function saveCart() {
  localStorage.setItem("pancho_cart_v2", JSON.stringify(cart));
}

// Agregar producto
function addToCart(id, qty = 1) {
  if (!cart[id]) cart[id] = 0;
  cart[id] += qty;
  saveCart();
  renderCart();
  showToast("Agregado al carrito");
}

// Eliminar producto
function removeFromCart(id) {
  delete cart[id];
  saveCart();
  renderCart();
  showToast("Eliminado");
}

// Cambiar cantidad de producto
function changeQty(id, delta) {
  if (!cart[id]) return;
  cart[id] += delta;
  if (cart[id] <= 0) delete cart[id];
  saveCart();
  renderCart();
}

// Vaciar carrito completo
function clearCart() {
  cart = {};
  saveCart();
  renderCart();
}

// ===========================================================
// RENDERIZAR PANEL DEL CARRITO
// ===========================================================

function renderCart() {
  cartItemsEl.innerHTML = "";
  let total = 0;
  let count = 0;

  // Recorre productos del carrito
  for (const [id, qty] of Object.entries(cart)) {
    const item = findItemById(id);
    if (!item) continue;
    count += qty;
    const subtotal = item.price * qty;
    total += subtotal;

    const li = document.createElement("li");
    li.className = "cart-item";
    li.innerHTML = `
      <div class="meta">
        <strong>${item.name}</strong>
        <small>${formatCurrency(item.price)} · Subtotal: ${formatCurrency(subtotal)}</small>
      </div>
    `;

    // Controles de cantidad (+ / −) y botón de eliminar
    const right = document.createElement("div");
    right.style.display = "flex";
    right.style.flexDirection = "column";
    right.style.alignItems = "flex-end";
    right.style.gap = "6px";

    const qtyControls = document.createElement("div");
    qtyControls.className = "qty-controls";

    const dec = document.createElement("button");
    dec.textContent = "−";
    dec.onclick = () => changeQty(id, -1);

    const qtyBox = document.createElement("div");
    qtyBox.style.padding = "6px 8px";
    qtyBox.style.borderRadius = "8px";
    qtyBox.style.background = "#f4f4f4";
    qtyBox.textContent = qty;

    const inc = document.createElement("button");
    inc.textContent = "+";
    inc.onclick = () => changeQty(id, 1);

    qtyControls.appendChild(dec);
    qtyControls.appendChild(qtyBox);
    qtyControls.appendChild(inc);

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-btn";
    removeBtn.textContent = "Eliminar";
    removeBtn.onclick = () => removeFromCart(id);

    right.appendChild(qtyControls);
    right.appendChild(removeBtn);
    li.appendChild(right);

    cartItemsEl.appendChild(li);
  }

  // Muestra totales
  cartTotalEl.textContent = formatCurrency(total);
  cartCountEl.textContent = count;
}

// ===========================================================
// BÚSQUEDA DE PRODUCTOS POR ID
// ===========================================================

function findItemById(id) {
  for (const items of Object.values(menuData)) {
    for (const it of items) {
      if (it.id === id) return it;
    }
  }
  return null;
}

// ===========================================================
// ENVÍO DE PEDIDO POR WHATSAPP
// ===========================================================

// Construye el mensaje
function buildMessage() {
  const mesa = document.getElementById("mesa").value.trim();
  const notes = document.getElementById("notes").value.trim();

  if (!mesa) { alert("Por favor ingresa el número de mesa."); return null; }
  const entries = Object.entries(cart);
  if (entries.length === 0) { alert("El carrito está vacío."); return null; }

  let total = 0;
  let text = `Hola, este es mi Pedido de la Mesa: ${mesa}\n\nPedido:\n`;
  entries.forEach(([id, qty]) => {
    const item = findItemById(id);
    if (!item) return;
    const subtotal = item.price * qty;
    total += subtotal;
    text += `- ${item.name} x${qty} = ${formatCurrency(subtotal)}\n`;
  });

  text += `\nTotal: ${formatCurrency(total)}\n`;
  if (notes) text += `\nInstrucciones: ${notes}\n`;
  return text;
}

// Envía el mensaje a WhatsApp
function sendOrder() {
  const message = buildMessage();
  if (!message) return;
  const url = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
  window.open(url, "_blank");
  // clearCart(); // opcional si se desea limpiar el carrito al enviar
}

// ===========================================================
// NOTIFICACIONES Y UTILIDADES
// ===========================================================

// Muestra notificación tipo “toast”
function showToast(txt = "") {
  toastEl.textContent = txt;
  toastEl.classList.add("show");
  setTimeout(()=> toastEl.classList.remove("show"), 1400);
}

// Genera slugs (ids) a partir de nombres de categoría
function slug(s) { 
  return s.toLowerCase().replace(/\s+/g, "-").replace(/[^\w-]/g, ""); 
}

// ===========================================================
// EVENTOS DE INTERFAZ
// ===========================================================

// Abre/cierra panel del carrito
cartToggle.addEventListener("click", ()=> {
  cartPanel.classList.toggle("open");
  cartPanel.setAttribute("aria-hidden", cartPanel.classList.contains("open") ? "false" : "true");
});

// Cierra carrito
cartClose.addEventListener("click", ()=> { 
  cartPanel.classList.remove("open"); 
  cartPanel.setAttribute("aria-hidden","true"); 
});

// Botón de enviar pedido
sendBtn.addEventListener("click", sendOrder);

// Botón de vaciar carrito
clearBtn.addEventListener("click", ()=> { 
  if (confirm("¿Vaciar carrito?")) clearCart(); 
});

// ===========================================================
// EJECUCIÓN INICIAL
// ===========================================================
init();
