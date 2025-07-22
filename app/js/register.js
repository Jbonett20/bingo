document.getElementById('registerForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const data = Object.fromEntries(formData.entries());

  const res = await fetch('../controllers/RegisterController.php', {
    method: 'POST',
    body: JSON.stringify(data)
  });

  const result = await res.json();

  if (result.success) {
    alert('Usuario registrado correctamente.');
    window.location.href = 'login.php?registered=1';
  } else {
    alert('Error: ' + result.message);
  }
});
