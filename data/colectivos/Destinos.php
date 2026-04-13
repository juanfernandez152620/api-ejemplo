<?php
$circuitos = [
    (object)[
        'id' => 1,
        'titulo' => 'Circuito Choromoro',
        'idNom' => 'circuito-choromoro-nav',
        'color' => '#CA8235',
        'destinos' => [1, 2]
    ],
    (object)[
        'id' => 2,
        'titulo' => 'Circuito Sur',
        'idNom' => 'circuito-sur-nav',
        'color' => '#800077',
        'destinos' => [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
    ],
    (object)[
        'id' => 3,
        'titulo' => 'Circuito Valles Calchaquies',
        'idNom' => 'circuito-valles-calchaquies-nav',
        'color' => '#A83413',
        'destinos' => [17, 18, 19, 20, 21]
    ],
    (object)[
        'id' => 4,
        'titulo' => 'Circuito Yungas',
        'idNom' => 'circuito-yungas-nav',
        'color' => '#0f477c',
        'destinos' => [22, 23, 24, 25, 26, 27, 28]
    ]
];

$destinos = [
    (object)[ //Circuito Choromoro
        'id' => 1,
        'titulo' => 'San Pedro de Colalao',
        'horarios' => [1],
        'idNom' => 'san-pedro-but',
        'col' => [1] // SanPedro - San Pedro de Colalao S.R.L.
    ],
    (object)[ //Circuito Choromoro
        'id' => 2,
        'titulo' => 'Trancas',
        'horarios' => [2],
        'idNom' => 'trancas-but',
        'col' => [2] // Tran - San Pedro de Colalao S.R.L.
    ],
    (object)[ //Circuito Sur
        'id' => 3,
        'titulo' => 'Simoca',
        'horarios' => [3],
        'idNom' => 'simoca-but',
        'col' => [3]
    ],
    (object)[ //Circuito Sur
        'id' => 4,
        'titulo' => 'La cocha',
        'horarios' => [4],
        'idNom' => 'la-cocha-but',
        'col' => [4]
    ],
    (object)[ //Circuito Sur
        'id' => 5,
        'titulo' => 'Lules',
        'idNom' => 'lules-but',
        'horarios' => [5],
        'col' => [5]
    ],
    (object)[ //Circuito Sur
        'id' => 6,
        'titulo' => 'Famaillá',
        'horarios' => [6],
        'idNom' => 'famailla-but',
        'col' => [6]
    ],
    (object)[ //Circuito Sur
        'id' => 7,
        'titulo' => 'Monteros',
        'horarios' => [7],
        'idNom' => 'monteros-but',
        'col' => [7] 
    ],
    (object)[ //Circuito Sur
        'id' => 8,
        'titulo' => 'Concepcion',
        'horarios' => [8, 17],
        'idNom' => 'concepcion-but',
        'col' => [8, 17] 
    ],
    (object)[ //Circuito Sur
        'id' => 9,
        'titulo' => 'Alberdi',
        'horarios' => [9],
        'idNom' => 'alberdi-but',
        'col' => [9]
    ],
    (object)[ //Circuito Sur
        'id' => 10,
        'titulo' => 'Bella Vista',
        'horarios' => [10],
        'idNom' => 'bellavista-but',
        'col' => [10]
    ],
    (object)[ //Circuito Sur
        'id' => 11,
        'titulo' => 'Rio Colorado',
        'horarios' => [11],
        'idNom' => 'riocolorado-but',
        'col' => [11]
    ],
    (object)[ //Circuito Sur
        'id' => 12,
        'titulo' => 'Atahona',
        'horarios' => [12],
        'idNom' => 'atahona-but',
        'col' => [12]
    ],
    (object)[ //Circuito Sur
        'id' => 13,
        'titulo' => 'Monteagudo',
        'horarios' => [13],
        'idNom' => 'monteagudo-but',
        'col' => [13]
    ],
    (object)[ //Circuito Sur
        'id' => 14,
        'titulo' => 'Lamadrid',
        'horarios' => [14],
        'idNom' => 'lamadrid-but',
        'col' => [14]
    ],
    (object)[ //Circuito Sur
        'id' => 15,
        'titulo' => 'Taco Ralo',
        'horarios' => [15],
        'idNom' => 'tacoralo-but',
        'col' => [15]
    ],
    (object)[ //Circuito Sur
        'id' => 16,
        'titulo' => 'Samay Cochuna',
        'horarios' => [16],
        'idNom' => 'samaycochuna-but',
        'col' => [16]
    ],
    (object)[ //Circuito Calchaqui
        'id' => 17,
        'titulo' => 'El Mollar',
        'horarios' => [20, 18],
        'idNom' => 'el-mollar-but',
        'col' => [20, 18]
    ],
    (object)[ //Circuito Calchaqui
        'id' => 18,
        'titulo' => 'Tafi del Valle',
        'horarios' => [21, 19],
        'idNom' => 'tafi-del-valle-but',
        'col' => [21, 19] // Aconguija
    ],
    (object)[ //Circuito Calchaqui
        'id' => 19,
        'titulo' => 'Amaicha del Valle',
        'horarios' => [22],
        'idNom' => 'amaicha-del-valle-but',
        'col' => [22] // Aconguija
    ],
    (object)[ //Circuito Calchaqui
        'id' => 20,
        'titulo' => 'Colalao del Valle',
        'horarios' => [23],
        'idNom' => 'colalao-del-valle-but',
        'col' => [23] // Aconguija
    ],
    (object)[ //Circuito Calchaqui
        'id' => 21,
        'titulo' => 'Cafayate',
        'idNom' => 'cafayate-but',
        'horarios' => [24],
        'col' => [24] //130 Cartel RUTA 9
    ],
    (object)[ //Circuito Yungas
        'id' => 22,
        'titulo' => 'Yerba Buena',
        'horarios' => [25],
        'idNom' => 'yerba-buena-but',
        'col' => [25] // 100
    ],
    (object)[ //Circuito Yungas
        'id' => 23,
        'titulo' => 'Tafi Viejo',
        'horarios' => [26],
        'idNom' => 'tafi-viejo-but',
        'col' => [26] 
    ],
    (object)[ //Circuito Yungas
        'id' => 24,
        'titulo' => 'Primera Confitería',
        'horarios' => [27],
        'idNom' => 'primera-confiteria-but',
        'col' => [27] 
    ],
    (object)[ //Circuito Yungas
        'id' => 25,
        'titulo' => 'Horco Molle',
        'horarios' => [28],
        'idNom' => 'horco-molle-but',
        'col' => [28]
    ],
    (object)[ //Circuito Yungas
        'id' => 26,
        'titulo' => 'San Javier',
        'horarios' => [29],
        'idNom' => 'san-javier-but',
        'col' => [29] // 118
    ],
    (object)[ //Circuito Yungas
        'id' => 27,
        'titulo' => 'Raco y El Siabón',
        'horarios' => [30],
        'idNom' => 'raco-but',
        'col' => [30] // Buscor
    ],
    (object)[  //Circuito Yungas
        'id' => 28,
        'titulo' => 'El Cadillal y Rio Loro',
        'horarios' => [31],
        'idNom' => 'el-cadillal-but',
        'col' => [31] // Buscor
    ]
];