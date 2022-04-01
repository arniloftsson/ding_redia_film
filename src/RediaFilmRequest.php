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
  private $agency_id;
  private $version;
  private $language;
  private $customerId = 'bob';


  /**
   * RediaFilmRequest constructor.
   *
   * @param string $url
   *   The service endpoint for digital article service.
   * @param string $apikey
   *   The apikey to login with.
   * @param RediaFilmLogger $logger
   *   A custom logger to log calls and exceptions.
   * @param string $agency_id
   *   The id off the agency.
   */
  public function __construct(string $url, string $apikey, RediaFilmLogger $logger, string $agency_id, string $version, string $language) {
    $this->url = $url;
    $this->apikey= $apikey;
    $this->logger = $logger;
    $this->agency_id = $agency_id;
    $this->version = $version;
    $this->language = $language;
  }

  /**
   * Gets the customerid from the service.
   */
  private function getCustomerId() {
    $params = [];
    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->agency_id;
    $response = $this->filmServiceRequest('watch.getLibraryDetails', $params);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
  }

  /**
   * Gets the customerid from the service.
   * 
   * @param string $dbc_token
   *   The token from the users login to the adgangsplatform.
   * 
   * @return array $response
   *   The response from the film service.
   */
  public function login(string $dbc_token) {
    // if (!(isset($this->customerId))) {
    //   $this->customerId = $this->getCustomerId();
    // }
    $params = [];
    $params[] = $this->apikey;
    $params[] = 'bob';
    $params[] = $dbc_token;

    return $this->filmServiceRequest('watch.webLogin', $params);
  }

  /**
    * Gets at film object from the service.
    * 
    * @param string $identifier
    *   The identifier off the object.
    * 
    * @return array $response
    *   The response from the film service.
    */
  public function getObject(string $identifier) {
    $params = [];

    if (!(isset($this->customerId))) {
        $this->customerId = $this->getCustomerId();
    }

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
    $params[] = $identifier;

    return $this->filmServiceRequest('watch.getObjects', $params);
  }

  /**
   * Creates a loan at the service. The user must be logged in.
   * 
   * @param string $identifier
   *   The identifier off the object.
   * 
   * @param string $session_id
   *   The session id.
   * 
   * @return array $response
   *   The response from the film service.
   */
  public function createLoan(string $identifier, RediaFilmUser $user) {
    $params = [];

    if (!(isset($this->customerId))) {
        $this->customerId = $this->getCustomerId();
    }

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
    $params[] = $identifier;

    return $this->filmServiceRequest('watch.checkout', $params, $user->getSessionid());
  }

  /**
   * Gets the users loan from the service The user must be logged in.
   * 
   * @param string $session_id
   *   The session id.
   * 
   * @return array $response
   *   The response from the film service.
   */
  public function getLoans(string $session_id) {
    $params = [];

    if (!(isset($this->customerId))) {
        $this->customerId = $this->getCustomerId();
    }

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
  
    return $this->filmServiceRequest('watch.getLoans', $params, $session_id);
  }

  /**
   * Gets the users eligibility from the service The user must be logged in.
   * 
   * @param string $session_id
   *   The session id.
   * 
   * @return array $response
   *   The response from the film service.
   */
  public function getUserEligble(string $session_id) {
    $params = [];

    if (!(isset($this->customerId))) {
        $this->customerId = $this->getCustomerId();
    }

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
  
    return $this->filmServiceRequest('watch.userLoanInfo', $params, $session_id);
  }

  /**
   * Gets the token from the service in order to watch the film. The user must be logged in.
   * 
   * @param string $session_id
   *   The session id.
   * 
   * @return array $session //TODO
   *   The session id from the service or null if there is a error.
   */
  public function getToken(string $session_id) {
    $params = [];

    if (!(isset($this->customerId))) {
        $this->customerId = $this->getCustomerId();
    }

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
  
    return $this->filmServiceRequest('watch.getToken', $params, $session_id);
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
          "id" => 11,
          "method" => $method,
          "params" => $params
        ],
        //'cookies' => $jar
      ];
      file_put_contents("/var/www/drupalvm/drupal/web/debug/redia1.txt", print_r($options , TRUE), FILE_APPEND);
      file_put_contents("/var/www/drupalvm/drupal/web/debug/redia3.txt", print_r(json_encode($options['json']) , TRUE), FILE_APPEND);
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
