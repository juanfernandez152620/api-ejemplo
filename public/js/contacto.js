function enviarFormularioContacto() {
    $('.form-mail #mail-alert').text(``);
    // Validación simple del formulario (puedes mejorar esto)
    const nombre = $('.form-mail #nombre').val();
    const email = $('.form-mail #email').val();
    const telefono = $('.form-mail #telefono').val();
    const mensaje = $('.form-mail #mensaje').val();
    const validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

    let msgError = '';

    if (!nombre || !mensaje || !telefono || !email) {
        msgError = 'Por favor, complete todos los campos.';
        $('.form-mail #mail-alert').text(`${msgError}`);
        return;
    }
    if (!validEmail.test(email)) {
        msgError = 'Ingrese un mail valido';
        $('.form-mail #mail-alert').text(`${msgError}`);
        return;
    }
    $('.form-mail #enviar').attr("disabled", true);
    $('.form-mail #enviar').addClass("disabled");
    $('.form-mail #mail-alert').html(`
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
    `);

    // Envía el formulario utilizando AJAX
    $.ajax({
        type: 'POST',
        url: URLHome + 'views/contacto/enviar_correo.php', // Reemplaza 'URLHome' con la ruta correcta de tu servidor
        data: {
            nombre,
            email,
            telefono,
            mensaje,
        },
        success: function (responseJSON) {
            console.log(responseJSON);
            const response = JSON.parse(responseJSON);
            if (response.status === "success") {
                // Abre el modal
                $('.form-mail #mail-alert').text(`Su mensaje fue enviado con exito.`);
            } else {
                $('.form-mail #mail-alert').text(`Hubo un error al crear el cupon, intentelo nuevamente en unos minutos.`);
            }
            // Puedes agregar más lógica aquí según la respuesta del servidor
        },
        error: function (error) {
            console.log('Error en la solicitud AJAX', error);
            $('.form-mail #enviar').attr("disabled", false);
            $('.form-mail #enviar').removeClass("disabled");
            $('.form-mail #mail-alert').text(`Hubo un error al crear el cupon, intentelo nuevamente en unos minutos.`);
        }
    });
}