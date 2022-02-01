<?php

/**
 * @file
 * Client to communicate with the Redia film service.
 */

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;


/**
 * Class RediaFilmRequest.
 */
class RediaFilmRequest
{
  private $url;
  private $apikey;
  private $logger;

  private $customerId;


  /**
   * RediaFilmRequest constructor.
   *
   * @param string $url
   *   The service endpoint for digital article service.
   * @param string $apikey
   *   The apikey to login with.
   */
  public function __construct(string $url, string $apikey, RediaFilmLogger $logger) {
    $this->url = $url;
    $this->wsPassword = $apikey;
    $this->logger = $logger;
  }

  /**
   * Set the customerid
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function setCustomerId(string $customerId) {
    $this->customerId = $customerId;
  }



  /**
   * Set additional parameters to ting request.
   *
   * @param string $token
   *   Single sign on token.
   *
   * @param string $customer_id
   *   Id off the library which site the user is visting.
   *
   * @return string $libry_token
   *   Returns token from the libry service.
   */
  function ding_redia_film_get_film_service_token($token, $customer_id, $api_key) {
    $libry_token = NULL;

    try {
      $client = new Client();
      $jar = new CookieJar();

      $options = [
        'json' => [
          "jsonrpc" => "2.0",
          "id" => 1,
          "method" => "watch.webLogin",
          "params" => [$api_key, $customer_id, $token]
        ],
        'cookies' => $jar
      ];
      _ding_redia_film_debug_log('Call options to libry service: %options', ['%options' => json_encode($options)]);

      $film_service_server = variable_get('ding_redia_film_server', DING_REDIA_FILM_SERVER);
      $response = $client->post($film_service_server, $options);
      $content = $response->getBody()->getContents();

      $jar->toArray();
      $json = json_decode($content, true);

      _ding_redia_film_debug_log('Response from libry service: %json', ['%json' => $content]);

      if (isset($json['result']) && isset($json['result']['session'])) {
        $libry_token = $json['result']['session'];
      }
    } catch (Exception $e) {
      watchdog('ding_redia_film', 'Unable to retrieve token from Libry service: %message', ['%message' => $e->getMessage()], WATCHDOG_ERROR);
    }
    return $libry_token;
  }

    /**
   * Make request to the film service
   *
   * @param string $method
   *   The method to call on the service.
   *
   * @param array $params
   *   The parameters to send.
   *
   * @param bool $cookie
   *   Set cookie on request.
   *
   * @return array $json
   *   Returns json_decoded response.
   */
  function filmServiceRequest($method, $params, $cookie = false) {
    try {
      $client = new Client();
      $jar = new CookieJar();

      $options = [
        'json' => [
          "jsonrpc" => "2.0",
          "id" => 1,
          "method" => $method,
          "params" => $params
        ],
        'cookies' => $jar
      ];

      //$film_service_server = variable_get('ding_redia_film_server', DING_REDIA_FILM_SERVER);
      //$jar->toArray();
      $this->logger->logDebug('Call options to libry service: %options', ['%options' => json_encode($options)]);

      $response = $client->post($this->url, $options);
      $content = $response->getBody()->getContents();
      $decoded_response = json_decode($content, true);

      $this->logger->logDebug('Response from libry service: %json', ['%json' => $content]);

    } catch (Exception $e) {
      $this->logger->logException($e);
    }
    return $decoded_response;
  }
}
