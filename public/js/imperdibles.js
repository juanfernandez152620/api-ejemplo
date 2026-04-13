/* BLACK BOX */

const slider_id = 'con_Imp';
const container_id = 'view_Imp';
const slide_class = '.slide_Imp';
let slide_width_vw = 25;
const velocidad_Tran_ms = 200;
const boton_id_der = 'nextBtn_Imp';
const boton_id_izq = 'prevBtn_Imp';
const transition = 'ease-out'; //cubic-bezier(0.50, 0.00, 0.50, 1) | cubic-bezier(0,0,.2,1)

//LOS RESETS ESTAN HARD CODEADOS

/* ---------------- */

//verificacion de mobile o no
if (window.innerWidth >= 600) {
  mobileOn = false;
} else {
  mobileOn = true;
}

//width de mobile o no
if (mobileOn) {
  slide_width_vw = slide_width_vw*2;
}

//Elementos activos de mobile o no
if (mobileOn) {
  activos = 3;
} else {    
  activos = 5;
}



// Aplicar las reglas CSS a todos los elementos con la clase .slide
$(slide_class).css({
    'width': `${slide_width_vw}vw`,
    'min-width': `${slide_width_vw}vw`
});

// Aplicar la regla de CSS a todos los elementos con la clase .view
if (mobileOn) {
    $(`#${slider_id}`).css({
        'max-width': `${slide_width_vw*2}vw`
    });
} else {
    $(`#${slider_id}`).css({
        'max-width': `${slide_width_vw*4}vw`
    });
}

//Obtenemos las variables de los slides y el container
const container = document.getElementById(container_id);
const slides = document.querySelectorAll(slide_class);

//declaracion de variables utiles
let currentSlide = 0;

// Variable para controlar el estado del temporizador
let canToggle = true;

//shift
let shift = 1;


//overflowshift
let overflow_shift = activos;


//overflowshiftMobile
let overflow_shift_mobile = 2;

//El numero maximo de slides hasta que se resetea
let  max = slides.length + activos;

// movemos el contenedor a la mitad de un slide para que se vean los 5 slides //$(`#${container_id}`).css(`transform`, `translateX(-${slide_width_vw/2}vw)`);
shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);
//$(`#${container_id}`).css(`transform`, `translateX(calc(-${slide_width_vw/2}vw - 4rem))`);

// Esta funcion nos permite activar o desactivar elementos
function actDes(element) {
    // Comprobamos si el elemento está visible
    if ($(element).is(':visible')) {
        // Si está visible, lo ocultamos
        $(element).hide();
    } else {
        // Si está oculto, lo mostramos
        $(element).show();
    }
}

// Activamos los primeros 5 elementos
for (let i = activos; i < slides.length; i++) { // Hay que cambiar el numero activos porque depende de si es mobile o no
    actDes(slides[i]);
}
currentSlide = activos; 

// Esta funcion nos permite ingresar un numero de cuantas veces queremos recorrer la funcion y nos devuelve un numero que respete el orden de elementos de la funcion y no hacer un overflow
function indiceValido(array, indiceInicial) {
    // Si la longitud de la array es 0, devolvemos -1 para indicar que no hay elementos
    if (array.length === 0) {
      return -1;
    }
    
    // Calculamos el índice válido
    const indice = indiceInicial % array.length;
    
    // Si el índice es negativo, ajustamos para que sea un índice positivo
    return indice >= 0 ? indice : indice + array.length;
}

// Esta funcion nos permite mover el contenedor
function shift_vw(selector, value) {
    // Aplica el estilo transform: translateX(value) al elemento seleccionado por selector
    $(selector).css('transform', `translateX(${value}vw)`);
}

function shift_con_vw(selector, value) {
    if (mobileOn) {
        // Aplica el estilo transform: translateX(value) al elemento seleccionado por selector en mobile
        $(selector).css('transform', `translateX(calc(${value}vw - 1rem))`);
        
    } else {
        // Aplica el estilo transform: translateX(value) al elemento seleccionado por selector
        $(selector).css('transform', `translateX(calc(${value}vw - 6.5rem))`);
    }
}

