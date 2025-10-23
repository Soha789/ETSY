<?php /* cart.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Your Cart — HandCraft</title>
<style>
body{margin:0;background:#0e1124;color:#eef0ff;font-family:Inter,system-ui}
.wrap{max-width:1000px;margin:0 auto;padding:22px}
.top{display:flex;justify-content:space-between;align-items:center}
.btn{padding:10px 14px;border-radius:12px;border:1px solid #2a2f63;background:#1b2043;color:#cfd3ff;cursor:pointer;text-decoration:none}
.table{margin-top:16px;background:#14183a;border:1px solid #2a2f63;border-radius:16px;overflow:hidden}
.row{display:grid;grid-template-columns:80px 1fr 120px 120px 120px;gap:10px;align-items:center;padding:10px 12px;border-bottom:1px solid #2b3166}
.row.header{background:#121737;color:#b8bff2;font-size:12px}
.row img{width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid #2b3166}
.total{display:flex;justify-content:flex-end;gap:12px;margin-top:16px;align-items:center}
.price{color:#2ee6a6;font-weight:800}
input[type=number]{width:80px;padding:10px;border-radius:10px;border:1px solid #2b3166;background:#0f1433;color:#fff}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <h2>Your Cart</h2>
    <div>
      <a class="btn" href="#" onclick="goto('home.php')">Continue shopping</a>
    </div>
  </div>

  <div class="table" id="table"></div>

  <div class="total">
    <div>Subtotal:</div>
    <div class="price" id="subtotal">SAR 0.00</div>
    <button class="btn" onclick="checkout()">Proceed to Checkout</button>
  </div>
</div>

<script>
function goto(p){window.location.assign(p);}
function me(){ return JSON.parse(localStorage.getItem('etsy_current_user')||'null'); }
function cartKey(){ const u=me(); return 'etsy_cart_'+(u?u.id:'guest'); }
function cart(){ return JSON.parse(localStorage.getItem(cartKey())||'[]'); }
function setCart(c){ localStorage.setItem(cartKey(), JSON.stringify(c)); }

(function ensure(){ if(!me()){ alert('Please login first.'); goto('login.php'); }})();

function render(){
  const c=cart();
  const table=document.getElementById('table');
  if(c.length===0){ table.innerHTML='<div style="padding:18px;color:#9aa0c3">Your cart is empty.</div>'; document.getElementById('subtotal').textContent='SAR 0.00'; return; }
  const head=`<div class="row header"><div></div><div>Item</div><div>Price</div><div>Qty</div><div>Total</div></div>`;
  const rows=c.map(x=>`
    <div class="row">
      <img src="${x.img}">
      <div>${x.title}</div>
      <div>SAR ${x.price.toFixed(2)}</div>
      <div><input type="number" min="1" value="${x.qty}" onchange="upd('${x.id}',this.value)"></div>
      <div>SAR ${(x.qty*x.price).toFixed(2)} <a href="#" onclick="rem('${x.id}')" style="margin-left:8px;color:#ffb0b0">Remove</a></div>
    </div>
  `).join('');
  table.innerHTML=head+rows;
  const sum=c.reduce((a,b)=>a+b.qty*b.price,0);
  document.getElementById('subtotal').textContent='SAR '+sum.toFixed(2);
}
render();

function upd(id,v){
  v=parseInt(v||'1',10);
  let c=cart(); const i=c.findIndex(x=>x.id===id); if(i>-1){ c[i].qty=v; setCart(c); render(); }
}
function rem(id){
  let c=cart().filter(x=>x.id!==id); setCart(c); render();
}
function checkout(){ if(cart().length===0){alert('Cart is empty'); return;} goto('checkout.php'); }
</script>
</body>
</html>
