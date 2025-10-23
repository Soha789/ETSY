<?php /* signup.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sign Up — HandCraft</title>
<style>
body{margin:0;background:#0e1124;color:#eaf0ff;font-family:Inter,system-ui}
.wrap{max-width:900px;margin:40px auto;padding:24px}
.card{background:#14183a;border:1px solid #2a2f63;border-radius:20px;box-shadow:0 16px 40px rgba(0,0,0,.45);padding:24px}
h1{margin:0 0 6px} p{color:#9aa0c3;margin:0 0 18px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
label{font-size:12px;color:#b8bff2}
input{width:100%;padding:12px;border-radius:12px;border:1px solid #2b3166;background:#0f1433;color:#fff;outline:none}
input:focus{box-shadow:0 0 0 3px rgba(124,92,255,.35)}
.actions{display:flex;gap:10px;margin-top:16px}
.btn{border:none;border-radius:12px;padding:12px 16px;font-weight:700;cursor:pointer}
.btn-primary{background:linear-gradient(135deg,#7c5cff,#5a3df6);color:#fff}
.btn-ghost{background:#1b2043;border:1px solid #2a2f63;color:#cfd3ff}
a{color:#9aa0c3}
.heroSide{display:none}
@media(min-width:900px){.grid2{display:grid;grid-template-columns:1.1fr .9fr;gap:20px}.heroSide{display:block}}
.hero{border-radius:18px;background:
radial-gradient(600px 400px at -10% 0%, #26308b 0%, transparent 60%),
linear-gradient(180deg,#18206b 0%, #14183a 100%); height:100%; padding:20px}
.hero h3{margin:0 0 10px}
.badge{display:inline-block;background:#121737;border:1px solid #2a2f63;border-radius:999px;padding:6px 10px;font-size:12px;margin:6px 6px 0 0;color:#cfd3ff}
</style>
</head>
<body>
<div class="wrap grid2">
  <div class="card">
    <h1>Join HandCraft</h1>
    <p>Create your account to start buying & selling.</p>
    <div class="grid">
      <div>
        <label>Full name</label>
        <input id="name" placeholder="Your name">
      </div>
      <div>
        <label>Email</label>
        <input id="email" type="email" placeholder="you@example.com">
      </div>
      <div>
        <label>Password</label>
        <input id="pass" type="password" placeholder="••••••••">
      </div>
      <div>
        <label>Confirm password</label>
        <input id="cpass" type="password" placeholder="••••••••">
      </div>
    </div>
    <div class="actions">
      <button class="btn btn-primary" onclick="signup()">Create account</button>
      <button class="btn btn-ghost" onclick="goto('login.php')">I already have an account</button>
      <button class="btn btn-ghost" onclick="goto('index.php')">Back</button>
    </div>
    <p style="margin-top:10px">By signing up you agree to our demo terms.</p>
  </div>

  <div class="heroSide hero">
    <h3>Perks you'll love</h3>
    <span class="badge">Beautiful product cards</span>
    <span class="badge">Dashboard for sellers</span>
    <span class="badge">Search & category filters</span>
    <span class="badge">Cart + Checkout</span>
  </div>
</div>

<script>
function goto(p){window.location.assign(p);}

function getUsers(){ return JSON.parse(localStorage.getItem('etsy_users')||'[]'); }
function setUsers(u){ localStorage.setItem('etsy_users', JSON.stringify(u)); }

function signup(){
  const name=document.getElementById('name').value.trim();
  const email=document.getElementById('email').value.trim().toLowerCase();
  const pass=document.getElementById('pass').value;
  const cpass=document.getElementById('cpass').value;

  if(!name||!email||!pass){ alert('Please fill all fields.'); return; }
  if(pass!==cpass){ alert('Passwords do not match.'); return; }

  let users=getUsers();
  if(users.some(u=>u.email===email)){ alert('Email already registered. Login instead.'); goto('login.php'); return; }

  const user={ id:crypto.randomUUID(), name, email, pass };
  users.push(user); setUsers(users);
  localStorage.setItem('etsy_current_user', JSON.stringify({id:user.id,name:user.name,email:user.email}));
  alert('Account created! Redirecting to home…');
  goto('home.php');
}
</script>
</body>
</html>
