document.getElementById("loginForm").addEventListener("submit", async function(e) {
  e.preventDefault();

  const usuario = document.getElementById("usuario").value;
  const clave = document.getElementById("clave").value;

  const res = await fetch("../controllers/LoginController.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ usuario, clave })
  });

  const data = await res.json();

  if (data.success) {
    if (data.rol_id === "2" || data.rol_id === 2) {
      window.location.href = "../views/home.php";
    } else if (data.rol_id === "1" || data.rol_id === 1) {
      window.location.href = "../views/index.php";
    } else {
      document.getElementById("errorMsg").textContent = "Rol no reconocido.";
    }
  } else {
    document.getElementById("errorMsg").textContent = data.message;
  }
});