function shift_vw_rem(selector, value, rem) {
    // Aplica el estilo transform: translateX(value) al elemento seleccionado por selector
    $(selector).css('transform', `translateX(calc(${value}vw + ${rem}rem))`);
}


// ------------------------------------------------------------ //

function nextSlide() {
if (canToggle) {    
    canToggle = false;
    // --------- contenido

    if (currentSlide < slides.length) {
        console.log('modo normal');
        console.log(slides.length);

        // Activar el siguiente
        actDes(slides[indiceValido(slides, currentSlide)]);
        currentSlide++;

        // añadirle la transicion al contenedor
        $(`#${container_id}`).css("transition", `transform ${velocidad_Tran_ms}ms ${transition}`);

        // transform: translateX(-37.5%); | $(`#${container_id}`).css(`transform`, `translateX(-${slide_width_vw + slide_width_vw/2}vw)`);
        shift_con_vw(`#${container_id}`, (slide_width_vw + slide_width_vw/2)*-1);

        setTimeout(function() {
          // desactivar la transicion al contenedor
          $(`#${container_id}`).css("transition", `none`);
          // transform: translateX(-12.5%); | $(`#${container_id}`).css(`transform`, `translateX(-${slide_width_vw/2}vw)`);
          shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);
          // desactivar el anterior
          actDes(slides[currentSlide - (activos + 1)]);
          
        }, velocidad_Tran_ms);

        console.log('currentSlide ', currentSlide);

    } else if (currentSlide < max) { 
        console.log('modo indefinido');
        console.log(shift);

        // Activar el siguiente
        actDes(slides[indiceValido(slides, currentSlide)]);

        //Poner el overflow al ultimo
        slides[indiceValido(slides, currentSlide)].classList.add('overflow');

        //Seleccionar a todos los activos menos aquellos con overflow
        let actives = $(`${slide_class}:visible:not(.overflow)`);

        console.log(actives);

        //Shiftear todos a la derecha
        for (let i0 = 0; i0 < actives.length; i0++) {
            shift_vw_rem(actives[i0], (-1*shift*slide_width_vw), (-1*shift));
            //$(actives[i0]).css('transform', `translateX(calc(-25vw + -1rem)`);
            //shift_vw_rem(actives[i0], shift*(-1*slide_width_vw, -1*shift));
           //$(actives[i0]).css(`transform`, `translateX(${shift*(-1*slide_width_vw)}vw)`);
        }

        //sacar el overflow
        const overElem = $('.overflow');

        console.log(overElem);

        //Shiftear todos a la derecha
        for (let i = 0; i < overElem.length; i++) {
            if (mobileOn) {
                shift_vw_rem(overElem[i], (overflow_shift*slide_width_vw), (overflow_shift));
                //shift_vw(overElem[i], slide_width_vw*(overflow_shift_mobile+1));
                //$(overElem[i]).css(`transform`, `translateX(${overflow_shift*slide_width_vw}vw)`);
            } else {
                shift_vw_rem(overElem[i], (overflow_shift*slide_width_vw), (overflow_shift));
            }
            
        }

        // añadirle la transicion al contenedor
        $(`#${container_id}`).css("transition", `transform ${velocidad_Tran_ms}ms ${transition}`);

        // transform: translateX(-37.5%);
        shift_con_vw(`#${container_id}`, (slide_width_vw + slide_width_vw/2)*-1);

        
        setTimeout(function() {
            // desactivar la transicion al contenedor
            $(`#${container_id}`).css("transition", `none`);
            // desactivar el anterior
            actDes(actives[0]);
            // transform: translateX(-12.5%); 
            shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);

            //sacar el overflow
            overflow_shift--;
            for (let i = 0; i < overElem.length; i++) {
                if (mobileOn) {
                    shift_vw_rem(overElem[i], (overflow_shift*slide_width_vw), (overflow_shift));
                    //shift_vw(overElem[i], slide_width_vw*overflow_shift_mobile);
                    //$().css(`transform`, `translateX(${(overflow_shift-1)*slide_width_vw}vw)`);
                } else {
                    shift_vw_rem(overElem[i], (overflow_shift*slide_width_vw), (overflow_shift));
                }
                
            }


            if (mobileOn) {
                overflow_shift_mobile--;
            }

            currentSlide++;
            shift++;
                            
            console.log('currentSlide ', currentSlide);
            console.log('Shift ', shift);
            console.log('overflow_shift ', overflow_shift);

        }, velocidad_Tran_ms);
    } else {
        console.log('modo reset');
        currentSlide = 0;
        shift = 1;
        overflow_shift = activos;
        overflow_shift_mobile = 2;

        //sacamos el overflow
        $(slide_class).removeClass("overflow");

        //sacamos el shift
        $(slide_class).css(`transform`, `translateX(0vw)`);

        currentSlide = activos; 

        canToggle = true;
        nextSlide()
    }

    // --------- end_contenido

    

    // Reactivar el toggle después de 1 segundo
    setTimeout(() => {
        canToggle = true;
    }, velocidad_Tran_ms);
  }
}

