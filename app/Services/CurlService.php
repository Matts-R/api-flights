<?php

namespace App\Services;

use CurlHandle;

class CurlService {
  /**
   * Makes a HTTP request to a given url.
   *
   * @author Matheus H. R.
   *
   * @param string $url The url that will be requested.
   * @param string $method The HTTP method to be used.
   * @param array $headers An optional array containing customs headers to be used.
   * @param array $body An optional array containing the body of the request, if there is any.
   * 
   * @return array Returns an array containg the status of the request and the result.
   * 
   * @version 1.0  15/04/2022
   **/
  public static function fetch($url, $method, $headers = [], $body = []) {

    $curl = self::mountCurl($url, $method, $headers, $body);

    $response = json_decode(curl_exec($curl), true);
    
    self::checkRequestStatus($curl);
    
    curl_close($curl);

    return $response;
  }

  /**
   * Create and fill a curl Resource with basic options.
   *
   * @author Matheus H. R.
   *
   * @param string $url The url that will be requested.
   * @param string $method The HTTP method to be used.
   * @param array $headers An optional array containing customs headers to be used.
   * @param array $body An optional array containing the body of the request, if there is any.
   * 
   * @return \CurlHandle Returns a created and configured CurlHandle.
   * 
   * @version 1.0 15/04/2022
   **/
  private static function mountCurl($url, $method, $headers, $body) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => $headers
    ));

    if ($body) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));

    return $curl;
  }

  /**
   * Check if the requests was successful using its HTTP code.
   *
   * @author Matheus H. R.
   *
   * @param CurlHandle $curl The CurlHadle that HTTP code will be checked.
   * @return void
   * @throws \Exception Throw an \Exception if the request went wrong.
   * 
   * @version 1.0 15/04/2022
   **/
  public function checkRequestStatus($curl)
  {
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if($statusCode >= 300 || $statusCode < 200) throw new \Exception("Request failed with status code: $statusCode");
  }
}
