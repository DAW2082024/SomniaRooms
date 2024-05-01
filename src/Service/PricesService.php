<?php

namespace App\Service;

use App\Entity\FareTable;
use App\Entity\RoomCategory;
use App\Repository\FareTableRepository;
use App\Repository\RoomFareRepository;

/**
 * Servicio para calcular los precios de las estancias.
 * En un futuro este servicio deberá admitir múltiples estrategias de cálculo de precios finales.
 */
class PricesService
{

    public function __construct(
        private FareTableRepository $repoFareTable,
        private RoomFareRepository $repoRoomFares
    ) {
    }

    /**
     * Calcula los precios de la estancia para la categoría y periodo indicados.
     * Devolverá para cada numero de personas, el precio final de la estancia.
     *
     * e. g. --> [ 2 => 120€, 3 => 180€ ]
     *
     * @return array Contiene los precios en función del número de personas.
     */
    public function getCategoryPricesForSearch(RoomCategory $category, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $daysDiff = $startDate->diff($endDate)->days;
        if (!$daysDiff) {
            return "Invalid date period :( ";
        }

        $fareTablesForPeriod = $this->repoFareTable->findFareTable($category->getId(), $startDate, $endDate);

        //Obtenemos el número de personas permitido por todos los tarifarios activos.
        $allowedGuestNumbers = $this->repoFareTable->getAllowedGuestNumberOnFareTableList($fareTablesForPeriod);

        $rs = [];

        //Calculamos los precios para cada GuestNumber permitido.
        foreach ($allowedGuestNumbers as $currentGuestNumber) {

            //Calculamos el precio de cada día.
            // e.g. --> [ "2024-04-30" => 20, "2024-05-01" => 28, "2024-05-02" => 20, ...].
            $dailyPrices = [];
            $currentDate = $startDate; //TODOME: Comprobar si es tipo referencia y está cambiando.
            for ($i = 0; $i < $daysDiff; $i++) {
                $strCurrentDate = $currentDate->format("Y-m-d");

                //Obtenemos el tarifario que aplica --> TODOME: Buscar si se puede cachear esto, para no recarlcularlo para cada GuestNumber.
                $currentFareTableList = \array_filter($fareTablesForPeriod, function (FareTable $element) use ($currentDate) {
                    return $element->isActiveOnDate($currentDate);
                });

                if (count($currentFareTableList) != 1) {
                    //ERROR
                    $dailyPrices[$strCurrentDate] = "ERROR - Number of FareTable found: " . count($currentFareTableList);
                    continue;
                }

                $currentFareTable = $currentFareTableList[0];

                //TODOME: -> Obtener precio específico (RoomPrices).

                //Si no hay precio, calcular mediante tarifarios.
                $roomFares = $currentFareTable->getRoomFaresByGuestNumber($currentGuestNumber);
                if (count($roomFares) < 1) {
                    //ERROR
                    $dailyPrices[$strCurrentDate] = "ERROR - Invalid RoomFares found";
                    continue;
                }

                //TODOME: -> Handle conditions...
                $fareAmount = $roomFares->first()->getFareAmount();
                $dailyPrices[$strCurrentDate] = $fareAmount;

                //Add 1 day to current date.
                $currentDate->add(new \DateInterval("P1D"));
            }

            $rs[$currentGuestNumber] = $dailyPrices;
        }

        //Aplicamos la estrategia de precios configurada para calcular el precio final de la estancia.
        // TODOME: ...

        return $rs;

    }

}
