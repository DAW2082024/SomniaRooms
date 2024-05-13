# SomniaRooms - API Endpoints and Objects

En este documento se indican los endpoints disponibles en la API de SomniaRooms, así como los mensajes de petición y respuesta en cada caso. (Vamos, un Swagger pero escrito a mano.)

## Config (`api/config`)

Permite al cliente obtener las variables de configuración del sistema. Ver más sobre las [variables de configuración](config-vars.md) en la documentación.

**TODO**


## Room Category (`api/room/category`)

Estos endpoints permiten obtener la información de las categorias del sistema.

**TODO**


## Search (`api/search`)

Conjunto de Endpoints para consultar el estado de las habitaciones.


## Reservas (`api/booking`)

Endpoint para la gestión y consulta de las reservas.

### `api/booking/details`
Permite obtener el detalle de una reserva. 

Es necesario enviar la información de la reserva a consultar.

**Request (JSON):**
```json
{
	"refNumber": "2024001",
	"email": "example@example.com"
}
```

Se debe incluir el número de referencia de la reserva y el correo electrónico asociado.

**Response (JSON):**

TODO


### `api/booking/create`

Endpoint para crear nuevas reservas. 

Se enviarán los datos de la reserva con el siguiente formato:

**Request (JSON)_**
```json
{
    "arrivalDate": "20240510",
    "departureDate": "20240514",
    "customerDetails": {
        "name": "Juan",
        "surname": "Perez",
        "email": "juanperez@mail.com",
        "phoneNumber": "12345678"
    },
    "rooms": [
        {
            "roomCategory": 1,
            "guestNumber": 2,
            "amount": 1
        },
        {
            "roomCategory": 1,
            "guestNumber": 3,
            "amount": 1
        }
    ]
}
```

Si la reserva es correcta se devolverá el número de referencia de la reserva:
```json
{
	"refNumber": "2024001",
	"email": "juanperez@mail.com",
}
```