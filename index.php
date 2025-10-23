<?php /* index.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>HandCraft — Your Etsy-style Marketplace</title>
<style>
:root{
  --bg:#0f1221; --card:#151935; --muted:#9aa0c3; --text:#eef0ff; --accent:#7c5cff; --accent2:#2ee6a6; --shadow:0 20px 40px rgba(0,0,0,.45);
  --ring:0 0 0 3px rgba(124,92,255,.35);
}
*{box-sizing:border-box} html,body{height:100%}
body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial; background:
radial-gradient(1200px 600px at 10% -10%, #1f2450 0%, transparent 60%),
radial-gradient(900px 500px at 110% 10%, #18204a 0%, transparent 60%),
linear-gradient(180deg,#0b0e1b 0%, #0f1221 100%); color:var(--text)}
.container{max-width:1120px;margin:0 auto;padding:28px}
.nav{display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;gap:10px;align-items:center;font-weight:800;letter-spacing:.5px}
.logo-badge{width:38px;height:38px;background:
conic-gradient(from 0deg,#7c5cff,#2ee6a6,#7c5cff); border-radius:12px;box-shadow:var(--shadow)}
.nav a.btn{padding:10px 16px;border-radius:12px;background:#1b2043;color:var(--text);text-decoration:none;border:1px solid #2a2f63}
.hero{display:grid;grid-template-columns:1.15fr .85fr;gap:28px;align-items:center;margin-top:22px}
.card{background:linear-gradient(180deg,#141a3e 0%, #101537 100%); border:1px solid #2b3166;border-radius:22px;box-shadow:var(--shadow)}
.hero .left{padding:34px}
.h-title{font-size:42px;line-height:1.1;margin:0 0 12px;font-weight:900}
.h-sub{color:var(--muted);font-size:16px;margin:0 0 22px}
.actions{display:flex;gap:12px;flex-wrap:wrap}
.btn{cursor:pointer;border:none;padding:12px 18px;border-radius:14px;font-weight:700}
.btn-primary{background:linear-gradient(135deg,var(--accent),#5a3df6); color:#fff; box-shadow:0 10px 30px rgba(124,92,255,.35)}
.btn-primary:focus{outline:none; box-shadow:var(--ring)}
.btn-ghost{background:#1b2043;color:#cfd3ff;border:1px solid #2a2f63}
.badges{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
.badge{background:#121737;border:1px solid #2a2f63;color:#cfd3ff;border-radius:999px;padding:8px 12px;font-size:12px}
.hero .right{padding:0;overflow:hidden;position:relative}
.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;padding:14px}
.item{border-radius:18px;overflow:hidden;background:#0e1230;border:1px solid #2b3166}
.item img{width:100%;height:160px;display:block;object-fit:cover}
.item .meta{padding:12px}
.item .meta h4{margin:0 0 4px;font-size:14px}
.item .meta .price{color:var(--accent2);font-weight:800}
.section{margin-top:36px}
.section h3{margin:0 0 14px}
.features{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.feature{padding:18px;border-radius:16px;background:#141a3d;border:1px solid #2a2f63}
.feature h4{margin:0 0 6px}
.footer{margin:36px 0 8px;color:var(--muted);font-size:13px;text-align:center}
@media(max-width:900px){.hero{grid-template-columns:1fr}}
</style>
</head>
<body>
  <div class="container">
    <div class="nav">
      <div class="logo"><div class="logo-badge"></div> HandCraft</div>
      <div>
        <a class="btn" href="#" onclick="goto('login.php')">Login</a>
        <a class="btn" href="#" onclick="goto('signup.php')">Sign Up</a>
      </div>
    </div>

    <div class="hero">
      <div class="left card">
        <h1 class="h-title">Buy & sell <span style="background:linear-gradient(90deg,#7c5cff,#2ee6a6);-webkit-background-clip:text;background-clip:text;color:transparent;">handmade, vintage & digital</span> goods</h1>
        <p class="h-sub">Showcase your craft, reach real buyers, and manage your shop with zero setup. Fast listings, secure accounts, and a delightful cart/checkout flow.</p>
        <div class="actions">
          <button class="btn btn-primary" onclick="goto('signup.php')">Create your shop</button>
          <button class="btn btn-ghost" onclick="goto('home.php')">Explore trending</button>
        </div>
        <div class="badges">
          <div class="badge">Zero DB • LocalStorage</div>
          <div class="badge">Image Upload (Base64)</div>
          <div class="badge">Search & Filters</div>
          <div class="badge">Cart & Checkout</div>
        </div>
      </div>

      <div class="right card">
        <div class="grid" id="trendingGrid"></div>
      </div>
    </div>

    <div class="section">
      <h3>Why join HandCraft?</h3>
      <div class="features">
        <div class="feature">
          <h4>Attract buyers</h4>
          <p style="color:var(--muted)">Beautiful product cards, ratings, and tags help your items shine.</p>
        </div>
        <div class="feature">
          <h4>Effortless selling</h4>
          <p style="color:var(--muted)">Add, edit, and manage inventory from your dashboard in seconds.</p>
        </div>
        <div class="feature">
          <h4>Delightful checkout</h4>
          <p style="color:var(--muted)">Sleek cart and dummy payment for smooth test purchases.</p>
        </div>
      </div>
    </div>

    <p class="footer">© HandCraft — Etsy-style demo built with love.</p>
  </div>

<script>
// ---------- minimal shared helpers ----------
function goto(p){ window.location.assign(p); }

// Seed demo products in localStorage if empty
(function seed(){
  const key='etsy_products';
  if(!localStorage.getItem(key)){
    const demo=[
      {id:crypto.randomUUID(),title:'Crochet Jelly Keychain',price:25.00,category:'Handmade',rating:4.8,qty:10,
       desc:'Soft crochet jellyfish keychain with pastel tassel.',
       img:'https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=1200&auto=format&fit=crop'},
      {id:crypto.randomUUID(),title:'Minimalist Ring',price:39.00,category:'Jewelry',rating:4.6,qty:8,
       desc:'Hand-polished stainless ring with mirror finish.',
       img:'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=1200&auto=format&fit=crop'},
      {id:crypto.randomUUID(),title:'Watercolor Prints (Set of 3)',price:19.00,category:'Digital Products',rating:4.9,qty:999,
       desc:'Printable A4 wall art — instant download.',
       img:'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop'},
      {id:crypto.randomUUID(),title:'Macrame Wall Hanging',price:45.00,category:'Home Decor',rating:4.7,qty:5,
       desc:'Boho macrame in natural cotton cord.',
       img:'https://images.unsplash.com/photo-1520975916090-3105956dac38?q=80&w=1200&auto=format&fit=crop'},
    ];
    localStorage.setItem(key, JSON.stringify(demo));
  }
})();

// Render trending
(function renderTrending(){
  const grid=document.getElementById('trendingGrid');
  const items=(JSON.parse(localStorage.getItem('etsy_products'))||[]).slice(0,4);
  grid.innerHTML=items.map(p=>`
    <div class="item">
      <img src="${p.img}" alt="">
      <div class="meta">
        <h4>${p.title}</h4>
        <div class="price">SAR ${p.price.toFixed(2)}</div>
      </div>
    </div>
  `).join('');
})();
</script>
</body>
</html>
