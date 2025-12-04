document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("update-task")
    .addEventListener("click", async function (e) {
      e.preventDefault();
      // Obtener valores
      const nombre = document.getElementById("tareaNombre");
      const descripcion = document.getElementById("descripcion");
      const estado = document.getElementById("estado");
      const urlImagen = document.getElementById("urlImagen");
      const usuarioId = document.getElementById("usuarioId");
      const tareaId = document.getElementById("tareaId");

      // Límites
      const maxNombre = 50;
      const maxDescripcion = 255;
      const maxUrl = 200;
      try {
        // Validaciones
        if (
          !nombre.value ||
          !descripcion.value ||
          !estado.value ||
          !urlImagen.value
        ) {
          Swal.fire({
            icon: "warning",
            title: "Datos incompletos",
            text: "Debe completar todos los campos obligatorios.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        if (nombre.value.length > maxNombre) {
          Swal.fire({
            icon: "error",
            title: "Nombre demasiado largo",
            text: `El nombre no puede superar los ${maxNombre} caracteres.`,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        if (descripcion.value.length > maxDescripcion) {
          Swal.fire({
            icon: "error",
            title: "Descripción muy larga",
            text: `La descripción no puede superar los ${maxDescripcion} caracteres.`,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        if (urlImagen && urlImagen.value.length > maxUrl) {
          Swal.fire({
            icon: "error",
            title: "URL muy larga",
            text: `La URL no puede superar los ${maxUrl} caracteres.`,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        if (urlImagen.value !== "" && !urlImagen.value.startsWith("http")) {
          Swal.fire({
            icon: "error",
            title: "URL inválida",
            text: "La URL debe iniciar con http o https.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        if(isNaN(tareaId.value) || !tareaId.value){
            Swal.fire({
            icon: "error",
            title: "Tarea Invalida",
            text: "El ID de la tarea es un ID Invalido",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        // Associate the FormData object with the form element
        const formData = new FormData();

        formData.append("tareaNombre", nombre.value.trim());
        formData.append("descripcion", descripcion.value.trim());
        formData.append("estado", estado.value);
        formData.append("urlImagen", urlImagen.value.trim());
        formData.append("userId", usuarioId.value);
        formData.append("tareaId", tareaId.value);

        const response = await fetch(
          "/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/api/editar-tarea.php",
          {
            method: "POST",
            body: formData,
          }
        );
        const resp = await response.json();

        if (resp) {
          if (resp.success && resp.message) {
            Swal.fire({
              icon: "success",
              title: "Tarea Editada exitosamente",
              text: resp.message,
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 5000,
              timerProgressBar: true,
            });
            setTimeout(() => {
              window.location.href =
                "/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/";
            }, 1500);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error al Editar la Tarea",
              text: resp.message,
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 4000,
              timerProgressBar: true,
            });
            return;
          }
        } else {
          Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "La Tarea no pudo ser editada.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }
      } catch (e) {
        console.error(e);
      }
    });
});
