document
  .getElementById("loginForm")
  .addEventListener("submit", async function (e) {
    //Previene el comportamiento por defecto del componente, en este caso, loginForm
    e.preventDefault();

    /*
    "     Tengo texto   "
    "Tengo Texto
    */

    const username = document.getElementById("usuario").value.trim();
    const password = document.getElementById("contrasenna").value.trim();

    if (username.length == 0) {
      // alert("Debe ingresar un usuario")

      Swal.fire({
        icon: "error",
        title: "Datos faltantes",
        text: "Debe ingresar un usuario válido.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });

      return;
    }

    if (!password) {
      Swal.fire({
        icon: "error",
        title: "Datos faltantes",
        text: "Debe ingresar una contraseña válida.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });
      return;
    }

    //Hace login
    try {
      const respuesta = await fetch(
        "http://localhost:8080/universidad-fidelitas/Sem3/php/login/login.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            usuario: username,
            contrasenna: password,
          }),
        }
      );

      const data = await respuesta.json();
      console.log(data);

      if (respuesta.ok && data.status) {
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Inicio de sesión exitoso. Bienvenido: " + username,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
        });

        setTimeout(() => {
          window.location.href = "home.php";
        }, 3000);
      }
      return;
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Hubo un error inesperado",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });
      return;
    }
  });
