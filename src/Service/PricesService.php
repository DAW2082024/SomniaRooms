<?php

namespace App\Service;

use App\Entity\FareTable;
use App\Entity\RoomCategory;
use App\Repository\FareTableRepository;
use App\Repository\RoomFareRepository;

use Doctrine\Common\Collections\ArrayCollection;

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
    public function getFinalPricesForSearch(RoomCategory $category, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
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
            $dailyPrices = $this->getDailyPricesForSearch($startDate, $endDate, $currentGuestNumber, $fareTablesForPeriod);

            $rs[$currentGuestNumber] = $dailyPrices;
        }

        //Aplicamos la estrategia de precios configurada para calcular el precio final de la estancia.
        // TODOME: Crear y configurar diversas estrategias de precios -> Por el momento utilizamos "Suma del precio de cada día".

        $finalResult = [];
        foreach ($rs as $guestNum => $priceArray) {
            $finalAmount = \array_reduce($priceArray, function ($acc, $element) {
                return $acc + $element;
            });

            $finalResult[$guestNum] = $finalAmount;
        }

        return $finalResult;

    }

    /**
     * Dado un número de huéspedes, calcula el precio de cada día del periodo indicado.
     * @param \DateTimeInterface $startDate Fecha de inicio del periodo.
     * @param \DateTimeInterface $endDate Fecha fin del periodo.
     * @param int $guestNumber Número de huéspedes para los que calcular los precios.
     * @param array $fareTableList Array con los tarifarios que aplican en el periodo indicado.
     * @return array Devuelve un array con los precios: e.g. --> [ "2024-04-30" => 2500, "2024-05-01" => 3500, "2024-05-02" => 3000, ...].
     */
    public function getDailyPricesForSearch(\DateTimeInterface $startDate, \DateTimeInterface $endDate, int $guestNumber, array $fareTableList): array
    {
        $daysDiff = $startDate->diff($endDate)->days;

        $dailyPrices = [];
        $currentDate = new \DateTime($startDate->format("Y-m-d")); //TODOME: Comprobar si es tipo referencia y está cambiando.
        for ($i = 0; $i < $daysDiff; $i++) {
            $strCurrentDate = $currentDate->format("Y-m-d");

            //Obtenemos el tarifario que aplica --> TODOME: Buscar si se puede cachear esto, para no recarlcularlo para cada GuestNumber.
            $currentFareTableList = \array_filter($fareTableList, function (FareTable $element) use ($currentDate) {
                return $element->isActiveOnDate($currentDate);
            });

            if (count($currentFareTableList) != 1) {
                $dailyPrices[$strCurrentDate] = "ERROR - Number of FareTable found: " . count($currentFareTableList);
                continue;
            }

            $currentFareTable = array_values($currentFareTableList)[0];

            //TODOME: -> Obtener precio específico (RoomPrices).

            //Si no hay precio, calcular mediante tarifarios.
            $roomFares = $currentFareTable->getRoomFaresByGuestNumber($guestNumber);
            if (count($roomFares) < 1) {
                $dailyPrices[$strCurrentDate] = "ERROR - Invalid RoomFares found";
                continue;
            }

            //Si sólo hay 1 tarifa, aplico esa, sino calculo la apropiada según condiciones.
            $fareAmount = null;
            if (count($roomFares) == 1) {
                $fareAmount = $roomFares->first()->getFareAmount();
            } else {
                $fareAmount = $this->calculateFareApplyingConditions($roomFares, $currentDate);
            }

            $dailyPrices[$strCurrentDate] = $fareAmount;

            //Add 1 day to current date.
            $currentDate->add(new \DateInterval("P1D"));
        }

        return $dailyPrices;
    }

    /**
     * Calcula el precio a aplicar según las condiciones de las tarifas.
     * @param RoomFare[] $roomFareList Lista con las posibles tarifas.
     * @param DateTimeInterface $fareCondDate Fecha utilizada para las condiciones.
     *
     */
    public function calculateFareApplyingConditions(ArrayCollection $roomFareList, \DateTimeInterface $fareCondDate): int
    {
        $currentDayOfWeek = $fareCondDate->format("w");

        //Comprobamos si a alguna de las tarifas se la aplica la condición de "DayType" y coincide con el actual.
        // Si es así, devolvemos su valor.
        foreach ($roomFareList as $roomFare) {
            $dayType = $roomFare->getDayType();
            if ($dayType == null || $dayType == "") {
                continue;
            }
            if($dayType == $currentDayOfWeek) {
                return $roomFare->getFareAmount();
            }
        }

        //TODOME: Si existen múltiples tarifas posibles, debería haber una estrategia para elegir una u otra. Variable de configuración para esto.
        $faresWithoutConditions = $roomFareList->filter(function ($element) {
            return $element->getDayType() == null;
        });

        return $faresWithoutConditions->first()->getFareAmount();
    }
}
