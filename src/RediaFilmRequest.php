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
  private $customerId = '';


  /**
   * RediaFilmRequest constructor.
   *
   * @param string $url
   *   The service endpoint for the Libry film service.
   * @param string $apikey
   *   The apikey to login with.
   * @param RediaFilmLogger $logger
   *   A custom logger to log calls and exceptions.
   * @param string $agency_id
   *   The id off the agency.
   * @param string $version
   *   The version of the service.
   * @param string $language
   *   The language of the service.
   * @param string $customerId
   *   The customerId for the service.
   */
  public function __construct(string $url, string $apikey, RediaFilmLogger $logger, string $agency_id, string $version, string $language, string $customerId = null) {
    $this->url = $url;
    $this->apikey= $apikey;
    $this->logger = $logger;
    $this->agency_id = $agency_id;
    $this->version = $version;
    $this->language = $language;
    if (isset($customerId)) {
      $this->customerId = $customerId;
    } else {
      $this->getCustomerIdFromService();
    }
  }

   /**
   * Gets the logger.
   * 
   * @return RediaFilmLogger $logger.
   *   The logger.
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * Gets the customerid from the service.
   */
  private function getCustomerIdFromService() {
    $params = [];
    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->agency_id;
    $response = $this->filmServiceRequest('watch.getLibraryDetails', $params);
    if (isset($response['result']) &&  isset($response['result']['data']) && isset($response['result']['data']['customerId'])) {
      $this->customerId = $response['result']['data']['customerId'];
    } else {
      $this->status_message = 'Couldnt get the customerid from the film service';
      $this->logger->logError('Couldnt get the customerid from the film service: %response', ['%response' => print_r($response, TRUE)]);
    }
  }

  public function setCustomerId(string $customerId) {
    $this->customerId = $customerId;
  }

  public function getCustomerId() {
   return  $this->customerId;
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
    $params = [];
    $params[] = $this->apikey;
    $params[] = $this->customerId; 
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
  public function getObject(array $identifiers) {
    $params = [];

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
    $params[] = $identifiers;

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
  public function createLoan(string $identifier, string $sessionId) {
    $params = [];

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
    $params[] = $identifier;

    return $this->filmServiceRequest('watch.checkout', $params, $sessionId);
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
  public function getUserEligible(string $session_id) {
    $params = [];

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
  public function getToken(string $sessionId) {
    $params = [];

    $params[] = $this->apikey;
    $params[] = $this->version;
    $params[] = $this->language;
    $params[] = $this->customerId;
  
    return $this->filmServiceRequest('watch.getToken', $params, $sessionId);
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
  function filmServiceRequest($method, $params, $cookie = null) {
    try {
      $client = new Client();
      $options = [
        'json' => [
          "jsonrpc" => "2.0",
          "id" => 11,
          "method" => $method,
          "params" => $params
        ],
      ];
      if (isset($cookie)) {
        $values = ['PHPSESSID' => $cookie];
        $domain = parse_url($this->url);
        $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($values, $domain['host']);
        $options['cookies'] = $cookieJar;
      }
      $this->logger->logDebug('Call options to libry service: %options', ['%options' => json_encode($options)]);
      $startTime = explode(' ', microtime());
      $response = $client->post($this->url, $options);
      $content = $response->getBody()->getContents();
      $decoded_response = json_decode($content, true);
      $stopTime = explode(' ', microtime());
      $time = floatval(($stopTime[1]+$stopTime[0]) - ($startTime[1]+$startTime[0]));
      file_put_contents("/var/www/drupalvm/drupal/web/debug/time1.txt", print_r($method . ':' . $time . "\n" , TRUE), FILE_APPEND);
      $this->logger->logDebug('Response from libry service: %json', ['%json' => $content]);

    } catch (Exception $e) {
      $this->logger->logException($e);
    }
    return $decoded_response;
  }
}
