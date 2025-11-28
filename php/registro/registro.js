document.getElementById("frmRegistro").addEventListener("submit", async (e) => {
  e.preventDefault();

  //obtener los valores de los input

  const nombre = document.getElementById("nombre").value.trim();
  const correo = document.getElementById("correo").value.trim();
  const usuario = document.getElementById("usuario").value.trim();
  const clave = document.getElementById("clave").value.trim();
  const confirmar = document.getElementById("confirmar").value.trim();
  const fecha = document.getElementById("fecha").value.trim();
  const genero = document
    .querySelector('input[name="genero"]:checked')
    ?.value.trim();

  console.log("nombre", nombre);
  console.log("fecha", fecha);
  console.log("genero", genero);
  console.log("confirmar", confirmar);
  console.log("nombre", nombre);

  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: "#fff",
    color: "#000",
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });
  if (
    !nombre ||
    !correo ||
    !usuario ||
    !clave ||
    !confirmar ||
    !fecha ||
    !genero
  ) {
    Toast.fire({
      icon: "Warning",
      title: "Debe completar todos los campos",
    });
    return;
  }
  if (clave !== confirmar) {
    Toast.fire({
      icon: "error",
      title: "Las contraseñas no coinciden",
    });
    return;
  }

  const datos = new FormData();

  datos.append("nombre", nombre);
  datos.append("correo", correo);
  datos.append("usuario", usuario);
  datos.append("clave", clave);
  datos.append("confirmar", confirmar);
  datos.append("fecha", fecha);
  datos.append("genero", genero);

  try {
    const response = fetch(
      "/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/php/registro/registro.php",
      {
        method: "POST",
        body: datos,
      }
    );
    const result = (await response).text();

    if ((await result).includes("ok")) {
      Toast.fire({
        icon: "success",
        title: "Respuesta Obtenida por el server: " + (await result).toString(),
      });
      setTimeout(() => {
        window.location.href = "index.php";
      }, 5000);
    } else if ((await result).includes("error:")) {
      Toast.fire({
        icon: "error",
        title: (await result).replace("error:", "").trim(),
      });
    }
    else{
      Toast.fire({
        icon: "error",
        title: "Ocurrio un error inesperado al registrar el usuario.",
      });
    }
  } catch (error) {
    console.error(error);
    Toast.fire({
      icon: "error",
      title: "Error de conexion con el servidor" + error,
    });
  }
});
