<?php

namespace App\Services;

use App\Services\CurlService;

class FlightService {

  /**
   * Search for all the flights in the API.
   *
   * @author Matheus H. R.
   *
   * @return array An array containing the flights available in the API.
   **/
  public static function searchFlights() {
    return CurlService::fetch("http://prova.123milhas.net/api/flights", "GET");
  }

  /**
   * Group flights by type (outbound and inbound), fare and price.
   *
   * @author Matheus H. R. 
   *
   * @return array An array containing the grouped flights.
   **/
  public static function groupFlights() {
    $flights = self::searchFlights();

    if(count($flights) === 0) return [];

    $groups = [];

    foreach ($flights as $flight) {
      if ($flight['outbound'] === 1) {
        $groups['outbound'][$flight['fare'] . "_" . $flight['price']][] = $flight;
      } else {
        $groups['inbound'][$flight['fare'] . '_' . $flight['price']][] = $flight;
      }
    }

    [$out, $in] = [$groups['outbound'], $groups['inbound']];

    $index = 0;
    $cheapestGroupPrice = INF;
    $cheapestGroupId = null;
    $result['flights'] = $flights;

    foreach ($out as $outIndex => $outValue) {
      [$outFlare, $outPrice] = explode("_", $outIndex);

      foreach ($in as $inIndex => $inValue) {

        [$inFlare, $inPrice] = explode("_", $inIndex);

        if ($outFlare === $inFlare) {

          $result['groups'][$index]['uniqueId'] = uniqid();
          $result['groups'][$index]['totalPrice'] = (count($outValue) * (float) $outPrice) + (count($inValue) * (float) $inPrice);
          $result['groups'][$index]['outbound'] = $outValue;
          $result['groups'][$index]['inbound'] = $inValue;

          if ($result['groups'][$index]['totalPrice'] < $cheapestGroupPrice) {
            $cheapestGroupPrice = $result['groups'][$index]['totalPrice'];
            $cheapestGroupId = $result['groups'][$index]['uniqueId'];
          }

          $index++;
        }
      }
    }

    $result['totalGroups'] = count($result['groups']);
    $result['cheapestPrice'] = $cheapestGroupPrice;
    $result['cheapestGroup'] = $cheapestGroupId;

    return $result;
  }
}
