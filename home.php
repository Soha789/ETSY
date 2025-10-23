<?php /* home.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Home — HandCraft Marketplace</title>
<style>
:root{--bg:#0e1124;--card:#14183a;--border:#2a2f63;--muted:#9aa0c3;--text:#eef0ff;--accent:#7c5cff;--accent2:#2ee6a6}
*{box-sizing:border-box} body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,system-ui}
.wrap{max-width:1200px;margin:0 auto;padding:24px}
.top{display:flex;align-items:center;justify-content:space-between;gap:10px}
.brand{display:flex;gap:10px;align-items:center}
.badge{width:34px;height:34px;background:conic-gradient(#7c5cff,#2ee6a6,#7c5cff);border-radius:12px}
.top .actions{display:flex;gap:8px}
.btn{padding:10px 14px;border-radius:12px;border:1px solid var(--border);background:#1b2043;color:#cfd3ff;text-decoration:none;cursor:pointer}
.bar{display:flex;gap:10px;margin:18px 0}
input,select{padding:12px;border-radius:12px;border:1px solid var(--border);background:#0f1433;color:#fff;outline:none}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.card{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
.card img{width:100%;height:170px;object-fit:cover;display:block}
.px{padding:12px}
h4{margin:0 0 6px}
.price{color:var(--accent2);font-weight:800}
.row{display:flex;justify-content:space-between;align-items:center}
.tag{font-size:12px;background:#121737;border:1px solid var(--border);padding:4px 8px;border-radius:999px;color:#cfd3ff}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.65);display:none;align-items:center;justify-content:center;padding:16px}
.modal .box{max-width:860px;width:100%;background:#0f1433;border:1px solid var(--border);border-radius:18px;overflow:hidden}
.modal .box .body{display:grid;grid-template-columns:1fr 1fr}
.modal img{width:100%;height:100%;object-fit:cover}
.modal .pad{padding:16px}
@media(max-width:1000px){.grid{grid-template-columns:repeat(2,1fr)} .modal .box .body{grid-template-columns:1fr}}
@media(max-width:560px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div class="brand"><div class="badge"></div><strong>HandCraft</strong></div>
    <div class="actions">
      <a class="btn" href="#" onclick="goto('dashboard.php')">Dashboard</a>
      <a class="btn" href="#" onclick="goto('cart.php')">Cart (<span id="cartCount">0</span>)</a>
      <a class="btn" href="#" onclick="logout()">Logout</a>
    </div>
  </div>

  <div class="bar">
    <input id="q" placeholder="Search products by name…" style="flex:1" oninput="render()">
    <select id="cat" onchange="render()">
      <option value="">All Categories</option>
      <option>Handmade</option>
      <option>Jewelry</option>
      <option>Home Decor</option>
      <option>Digital Products</option>
    </select>
    <select id="sort" onchange="render()">
      <option value="">Sort</option>
      <option value="price_asc">Price ↑</option>
      <option value="price_desc">Price ↓</option>
      <option value="rating_desc">Top Rated</option>
    </select>
  </div>

  <div class="grid" id="grid"></div>
</div>

<div class="modal" id="modal">
  <div class="box">
    <div class="body">
      <img id="mimg" alt="">
      <div class="pad">
        <h2 id="mtitle"></h2>
        <div class="row"><div class="tag" id="mcat"></div><div class="price" id="mprice"></div></div>
        <p id="mdesc" style="color:#cfd3ff"></p>
        <div style="margin-top:8px;color:#9aa0c3">Rating: <span id="mrate"></span> ★</div>
        <div style="margin-top:16px;display:flex;gap:10px">
          <input id="mqty" type="number" min="1" value="1" style="width:100px">
          <button class="btn" onclick="addModalToCart()">Add to cart</button>
          <button class="btn" onclick="closeModal()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function goto(p){window.location.assign(p);}
function me(){ return JSON.parse(localStorage.getItem('etsy_current_user')||'null'); }
function products(){ return JSON.parse(localStorage.getItem('etsy_products')||'[]'); }
function setProducts(p){ localStorage.setItem('etsy_products', JSON.stringify(p)); }
function cartKey(){ const u=me(); return 'etsy_cart_'+(u?u.id:'guest'); }
function cart(){ return JSON.parse(localStorage.getItem(cartKey())||'[]'); }
function setCart(c){ localStorage.setItem(cartKey(), JSON.stringify(c)); updateCartCount(); }

function ensureAuth(){
  if(!me()){ alert('Please login first.'); goto('login.php'); }
}
ensureAuth();

function updateCartCount(){ document.getElementById('cartCount').textContent=cart().reduce((a,b)=>a+b.qty,0); }
updateCartCount();

let current=null;

function openModal(p){
  current=p;
  document.getElementById('mimg').src=p.img;
  document.getElementById('mtitle').textContent=p.title;
  document.getElementById('mcat').textContent=p.category;
  document.getElementById('mprice').textContent='SAR '+p.price.toFixed(2);
  document.getElementById('mdesc').textContent=p.desc;
  document.getElementById('mrate').textContent=p.rating.toFixed(1);
  document.getElementById('mqty').value=1;
  document.getElementById('modal').style.display='flex';
}
function closeModal(){ document.getElementById('modal').style.display='none'; }
function addModalToCart(){
  const qty=parseInt(document.getElementById('mqty').value||'1',10);
  addToCart(current.id, qty);
  closeModal();
}

function addToCart(id,qty=1){
  const p=products().find(x=>x.id===id); if(!p){return;}
  let c=cart(); const i=c.findIndex(x=>x.id===id);
  if(i>-1){ c[i].qty+=qty; } else { c.push({id,qty,price:p.price,title:p.title,img:p.img}); }
  setCart(c);
  alert('Added to cart!');
}

function render(){
  const q=(document.getElementById('q').value||'').toLowerCase();
  const cat=(document.getElementById('cat').value||'');
  const sort=document.getElementById('sort').value;
  let list=products().filter(p=>p.title.toLowerCase().includes(q));
  if(cat) list=list.filter(p=>p.category===cat);
  if(sort==='price_asc') list.sort((a,b)=>a.price-b.price);
  if(sort==='price_desc') list.sort((a,b)=>b.price-a.price);
  if(sort==='rating_desc') list.sort((a,b)=>b.rating-a.rating);

  document.getElementById('grid').innerHTML=list.map(p=>`
    <div class="card">
      <img src="${p.img}" alt="">
      <div class="px">
        <h4>${p.title}</h4>
        <div class="row">
          <span class="tag">${p.category}</span>
          <span class="price">SAR ${p.price.toFixed(2)}</span>
        </div>
        <div style="color:#9aa0c3;margin:6px 0">★ ${p.rating.toFixed(1)}</div>
        <div style="display:flex;gap:8px">
          <button class="btn" onclick='openModal(${JSON.stringify(p)})'>View</button>
          <button class="btn" onclick="addToCart('${p.id}',1)">Add</button>
        </div>
      </div>
    </div>
  `).join('');
}
render();

function logout(){ localStorage.removeItem('etsy_current_user'); alert('Logged out.'); goto('index.php'); }
</script>
</body>
</html>
