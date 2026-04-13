// Asegúrate de que el DOM esté completamente cargado
$(document).ready(function() {

    // Selecciona el botón por su ID y agrega el event listener para el evento 'click'
    $('#botonModal').click(function() {
        // Aquí va la función que quieres ejecutar cuando se haga clic en el botón
        //console.log('El botón del texto imagen');
        // Puedes agregar aquí más código para la función
        $('#car-botonModal, #bot-botonModal').addClass('active');
    });
    // Selecciona el botón por su ID y agrega el event listener para el evento 'click'
    $('#botonModal1').click(function() {
        // Aquí va la función que quieres ejecutar cuando se haga clic en el botón
        //console.log('El botón de la galeria 1');
        // Puedes agregar aquí más código para la función
        $('#car-botonModal1, #bot-botonModal1').addClass('active');

    });    // Selecciona el botón por su ID y agrega el event listener para el evento 'click'
    $('#botonModal2').click(function() {
        // Aquí va la función que quieres ejecutar cuando se haga clic en el botón
        //console.log('El botón de la galeria 2');
        // Puedes agregar aquí más código para la función
        $('#car-botonModal2, #bot-botonModal2').addClass('active');
    });    // Selecciona el botón por su ID y agrega el event listener para el evento 'click'
    $('#botonModal3').click(function() {
        // Aquí va la función que quieres ejecutar cuando se haga clic en el botón
        //console.log('El botón de la galeria 3');
        // Puedes agregar aquí más código para la función
        $('#car-botonModal3, #bot-botonModal3').addClass('active');
    });
    //Cerrar todo
    $('#close-modal').click(function() {
        // Aquí va la función que quieres ejecutar cuando se haga clic en el botón
        //console.log('El botón de cerrar');
        // Puedes agregar aquí más código para la función
        $('#car-botonModal, #bot-botonModal').removeClass('active');
        $('#car-botonModal1, #bot-botonModal1').removeClass('active');
        $('#car-botonModal2, #bot-botonModal2').removeClass('active');
        $('#car-botonModal3, #bot-botonModal3').removeClass('active');
    });

});