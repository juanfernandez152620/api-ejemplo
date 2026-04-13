const Destinos = [
    {
        "id": 14,
        "titulo": "El Cadillal",
        "linea": "Buscor",
        "costo": "$230",
        "parada": "Boletería Nº 03 - Plataforma Nº 12 y 13 terminal de Ómnibus.",
        "indicaciones":"",
        "horarios": [
            {
                dias: "Lunes a sabados",
                ida: ["07:00", "09:00", "11:30", "14:30", "17:00", "19:00"],
                vuelta: ['8:00', '10:15', '12:30', '16:00', '18:00', '20:00']
            },
            {
                dias: "Domingos y feriados",
                ida: ["09:00", "11.30", "14:30", "17:00", "19:00"],
                vuelta: ['10:15', '12:30', '16:00', '18:00', '20:00']
            }

        ],
        "icono": "cadillal-2-1.jpg", 
        "visible": true,
        "activo": true
    },
    {
        "id": 18,
        "titulo": "San Javier",
        "linea": "118",
        "costo": "Cristo $240.- Cascada $341.-  Portezuelo $419",
        "parada": " Plataforma Nº 57 a Nº 58 de Terminal de Ómnibus.",
        "indicaciones":"La vuelta es desde El Portezuelo",
        "horarios": [
            {
                dias: "Lunes a viernes",
                ida: ["12:30", "16:00"],
                vuelta: ['06:00', '14:15', '18:00']
            },
            {
                dias: "Sábados",
                ida: ["12:00", "16:00"],
                vuelta: ['07:00', '14:00', '18:00']
            },
            {
                dias: "Domingos y feriados",
                ida: ["8:00", "12:00", "16:00"],
                vuelta: ['10:00', '14:00', '18:00']
            }

        ],
        "icono": "sanjavier-1-1.jpg",
        "visible": true,
        "activo": true
    }
]