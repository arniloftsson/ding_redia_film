<?php

/**
 * @file
 * Film service user controller.
 */


/**
 * Class RediaFilmUserController.
 */
class RediaFilmUserController extends RediaFilmAbstractController
{
  private $session_id;
  private $is_loggedin = false;
  private $status_message;

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
   * @param bool $getObjects
   *   Whether to get object data from the service.
   * 
   * @return array $libry_loans
   *   The users loans from the service or empty array if none. Null if the request failes.
   */
  public function getLoans($getObjects = true) {
    $libry_loans = null;
    $ids = [];
    $response = $this->client->getLoans($this->session_id);
    if ($this->hasResult($response)) {
      $loans = $this->getData($response);
      foreach ($loans as $loan) {
        if (isset($loan['identifier'])) {
          $ids[] = $loan['identifier'];
        }
      }
      if (!$getObjects) {
        // We only want the ids.
        return $ids;
      }
      $objects = new RediaFilmObjectsController($this->client);
      $libry_loans = $objects->getObjects($ids);
 
      foreach ($loans as $loan) {
        if (isset($loan['identifier']) && isset($libry_loans[$loan['identifier']])) {
          $id = $loan['identifier'];
          $libry_loans[$id]->loanDate = isset($loan['loanDate']) ? date('d-m-Y', $loan['loanDate']) : '';
          $libry_loans[$id]->expireDate = isset($loan['expireDate']) ? date('d-m-Y', $loan['expireDate']) : '';
          $libry_loans[$id]->progress = isset($loan['progress']) ? $loan['progress'] : 0;
        }
      }
    } else {
      $this->status_message = 'Couldnt get the loans for the user from film service';
      $this->logger->logError('Couldnt get the loans for the user from film service: %response', ['%response' => print_r($response, TRUE)]);
    }
    return $libry_loans;
  }

  /**
   * Has the user already checked out the film.
   * 
   * @param string $id
   *   The token from the users login to the adgangsplatform.
   * 
   * @return bool $isCheckout
   *   Is the film checked out.
   */
  public function isCheckedOut(string $id) {
    $libry_loans = $this->getLoans(false);
    foreach ($libry_loans as $loans) {
      if ($loans == $id) {
        return true;
      }
    } 
    return false;
  }

 /**
   * Gets the users eligibility from the service. The user must be logged in.
   * 
   * @return bool $eligible 
   *   If the user is eligible to loan a film.
   */
  public function getUserEligible() {
    $response = $this->client->getUserEligible($this->session_id);

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
}
