<?php

/**
 * @file
 * Film service User.
 */


/**
 * Class RediaFilmUser.
 */
class RediaFilmUser extends RediaFilmAbstractObject
{
  private $session_id;
  private $is_loggedin = false;
  private $status_message;
  private $loans;

  public $status;
  public $maxNumberOfLoans;
  public $currentLoanCount;
  public $nextLoanDate;
  public $loanDuration;
  public $isElligble = false;

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
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia4.txt", print_r($response, TRUE), FILE_APPEND);

    if ($this->hasResult($response) && isset($response['result']['session'])) {
      $this->session_id = $response['result']['session'];
      $this->is_loggedin = true;
    } else {
      $this->is_loggedin = false;
      $this->status_message = 'Couldnt log user in to the film service';
      $this->logger->logError('Couldnt log the user in to the service: %response', ['%response' => print_r($response, TRUE)]);
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
    if ($this->hasResult($response)) {
      $this->loans = $this->getData($response);
    } else {
      $this->status_message = 'Couldnt get the loans for the user from film service';
      $this->logger->logError('Couldnt get the loans for the user from film service: %response', ['%response' => print_r($response, TRUE)]);
    }
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
  }

 /**
   * Gets the users eligibility from the service The user must be logged in.
   * 
   * @return array $session //TODO
   *   The session id from the service or null if there is a error.
   */
  public function getUserEligble() {
    $response = $this->teststatus();//$this->client->getUserEligble($this->session_id);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/checkout3.txt", print_r($response , TRUE), FILE_APPEND);

    if ($this->hasResult($response)) {
      $data = $this->getData($response);
      $this->maxNumberOfLoans = isset($data['maxNumberOfLoans']) ? $data['maxNumberOfLoans'] : 0;
      $this->currentLoanCount = isset($data['currentLoanCount']) ? $data['currentLoanCount'] : 0;
      $this->loanDuration = isset($data['loanDuration'] ) ? $data['loanDuration'] : 0;
      $this->nextLoanDate = isset($data['nextLoanDate']) ? date('d-m-Y', $data['nextLoanDate']) : 0;
      if ($this->currentLoanCount < $this->maxNumberOfLoans ) {
        $this->isElligble = true;
      }
      return true;
    } else {
      $this->status_message = 'Couldnt check the users status from the from film service';
      $this->logger->logError('Couldnt get the loans for the user from film service: %response', ['%response' => print_r($response, TRUE)]);
      return false;
    }
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

  function teststatus() {
    $res = '{ 
      "jsonrpc": "2.0", 
      "id": 11, 
      "result": { 
      "result": true, 
      "session": "g6i2srbu5q4a0gfvrfur702470", 
      "data": { 
      "maxNumberOfLoans": 10000, 
      "currentLoanCount": 0, 
      "currentReservedCount": 0, 
      "loanDuration": 30, 
      "nextLoanDate": 1642602569 
      }, 
      "message": "", 
      "language": "en", 
      "code": 0 
      } 
      }      
  ';
    return json_decode($res, true);
  }

}