// El shifteo brusco se da por la transicion de 3/4 a 1/2 hay que añadir otra trnasicion leve.

// ---------------------------------------------------------------------- //

function previousSlide() {
    if (canToggle) {    
        canToggle = false;
        // --------- contenido
        if (currentSlide == activos) {
            console.log('modo reset');
            if (mobileOn) {
                
                //movemos
                shift_con_vw(`#${container_id}`, (slide_width_vw + slide_width_vw/2)*-1);
                
                // Activar al anterior
                actDes(slides[5]); 

                //console.log('currentS-act ', slides.length - actives.length - 1);
                //let actives = $(`${slide_class}:visible:not(.overflow)`);                

                for (let i0 = 0; i0 < slides.length; i0++) {
                    if (i0 == 0) { ///9??
                        shift_vw_rem(slides[i0], 50, 1);
                        slides[i0].classList.add('overflow');
                    } else if (i0 == 1) {
                        shift_vw_rem(slides[i0], 50, 1);
                        slides[i0].classList.add('overflow');
                    } else if (i0 == 2) {
                        shift_vw_rem(slides[i0], 50, 1); // 0 0
                    } else if (i0 == 3) {
                        shift_vw_rem(slides[i0], 50, 1);
                    } else if (i0 == 4) {
                        shift_vw_rem(slides[i0], -100, -2);
                    } else if (i0 == 5) {
                        shift_vw_rem(slides[i0], -150, -3); //2 100
                    }
                    
                    //$(actives[i0]).css(`transform`, `translateX(${(shift-1)*(-1*slide_width_vw)}vw)`);
                }

                //Sin este timer no funciona la animacion por alguna razon
                setTimeout(function() {
                    // Añadir la transición primero
                    $(`#${container_id}`).css("transition", `transform ${velocidad_Tran_ms}ms ${transition}`);
                    
                    //contenedor transform: translateX(10vw);
                    shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);
                }, 10);

                //timer
                setTimeout(function() {
                    //sacar la animacion
                    $(`#${container_id}`).css("transition", `none`);

                    //console.log(overElem[overElem.length - 1]); //Hay un problema cuando no allá overflow y entonces el resueltado sea -1 cuando no allá eso puede dar el error

                    //desactivar el ultimo overflow
                    actDes(slides[2]);

                    shift_vw_rem(slides[2], 0, 0);
                    shift_vw_rem(slides[5], -100, -2);
                
                    //le reseteamsos todo pero le sumamos uno
                    currentSlide = 8; 
                    shift = 3; 
                    overflow_shift = 1; 


                }, velocidad_Tran_ms);
                
            } else { 
                
                //movemos
                shift_con_vw(`#${container_id}`, (slide_width_vw + slide_width_vw/2)*-1);

                // Activar al anterior
                actDes(slides[5]); 
                
                //console.log('currentS-act ', slides.length - actives.length - 1);
                let actives = $(`${slide_class}:visible:not(.overflow)`);


                //-1 vw a los que no son overflow NO ESTA FUNCIONANDO TIENE PROBLEMA AL SOLO VOLVER translateX(calc(1rem + 25vw))
                for (let i0 = 0; i0 < actives.length; i0++) {
                    if (i0 == 5) { ///9??
                        shift_vw_rem(actives[i0], -125, -5);
                    } else if (i0 == 4) {
                        shift_vw_rem(actives[i0], 25, 1);
                    } else {
                        shift_vw_rem(actives[i0], 25, 1);
                        actives[i0].classList.add('overflow');
                    }
                    
                    //$(actives[i0]).css(`transform`, `translateX(${(shift-1)*(-1*slide_width_vw)}vw)`);
                }

                //El penultimo no tiene overflow y no tiene display | tiene -4 -100
                
                
                //Sin este timer no funciona la animacion por alguna razon
                setTimeout(function() {
                    // Añadir la transición primero
                    $(`#${container_id}`).css("transition", `transform ${velocidad_Tran_ms}ms ${transition}`);
                    
                    //contenedor transform: translateX(10vw);
                    shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);
                }, 10);

                //timer
                setTimeout(function() {
                    //sacar la animacion
                    $(`#${container_id}`).css("transition", `none`);

                    //console.log(overElem[overElem.length - 1]); //Hay un problema cuando no allá overflow y entonces el resueltado sea -1 cuando no allá eso puede dar el error

                    //desactivar el ultimo overflow
                    actDes(slides[4]);

                    shift_vw_rem(actives[4], -100, -4);
                    shift_vw_rem(actives[5], -100, -4);
                
                    //le reseteamsos todo pero le sumamos uno
                    currentSlide = 10; //Bien
                    shift = 5; //Bien
                    overflow_shift = 1; //Bien


                }, velocidad_Tran_ms);
            }

            // ------------------------------------------------------------ //

        } else if (currentSlide < slides.length+1) {
            console.log('modo normal');

            // Activar al anterior
            actDes(slides[currentSlide - (activos + 1)]);
            
            // Restamos uno a currentSlide
            currentSlide--;

            // Correr el contenedor 3/2 a la izquierda (nueva posición objetivo)
            shift_con_vw(`#${container_id}`, (slide_width_vw + slide_width_vw/2)*-1);

            //Sin este timer no funciona la animacion por alguna razon
            setTimeout(function() {
                // Añadir la transición primero
                $(`#${container_id}`).css("transition", `transform ${velocidad_Tran_ms}ms ${transition}`);
                
                // Correr el contenedor 1/2 a la izquierda (restaurar la posición original)
                shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);
            }, 10);
            
            // Temporizador para esperar a que la transición termine
            setTimeout(function() {

                // Sacamos la animación
                $(`#${container_id}`).css("transition", `none`);
            
                // Desactivar el anterior
                actDes(slides[indiceValido(slides, currentSlide)]);
            
            }, velocidad_Tran_ms);
                            
            console.log('currentSlide ', currentSlide);
            console.log('Shift ', shift);
            console.log('overflow_shift ', overflow_shift);

        } else { // -5 pc | -3 mob y es el vuelta completa

            console.log('modo indefinido');
            console.log('currentSlide ', currentSlide);
            console.log(slides.length);

            //$(slides[11]).css(`transform`, `translateX(100vw)`);


            //Seleccionar a todos los activos menos aquellos con overflow
            let actives = $(`${slide_class}:visible:not(.overflow)`);

            console.log(actives);
            console.log(slides);
            //slides[slides.length - actives.length - 1]

            //(el anterior) (vh*shift)
            //shift_vw(slides[slides.length - actives.length - 1], (slide_width_vw * (shift-1)) * -1);

            //Corregiendo un bug
            if (mobileOn == true && currentSlide == 7) {
                shift_vw_rem(slides[3], -50, -1); //El problema es que no agarra la transformacion por que no estan en actives pero porque todavida no se activo
            }
            if (mobileOn == false && currentSlide == 7) {
                shift_vw_rem(slides[1], -25, -1); //El problema es que no agarra la transformacion por que no estan en actives pero porque todavida no se activo
            }
            if (mobileOn == false && currentSlide == 8) {
                shift_vw_rem(slides[2], -50, -2); //El problema es que no agarra la transformacion por que no estan en actives pero porque todavida no se activo
            }
            if (mobileOn == false && currentSlide == 9) {
                shift_vw_rem(slides[3], -75, -3); //El problema es que no agarra la transformacion por que no estan en actives pero porque todavida no se activo
            }
            if (mobileOn == false && currentSlide == 10) {
                shift_vw_rem(slides[4], -100, -4); //El problema es que no agarra la transformacion por que no estan en actives pero porque todavida no se activo
            }


            // Activar al anterior
            actDes(slides[slides.length - actives.length - 1]); //Funciona a bases dudosas //Bug1 //
            
            //console.log('currentS-act ', slides.length - actives.length - 1);


            //-1 vw a los que no son overflow NO ESTA FUNCIONANDO TIENE PROBLEMA AL SOLO VOLVER
            for (let i0 = 0; i0 < actives.length; i0++) {
                shift_vw_rem(actives[i0], (shift-1)*(-1*slide_width_vw), (shift-1)*-1);
                // if (currentSlide == 9) { ///9??
                //     shift_vw_rem(actives[i0], -50, -2);
                // } else {
                //     shift_vw_rem(actives[i0], (shift-1)*(-1*slide_width_vw), (shift-1)*-1);
                // }
                
                //$(actives[i0]).css(`transform`, `translateX(${(shift-1)*(-1*slide_width_vw)}vw)`);
            }

            //sacar el overflow
            const overElem = $('.overflow');
            
            //Shiftear todos a la derecha
            for (let i = 0; i < overElem.length; i++) {
                if (mobileOn) {
                    shift_vw_rem(overElem[i], (overflow_shift+1)*slide_width_vw, overflow_shift+1);
                    //shift_vw(overElem[i], slide_width_vw*(overflow_shift_mobile+1));
                    //$(overElem[i]).css(`transform`, `translateX(${overflow_shift*slide_width_vw}vw)`);
                } else {
                    shift_vw_rem(overElem[i], (overflow_shift+1)*slide_width_vw, overflow_shift+1);
                    //$(overElem[i]).css(`transform`, `translateX(${(overflow_shift+1)*slide_width_vw}vw)`);
                }
                
            }


            // Correr el contenedor 3/2 a la izquierda (nueva posición objetivo)
            shift_con_vw(`#${container_id}`, (slide_width_vw + slide_width_vw/2)*-1);
            
            
            //Sin este timer no funciona la animacion por alguna razon
            setTimeout(function() {
                // Añadir la transición primero
                $(`#${container_id}`).css("transition", `transform ${velocidad_Tran_ms}ms ${transition}`);
                
                //contenedor transform: translateX(10vw);
                shift_con_vw(`#${container_id}`, (slide_width_vw/2)*-1);
            }, 10);

            //timer
            setTimeout(function() {
                //sacar la animacion
                $(`#${container_id}`).css("transition", `none`);

                //console.log(overElem[overElem.length - 1]); //Hay un problema cuando no allá overflow y entonces el resueltado sea -1 cuando no allá eso puede dar el error

                //desactivar el ultimo overflow
                actDes(overElem[overElem.length - 1]);

                //sacarle la clase overflow
                $(overElem[overElem.length - 1]).removeClass('overflow');

                //resetear su transform a 0
                $(overElem[overElem.length - 1]).css(`transform`, `translateX(0vw)`);

                //volvemos a sacar activos
                actives = $(`${slide_class}:visible:not(.overflow)`);

                //-1 vw a activos
                for (let i0 = 0; i0 < actives.length; i0++) {
                    
                    shift_vw_rem(actives[i0], (shift-2)*(-1*slide_width_vw), (shift-2)*-1);
                    //$(actives[i0]).css(`transform`, `translateX(${(shift-2)*(-1*slide_width_vw)}vw)`);
                }

                //restar a todo lo que se tenga que hacer

                currentSlide--;
                shift--;
                overflow_shift++;

                
                            
                console.log('currentSlide ', currentSlide);
                console.log('Shift ', shift);
                console.log('overflow_shift ', overflow_shift);


            }, velocidad_Tran_ms);

        }

        // --------- end_contenido

        // Reactivar el toggle después de 1 segundo
        setTimeout(() => {
            canToggle = true;
        }, velocidad_Tran_ms);
    }
}

// ---------------------------------------------------------------------- //

// En el modo mobile el reset no funciona.

// ----

//El render del mismo (Por mas que sean 4 o 5 que se rendericen 6 siempre)





document.getElementById(boton_id_der).addEventListener('click', nextSlide);
document.getElementById(boton_id_izq).addEventListener('click', previousSlide);