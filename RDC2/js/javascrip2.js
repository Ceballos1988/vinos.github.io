const formContact = document.querySelector("#formContact");
formContact.addEventListener("submit", function (e) {
    e.preventDefault();
    
    const data = {
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        telefono: document.getElementById('telefono').value,
        mensaje: document.getElementById('mensaje').value   
    };

    $.post('correo.php', data, function (response) {
        if (response.success) {
            Swal.fire({
                title: 'Gracias!',
                html: `
                    ${response.message}
                    <h3 class="text-center" style="margin-top:15px;">Whatsapp: 1123050888</h3>
                `,
                icon: 'success',
                time: 3000
            }).then(function () {
                document.querySelector('#formContact').reset();
            });
        } else {
            Swal.fire({
                title: 'Fallo!',
                text: response.message,
                icon: 'warning',
                time: 3000
            });
        }
    });
});


