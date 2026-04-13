<?php
$horarios = [
    (object)[ // SanPedro - San Pedro de Colalao S.R.L.
        'id' => 1,
        'horario' => [
            (object)[
                'dia' => 'Lunes a sabados',
                'ida' => ['6:00', '7:00', '09:30', '11', '12:30', '14:10', '15:30', '17:10', '18:30', '20:15', '21:45'],
                'vuelta' => ['5:20', '8:30', '09:30', '12:00', '13:30', '14:45', '16:30', '18:00', '19:30', '21']
            ],
            (object)[
                'dia' => 'Domingos y Feriados',
                'ida' => ['7:00', '9:30', '11:00', '12:30', '15:30', '18:30', '20:15', '21:45'],
                'vuelta' => ['7:00', '9:30', '12:00', '14:00', '16:30', '18', '19:30', '21']
            ]
        ]
    ],
    (object)[
        'id' => 2, // Trancas - San Pedro de Colalao S.R.L.
        'horario' => [
            (object)[
                'dia' => 'Lunes a sabados',
                'ida' => ['6:00', '7:00', '09:30', '11', '12:30', '14:10', '15:30', '17:10', '18:30', '20:15', '21.45'],
                'vuelta' => ['5:20', '6:30', '7:15', '10:00', '12:30', '14:00', '15:15', '17:00', '18:30', '20:00', '21:30']
            ],
            (object)[
                'dia' => 'Domingos y Feriados',
                'ida' => ['7:00', '9:30', '11:00', '12:30', '15:30', '18:30', '20:15', '21:45'],
                'vuelta' => ['7:30', '10:00', '12:30', '14:30', '17', '18:30', '20:00', '21:30']
            ]            
        ]
    ],
    (object)[
        'id' => 3, // Simoca - Tradio
        'horario' => [
            (object)[
                'dia' => 'Lunes a viernes',
                'ida' => ['05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '12:40', '13:30', '14:00', '14:30', '15:30', '16:30', '17:30', '18:30', '20:00', '21:00', '22:00', '23:10', '', ''],
                'vuelta' => ['04:55', '5:45', '06:00', '06:30', '06:50', '07:10', '8:00', '09:00', '10:00', '11:30', '12:15', '12:30', '13:30', '14:30', '15:00', '16:00', '16:30', '17:30', '18:15', '18:40', '20:00', '20:40']
            ],
            (object)[
                'dia' => 'Sabados',
                'ida' => ['06:00', '07:00', '08:00', '09:00', '10', '11', '12:40', '12:40', '13:30', '14', '14:30', '15:30', '16:30', '17:30', '18:30', '20:00', '21:00', '22:00', ''],
                'vuelta' => ['06:00', '6:50', '07:20', '08:00', '08:30', '9:00', '10:00', '11:30', '12:30', '13:30', '14:30', '15:00', '16:00', '16:30', '17:15', '17:30', '18:30', '20:00', '21:00']
            ],
            (object)[
                'dia' => 'Domingo',
                'ida' => ['6:30', '8:00', '09:00', '10:00', '11', '12:10', '12:40', '14:00', '15:30', '16:30', '17:30', '18:10', '18:50', '20:00', '21:00', '22:00'],
                'vuelta' => ['6:45', '08:00', '09:00', '10:00', '11:00', '12:30', '14:30', '16:30', '17:20', '17:30', '18:30', '20:00', '20:30']
            ]            
        ]
    ],
    (object)[
        'id' => 4, // La Cocha - Exprebus
        'horario' => [
            (object)[
                'dia' => 'Lunes a viernes',
                'ida' => ['05:30', '08:30', '12:30', '14:30', '17:00', '18:30', '20:45', '', ''],
                'vuelta' => ['04:30', '05:45', '06:25', '09:25', '12:10', '15:50', '19:25', '21:10', '22:00']
            ],
            (object)[
                'dia' => 'Sábados y domingos',
                'ida' => ['05:30', '08:30', '12:30', '14:30', '17:00', '18:30', '20:45', ''],
                'vuelta' => ['05:45', '06:25', '9:25', '12:10', '15:50', '19:25', '21:10', '22:00']
            ]            
        ]
    ],
    (object)[
        'id' => 5, // Lules - El Provincial
        'horario' => 'Cada 20 minutos aproximadamente.'
    ],
    (object)[
        'id' => 6, // Famailla - Exprebus
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 7, // Monteros - Exprebus
        'horario' => 'Cada 2 Horas.'
    ],
    (object)[
        'id' => 8, // Concepcion - Exprebus
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 9, // Alberdi - Exprebus
        'horario' => 'Cada 3 Horas.'
    ],
    (object)[
        'id' => 10, // Bella Vista - Tradio
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 11, // Rio Colorado - Tradio
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 12, // Atahona - Tradio
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 13, // Monteagudo - Tradio
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 14, // Lamadrid - Tradio
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 15, // Taco Ralo - Tradio
        'horario' => 'Cada 40 minutos aproximadamente.'
    ],
    (object)[
        'id' => 16, // Samay Cochuna - Empresa Gutierrez
        'horario' => 'Jueves y Domingos 8hrs. Con reserva.'
    ],
    (object)[
        'id' => 17, // Nuestro Servicio - Concepcion
        'horario' => 'Ida desde Concepcíon: Viernes 16:30hrs. Sábados y Domingos. Vuelta desde Tafí del valle: Sábados y domingos 19:30 hrs.'
    ],
    (object)[
        'id' => 18, // Nuestro Servicio - Mollar
        'horario' => 'Ida desde Concepcíon: Viernes 16:30hrs. Sábados y Domingos. Vuelta desde Tafí del valle: Sábados y domingos 19:30 hrs.'
    ],
    (object)[
        'id' => 19, // Nuestro Servicio - Tafi del valle
        'horario' => 'Ida desde Concepcíon: Viernes 16:30hrs. Sábados y Domingos. Vuelta desde Tafí del valle: Sábados y domingos 19:30 hrs.'
    ],
    (object)[
        'id' => 20, // Mollar - Aconquija
        'horario' => [
            (object)[
                'dia' => 'Lunes a Sábados',
                'ida' => ['06:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                'vuelta' => ['05:15', '06:10', '10:10', '14:10', '17:10', '18:10', '21:45']
            ],
            (object)[
                'dia' => 'Domingos',
                'ida' => ['06:00', '10:00', '12:00', '14:00', '16:00', '19:00'],
                'vuelta' => ['10:10', '14:10', '17:10', '18:10', '19:50']
            ]            
        ]
    ],
    (object)[
        'id' => 21, // Tafi del valle - Aconquija
        'horario' => [
            (object)[
                'dia' => 'Lunes a Sábados',
                'ida' => ['06:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                'vuelta' => ['05:00', '06:00', '10:00', '13:50', '16:50', '17:50', '21:35']
            ],
            (object)[
                'dia' => 'Domingos',
                'ida' => ['06:00', '10:00', '12:00', '14:00', '16:00', '19:00'],
                'vuelta' => ['10:00', '13:50', '16:50', '17:50', '19:30', '20:50']
            ]                     
        ]
    ],
    (object)[
        'id' => 22, // Amaicha del valle - Aconquija
        'horario' => [
            (object)[
                'dia' => 'Lunes a Sábados',
                'ida' => ['06:00', '10:00', '14:00', '18:00', '20:00'],
                'vuelta' => ['03:15', '08:15', '12:20', '16:20', '20:00']
            ],
            (object)[
                'dia' => 'Domingos',
                'ida' => ['06:00', '10:00', '12:00', '14:00', '16:00', '19:00'],
                'vuelta' => ['08:15', '12:20', '16:20', '19:20']
            ]
        ]
    ],
    (object)[
        'id' => 23, //  Colalao del valle - Aconquija
        'horario' => [
            (object)[
                'dia' => 'Lunes a Sábados',
                'ida' => ['06:00', '14:00', ''],
                'vuelta' => ['06:00', '12:00', '14:00']
            ],
            (object)[
                'dia' => 'Domingos',
                'ida' => ['06:00', '14:00', '19:00'],
                'vuelta' => ['06:00', '12:00', '14:00']
            ]
        ]
    ],
    (object)[
        'id' => 24, //  Cafayate - Aconquija
        'horario' => [
            (object)[
                'dia' => 'Lunes a Sábados',
                'ida' => ['06:00', '14:00'],
                'vuelta' => ['06:00', '14:00']
            ],
            (object)[
                'dia' => 'Domingos',
                'ida' => ['06:00', '12:00', '14:00', '19:00'],
                'vuelta' => ['06:00', '14:00', '18:00']
            ]
        ]
    ],
    (object)[
        'id' => 25, // Yerba Buena
        'horario' => 'Cada 20 Minutos aproximadamente.'
    ],
    (object)[
        'id' => 26, // Tafi Viejo
        'horario' => 'Cada 20 Minutos aproximadamente.'
    ],
    (object)[
        'id' => 27, // Primera Confiteria
        'horario' => 'Cada 30 Minutos aproximadamente.'
    ],
    (object)[
        'id' => 28, // Horco Molle
        'horario' => 'Cada 40 Minutos aproximadamente.'
    ],
    (object)[
        'id' => 29, //  San Javier
        'horario' => [
            (object)[
                'dia' => 'Lunes a Viernes (Ida por terminal y vuelta por el Cristo)',
                'ida' => ['12:30', '16:00', '20:00'],
                'vuelta' => ['06:00', '14:00', '18:00']
            ],
            (object)[
                'dia' => 'Sábados (Ida por terminal y vuelta por el Cristo)',
                'ida' => ['12:00', '16:00', ''],
                'vuelta' => ['07:00', '14:00', '18:00']
            ],
            (object)[
                'dia' => 'Domingos y Feriados (Ida por terminal y vuelta por el Cristo)',
                'ida' => ['08:00', '12:00', '16:00'],
                'vuelta' => ['10:00', '14:00', '18:00']
            ]            
        ]
    ],
    (object)[
        'id' => 30, //  Raco y El Siambón
        'horario' => [
            (object)[
                'dia' => 'Lunes a Viernes (Regreso desde EL SIAMBÓN)',
                'ida' => ['06:00', '9:30', '11', '12:25', '14:00', '17:00', '19:30', '21:00'],
                'vuelta' => ['06:20', '07:20', '18:30']
            ],
            (object)[
                'dia' => 'Sábados (Regreso desde EL SIAMBÓN)',
                'ida' => ['07:00', '09:00', '11:00', '14:00', '17:00', '19:30', '21:00'],
                'vuelta' => ['08:50', '10:50', '16:50']
            ],
            (object)[
                'dia' => 'Domingos y Feriados (Regreso desde EL SIAMBÓN)',
                'ida' => ['07:00', '09:00', '11:00', '13:00', '15:00', '17:00', '19:30', '21:00'],
                'vuelta' => ['09:00', '14:00', '17:00']
            ],
            (object)[
                'dia' => 'Lunes a Viernes (Regreso DESDE NOGALITO)',
                'ida' => ['06:00', '9:30', '11', '12:25', '14:00', '17:00', '19:30', '21:00'],
                'vuelta' => ['05:45', '09:00', '12:00', '14:20', '19:00']
            ],
            (object)[
                'dia' => 'Sábados (Regreso DESDE NOGALITO)',
                'ida' => ['07:00', '09:00', '11:00', '14:00', '17:00', '19:30', '21:00'],
                'vuelta' => ['06:30', '14:00', '19:00']
            ],
            (object)[
                'dia' => 'Domingos y Feriados (Regreso DESDE NOGALITO)',
                'ida' => ['07:00', '09:00', '11:00', '13:00', '15:00', '17:00', '19:30', '21:00'],
                'vuelta' => ['14:00', '19:00']
            ]                      
        ]
    ],
    (object)[
        'id' => 30, //  San Javier y Rio Loro
        'horario' => [
            (object)[
                'dia' => 'Lunes a Viernes',
                'ida' => ['09:00', '11:30', '14:30', '17:00', '19:00'],
                'vuelta' => ['06:00', '07:30', '10:30', '12:15', '14:00', '16:00', '18:20', '20:20']
            ],
            (object)[
                'dia' => 'Sábados',
                'ida' => ['07:00', '09:00', '11:30', '14:30', '17:00', '19:00'],
                'vuelta' => ['08:00', '10:15', '12:30', '16:00', '18:00', '20:00']
            ],
            (object)[
                'dia' => 'Domingos y Feriados',
                'ida' => ['09:00', '11:30', '14:30', '17:00', '19:00'],
                'vuelta' => ['10:15', '12:30', '16:00', '18:00', '20:00']
            ]            
        ]
    ]

];
