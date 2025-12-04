document.addEventListener("DOMContentLoaded", ()=> {
    const btnsElimnar = Array.from(document.querySelectorAll(".btn-eliminar-tarea"))

    if(!btnsElimnar.length){
        return
    }

    btnsElimnar.forEach((btnElimnar) => {
        if (!btnElimnar) return;
        btnElimnar.addEventListener("click", async(event)=> {
            event.preventDefault();
            const id = btnElimnar.getAttribute("data-task-id")
            try {
                if(id){
                    // Associate the FormData object with the form element
                    const formData = new FormData();
                    formData.append("task", id);

                    const response = await fetch(
                    "/universidad-fidelitas/LeonardoAranibar_P4_G7_JN/api/eliminar-tarea.php",
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
                            title: "Tarea Elimiada exitosamente",
                            text: resp.message,
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            });
                            setTimeout(() => {
                            window.location.reload();
                            }, 2800);
                        } else {
                            Swal.fire({
                            icon: "error",
                            title: "Error al eliminar la Tarea",
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
                        text: "La Tarea no pudo eliminar.",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                    return;
                    }
                }
            } catch (error) {
                
            }
        })
    });
})