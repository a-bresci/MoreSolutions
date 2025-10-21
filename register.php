<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro - More Solutions</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <h2>Crear usuario</h2>
    <form id="regForm">
      <input type="text" id="nombre" placeholder="Nombre" required><br>
      <input type="email" id="email" placeholder="Email" required><br>
      <input type="text" id="username" placeholder="Usuario" required><br>
      <input type="password" id="password" placeholder="Contraseña" required><br>
      <button type="submit">Registrar</button>
    </form>
    <p id="msg" style="color:green"></p>
  </div>

<script>
document.getElementById('regForm').addEventListener('submit', function(e){
  e.preventDefault();
  const fd = new FormData();
  fd.append('nombre', document.getElementById('nombre').value);
  fd.append('email', document.getElementById('email').value);
  fd.append('username', document.getElementById('username').value);
  fd.append('password', document.getElementById('password').value);

  fetch('php/register_user.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(j => {
      if (j.success) {
        document.getElementById('msg').textContent = 'Usuario creado, ya podés ingresar.';
        setTimeout(()=> window.location.href = 'login.php', 1200);
      } else {
        document.getElementById('msg').style.color = 'crimson';
        document.getElementById('msg').textContent = j.message || 'Error';
      }
    })
    .catch(()=> document.getElementById('msg').textContent = 'Error de conexión');
});
</script>
</body>
</html>
