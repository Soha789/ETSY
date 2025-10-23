<?php /* checkout.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Checkout — HandCraft</title>
<style>
body{margin:0;background:#0e1124;color:#eef0ff;font-family:Inter,system-ui}
.wrap{max-width:980px;margin:0 auto;padding:22px}
.grid{display:grid;grid-template-columns:1fr .8fr;gap:16px}
.card{background:#14183a;border:1px solid #2a2f63;border-radius:18px;padding:16px}
label{font-size:12px;color:#b8bff2}
input,textarea{width:100%;padding:12px;margin:6px 0 12px;border-radius:12px;border:1px solid #2b3166;background:#0f1433;color:#fff}
.btn{padding:12px 16px;border-radius:12px;border:1px solid #2a2f63;background:#1b2043;color:#cfd3ff;cursor:pointer}
.row{display:flex;justify-content:space-between;align-items:center;margin:6px 0}
.price{color:#2ee6a6;font-weight:800}
@media(max-width:900px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <h2>Checkout</h2>
  <div class="grid">
    <div class="card">
      <h3>Shipping</h3>
      <label>Full name</label><input id="name" placeholder="Your name">
      <label>Address</label><textarea id="addr" placeholder="Street, City, Country"></textarea>
      <label>Phone</label><input id="phone" placeholder="+966…">
      <h3>Payment (Dummy)</h3>
      <label>Card holder</label><input id="ch" placeholder="As on card">
      <label>Card number</label><input id="cn" placeholder="0000 0000 0000 0000">
      <label>Expiry / CVV</label>
      <div style="display:flex;gap:10px">
        <input id="ex" placeholder="MM/YY">
        <input id="cvv" placeholder="123">
      </div>
      <button class="btn" onclick="placeOrder()">Place order</button>
      <button class="btn" onclick="goto('cart.php')" style="margin-left:8px">Back to cart</button>
    </div>
    <div class="card">
      <h3>Order Summary</h3>
      <div id="sum"></div>
      <div class="row"><div>Subtotal</div><div class="price" id="subtotal">SAR 0.00</div></div>
      <div class="row"><div>Shipping</div><div>Free</div></div>
      <div class="row"><div>Total</div><div class="price" id="total">SAR 0.00</div></div>
    </div>
  </div>
</div>

<script>
function goto(p){window.location.assign(p);}
function me(){ return JSON.parse(localStorage.getItem('etsy_current_user')||'null'); }
function cartKey(){ const u=me(); return 'etsy_cart_'+(u?u.id:'guest'); }
function cart(){ return JSON.parse(localStorage.getItem(cartKey())||'[]'); }
function setCart(c){ localStorage.setItem(cartKey(), JSON.stringify(c)); }
function orders(){ return JSON.parse(localStorage.getItem('etsy_orders')||'[]'); }
function setOrders(o){ localStorage.setItem('etsy_orders', JSON.stringify(o)); }

(function ensure(){ if(!me()){ alert('Please login first.'); goto('login.php'); }})();
(function render(){
  const c=cart(); const sum=c.reduce((a,b)=>a+b.qty*b.price,0);
  document.getElementById('sum').innerHTML=c.map(x=>`<div class="row"><div>${x.title} × ${x.qty}</div><div>SAR ${(x.qty*x.price).toFixed(2)}</div></div>`).join('')||'<div style="color:#9aa0c3">Cart empty.</div>';
  document.getElementById('subtotal').textContent='SAR '+sum.toFixed(2);
  document.getElementById('total').textContent='SAR '+sum.toFixed(2);
})();

function placeOrder(){
  const name=document.getElementById('name').value.trim();
  const addr=document.getElementById('addr').value.trim();
  const phone=document.getElementById('phone').value.trim();
  if(!name||!addr||!phone){ alert('Please fill shipping details.'); return; }
  const c=cart(); if(c.length===0){ alert('Cart empty.'); goto('home.php'); return; }
  const order={
    id: 'ORD-'+Math.random().toString(36).slice(2,8).toUpperCase(),
    user: me(),
    items: c,
    total: c.reduce((a,b)=>a+b.qty*b.price,0),
    date: new Date().toISOString()
  };
  const o=orders(); o.push(order); setOrders(o);
  setCart([]);
  goto('thankyou.php?order='+order.id);
}
</script>
</body>
</html>
