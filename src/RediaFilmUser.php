<?php

/**
 * @file
 * Film service User.
 */


/**
 * Class RediaFilmUser.
 */
class RediaFilmUser
{
  private $client;
  private $session_id;
  private $is_loggedin = false;
  private $status_message;



  /**
   * RediaFilmUser constructor.
   *
   * @param RediaFilmRequest $client
   *   The service endpoint for digital article service.
   */
  public function __construct(RediaFilmRequest $client) {
    $this->client= $client;
  }

 /**
   * The session id for the user.
   * 
   * @return string $session_id
   *   The current request object.
   */
  public function getSessionid() {
    return $this->session_id;
  }

  /**
   * Is the user logged in.
   * 
   * @return bool $is_loggedin
   *   Is the user logged in.
   */
  public function isLoggedin() {
    return $this->is_loggedin;
  }

  /**
   * Get the status message.
   * 
   * @return string $status_message
   *   Status message.
   */
  public function getStatusMessage() {
    return $this->status_message;
  }

    /**
   * Gets the customerid from the service.
   * 
   * @param string $dbc_token
   *   The token from the users login to the adgangsplatform.
   * 
   * @return bool $is_loggedin
   *   Is the user logged in.
   */
  public function login(string $dbc_token) {
    $response = $this->client->login($dbc_token);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia4.txt", print_r($response , TRUE), FILE_APPEND);

    if (isset($response['result']) && isset($response['result']['session']) && isset($response['result']['result'])) {
      if ($response['result']['result']) {
        $this->session_id = $response['result']['session'];
        $this->is_loggedin = TRUE;
      } else {
        $this->is_loggedin = FALSE;
        $this->status_message = 'Couldnt log user in to the film service';
        $this->logger->logError('Couldnt log the user in to the service: %response', ['%response' => print_r($response , TRUE)]);
      }     
    } else {
      $this->is_loggedin  = FALSE;
      $this->status_message = 'Invalid response from the film service';
      $this->logger->logError('Invalid response from server: %response', ['%response' => print_r($response , TRUE)]);
    }
    return $this->is_loggedin;
  }

 /**
   * Gets the users loan from the service The user must be logged in.
   * 
   * @return array $session //TODO
   *   The session id from the service or null if there is a error.
   */
  public function getLoans() {
    $response = $this->client->getLoans($this->session_id);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
  }

 /**
   * Gets the users eligibility from the service The user must be logged in.
   * 
   * @return array $session //TODO
   *   The session id from the service or null if there is a error.
   */
  public function getUserEligble() {
    $response = $this->client->getUserEligble($this->session_id);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
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
  public function getToken() {
    $response = $this->client->getToken($this->session_id);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
  }

}