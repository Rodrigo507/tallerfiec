function Estado(id) {
    var Ruta = Routing.generate('cambioestado')
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({id: id}),
        async: true,
        dataType: "json",
        success: function () {
            disableButton()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: 'Tarea terminada'
            })
        }
    });
}

function disableButton(){
    document.getElementById('terminado').hidden = true
}