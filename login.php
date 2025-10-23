<?php /* login.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — HandCraft</title>
<style>
body{margin:0;background:#0f1224;color:#eaf0ff;font-family:Inter,system-ui}
.box{max-width:520px;margin:60px auto;padding:22px;background:#14183a;border:1px solid #2a2f63;border-radius:20px;box-shadow:0 16px 40px rgba(0,0,0,.45)}
h1{margin:0 0 6px} p{color:#9aa0c3}
label{font-size:12px;color:#b8bff2}
input{width:100%;padding:12px;margin:6px 0 14px;border-radius:12px;border:1px solid #2b3166;background:#0f1433;color:#fff}
.btn{border:none;border-radius:12px;padding:12px 16px;font-weight:700;cursor:pointer}
.btn-primary{background:linear-gradient(135deg,#7c5cff,#5a3df6);color:#fff;width:100%}
.links{display:flex;justify-content:space-between;margin-top:10px}
a{color:#9aa0c3}
</style>
</head>
<body>
<div class="box">
  <h1>Welcome back</h1>
  <p>Login to continue shopping or managing your shop.</p>
  <label>Email</label>
  <input id="email" type="email" placeholder="you@example.com">
  <label>Password</label>
  <input id="pass" type="password" placeholder="••••••••">
  <button class="btn btn-primary" onclick="login()">Login</button>
  <div class="links">
    <a href="#" onclick="goto('signup.php')">Create account</a>
    <a href="#" onclick="goto('index.php')">Back</a>
  </div>
</div>

<script>
function goto(p){window.location.assign(p);}
function getUsers(){ return JSON.parse(localStorage.getItem('etsy_users')||'[]'); }

function login(){
  const email=document.getElementById('email').value.trim().toLowerCase();
  const pass=document.getElementById('pass').value;
  const u=getUsers().find(x=>x.email===email && x.pass===pass);
  if(!u){ alert('Invalid credentials.'); return; }
  localStorage.setItem('etsy_current_user', JSON.stringify({id:u.id,name:u.name,email:u.email}));
  alert('Logged in!'); goto('home.php');
}
</script>
</body>
</html>
