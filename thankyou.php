<?php /* thankyou.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thank You — HandCraft</title>
<style>
body{margin:0;background:#0e1124;color:#eef0ff;font-family:Inter,system-ui;display:grid;place-items:center;min-height:100vh}
.card{background:#14183a;border:1px solid #2a2f63;border-radius:20px;box-shadow:0 16px 40px rgba(0,0,0,.45);padding:26px;max-width:640px;margin:20px}
h1{margin:0 0 8px}
.badge{display:inline-block;background:#121737;border:1px solid #2a2f63;color:#cfd3ff;border-radius:999px;padding:6px 10px;font-size:12px}
.btn{display:inline-block;margin-top:14px;padding:10px 14px;border-radius:12px;border:1px solid #2a2f63;background:#1b2043;color:#cfd3ff;text-decoration:none}
.price{color:#2ee6a6;font-weight:800}
.row{display:flex;justify-content:space-between;margin:6px 0;color:#cfd3ff}
</style>
</head>
<body>
  <div class="card">
    <h1>Shukriya! 🎉</h1>
    <p>Your order <span class="badge" id="oid"></span> has been placed successfully.</p>
    <div id="summary"></div>
    <a class="btn" href="#" onclick="goto('home.php')">Back to Home</a>
  </div>

<script>
function goto(p){window.location.assign(p);}
function orders(){ return JSON.parse(localStorage.getItem('etsy_orders')||'[]'); }

(function show(){
  const url=new URL(window.location.href);
  const id=url.searchParams.get('order');
  document.getElementById('oid').textContent=id||'N/A';
  const o=orders().find(x=>x.id===id);
  if(!o){ document.getElementById('summary').innerHTML='<p style="color:#9aa0c3">No order details found.</p>'; return; }
  const items=o.items.map(x=>`<div class="row"><div>${x.title} × ${x.qty}</div><div>SAR ${(x.qty*x.price).toFixed(2)}</div></div>`).join('');
  const total=`<div class="row"><div>Total</div><div class="price">SAR ${o.total.toFixed(2)}</div></div>`;
  document.getElementById('summary').innerHTML=items+total;
})();
</script>
</body>
</html>
