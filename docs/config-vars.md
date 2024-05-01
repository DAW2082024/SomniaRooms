# SomniaRooms - App Configuration Variables.

This document contains a list of all config variables of SomniaRooms. Variables are stored in DB (table _`config_variable`_) and can be configured from admin panel.

Variables are divided in sections. Some sections are ony accesible from SomniaRoom, but others can be retrieved by anyone using config endpoint (_`/config`_).

## List of sections:

WIP

TODO -> Add default table with basic information to all sections.

### **Company**
Variables in this section contains information about the Hotel.

- SectionName -> company
- Availability -> public
- Nº variables -> X

**Variable list:**

- Name - Name of hotel.



### **Price Management**
Configure how backend services calculates prices for bookings.

- SectionName -> price_mng
- Availability -> internal
- Nº variables -> X

**Variable list:**

WIP

#### FinalPriceCalculation.
Establece cómo se deben tratar los precios de cada día para generar el precio final de la estancia.

Algunos algoritmos para estos serán:
- Precio más alto.
- Precio más bajo.
- Suma de precios. --> Por defecto.
- Media de precios.

#### MultipleRoomFareValid
Estrategia de selección de tarifa en caso de que haya varias válidas (mientras no exista un sistema de prioridad de tarifas...)

Posibles algoritmos:
- Precio más alto.
- Precio más bajo.
- Media.


