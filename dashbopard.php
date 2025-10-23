<?php /* dashboard.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — HandCraft</title>
<style>
body{margin:0;background:#0f1224;color:#eef0ff;font-family:Inter,system-ui}
.wrap{max-width:1100px;margin:0 auto;padding:22px}
.top{display:flex;justify-content:space-between;align-items:center}
.btn{padding:10px 14px;border-radius:12px;border:1px solid #2a2f63;background:#1b2043;color:#cfd3ff;cursor:pointer;text-decoration:none}
.grid{display:grid;grid-template-columns:1.1fr .9fr;gap:16px;margin-top:16px}
.card{background:#14183a;border:1px solid #2a2f63;border-radius:18px;padding:16px}
label{font-size:12px;color:#b8bff2}
input,select,textarea{width:100%;padding:12px;margin:6px 0 12px;border-radius:12px;border:1px solid #2b3166;background:#0f1433;color:#fff;outline:none}
textarea{min-height:100px;resize:vertical}
.list{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.item{background:#0f1433;border:1px solid #2b3166;border-radius:14px;overflow:hidden}
.item img{width:100%;height:140px;object-fit:cover}
.row{display:flex;justify-content:space-between;align-items:center;padding:8px 10px}
.price{color:#2ee6a6;font-weight:800}
@media(max-width:1000px){.grid{grid-template-columns:1fr}.list{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.list{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <h2>Seller Dashboard</h2>
    <div>
      <a class="btn" href="#" onclick="goto('home.php')">View Store</a>
      <a class="btn" href="#" onclick="goto('index.php')">Landing</a>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <h3>Add / Edit Product</h3>
      <input id="pid" type="hidden">
      <label>Title</label>
      <input id="title" placeholder="Product title">
      <label>Price (SAR)</label>
      <input id="price" type="number" step="0.01" placeholder="39.00">
      <label>Category</label>
      <select id="category">
        <option>Handmade</option><option>Jewelry</option><option>Home Decor</option><option>Digital Products</option>
      </select>
      <label>Quantity</label>
      <input id="qty" type="number" min="0" placeholder="10">
      <label>Rating (0–5)</label>
      <input id="rating" type="number" min="0" max="5" step="0.1" placeholder="4.8">
      <label>Description</label>
      <textarea id="desc" placeholder="Describe your product…"></textarea>
      <label>Image</label>
      <input id="img" type="file" accept="image/*" onchange="loadImg(event)">
      <div style="display:flex;gap:8px;margin-top:8px">
        <button class="btn" onclick="save()">Save</button>
        <button class="btn" onclick="resetForm()">Reset</button>
      </div>
    </div>

    <div class="card">
      <h3>Your Products</h3>
      <div class="list" id="list"></div>
    </div>
  </div>
</div>

<script>
function goto(p){window.location.assign(p);}
function me(){ return JSON.parse(localStorage.getItem('etsy_current_user')||'null'); }
function products(){ return JSON.parse(localStorage.getItem('etsy_products')||'[]'); }
function setProducts(p){ localStorage.setItem('etsy_products', JSON.stringify(p)); }

(function ensure(){ if(!me()){ alert('Please login first.'); goto('login.php'); }})();

let imageData=null;
function loadImg(e){
  const file=e.target.files[0]; if(!file) return;
  const fr=new FileReader(); fr.onload=()=>{ imageData=fr.result; }; fr.readAsDataURL(file);
}

function save(){
  const id=document.getElementById('pid').value || crypto.randomUUID();
  const p={
    id,
    title:document.getElementById('title').value.trim(),
    price:parseFloat(document.getElementById('price').value||'0'),
    category:document.getElementById('category').value,
    qty:parseInt(document.getElementById('qty').value||'0',10),
    rating:parseFloat(document.getElementById('rating').value||'0'),
    desc:document.getElementById('desc').value.trim(),
    img:imageData || (products().find(x=>x.id===id)?.img || '')
  };
  if(!p.title||!p.price||!p.img){ alert('Title, price and image are required.'); return; }

  const list=products();
  const i=list.findIndex(x=>x.id===id);
  if(i>-1) list[i]=p; else list.push(p);
  setProducts(list);
  render(); resetForm();
  alert('Saved!');
}

function resetForm(){
  document.getElementById('pid').value='';
  ['title','price','qty','rating','desc'].forEach(id=>document.getElementById(id).value='');
  imageData=null; document.getElementById('img').value='';
}

function del(id){
  if(!confirm('Delete this product?')) return;
  setProducts(products().filter(x=>x.id!==id));
  render();
}

function edit(id){
  const p=products().find(x=>x.id===id); if(!p) return;
  document.getElementById('pid').value=p.id;
  document.getElementById('title').value=p.title;
  document.getElementById('price').value=p.price;
  document.getElementById('category').value=p.category;
  document.getElementById('qty').value=p.qty;
  document.getElementById('rating').value=p.rating;
  document.getElementById('desc').value=p.desc;
  imageData=p.img;
  window.scrollTo({top:0,behavior:'smooth'});
}

function render(){
  const list=products();
  document.getElementById('list').innerHTML=list.map(p=>`
    <div class="item">
      <img src="${p.img}">
      <div class="row"><strong style="font-size:14px">${p.title}</strong><span class="price">SAR ${p.price.toFixed(2)}</span></div>
      <div class="row" style="color:#9aa0c3;padding-bottom:8px"><span>${p.category}</span><span>★ ${p.rating.toFixed(1)}</span></div>
      <div class="row" style="gap:8px">
        <button class="btn" onclick="edit('${p.id}')">Edit</button>
        <button class="btn" onclick="del('${p.id}')">Delete</button>
      </div>
    </div>
  `).join('');
}
render();
</script>
</body>
</html>
