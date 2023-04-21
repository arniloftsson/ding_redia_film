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
  public $loanDuration;
  public $isEligible = false;
  public $loanPercentage;

  public $nextLoanDate;
  public $nextLoanDateRaw;
  public $nextLoanDays;
  public $nextLoanHours;
  public $nextLoanMinutes;

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
   * Sets session id for the user.
   *
   * @param string $session_id
   *   Sets the session id.
   */
  public function setSessionid($session_id) {
    $this->is_loggedin = true;
    $this->session_id = $session_id;
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
  public function login($dbc_token) {
    $response = $this->client->login($dbc_token);

    if ($this->hasResult($response) && isset($response['result']['session'])) {
      $this->session_id = $response['result']['session'];
      $this->is_loggedin = true;
    }
    else {
      $this->is_loggedin = false;
      $this->status_message = 'Could not log user in to the film service';
      $this->logger->logError('Could not log the user in to the service: %response', ['%response' => print_r($response, TRUE)]);
    }

    return $this->is_loggedin;
  }

  /**
   * Gets the users loan from the service The user must be logged in.
   *
   * @param bool $getObjects
   *   Whether to get object data from the service.
   *
   * @return array|null $libry_loans
   *   The user's loans from the service or empty array if none. Null if the
   *   request fails.
   */
  public function getLoans() {
    $libry_loans = [];
    $ids = [];

    $response = $this->client->getLoans($this->session_id);
    if ($this->hasResult($response)) {
      
      $loans = $this->getData($response);
      foreach ($loans as $loan) {
        if (isset($loan['identifier'])) {
          $ids[] = $loan['identifier'];
        }
      }
      foreach ($loans as $loan) {
        $libry_object = new RediaFilmObject();
        $id = $loan['identifier'];
        $libry_object->id = $id;
        $libry_object->loanDate = isset($loan['loanDate']) ? format_date($loan['loanDate'], 'ding_material_lists_date') : '';
        $libry_object->expireDate = isset($loan['expireDate']) ? format_date($loan['expireDate'], 'ding_material_lists_date') : '';
        $libry_object->progress = isset($loan['progress']) ? $loan['progress'] : 0;
        $libry_loans[$id] = $libry_object;
      }
    }
    else {
      $this->status_message = 'Could not get the loans for the user from film service';
      $this->logger->logError('Could not get the loans for the user from film service: %response', ['%response' => print_r($response, TRUE)]);
    }
    return $libry_loans;
  }

 /**
   * Gets the user's eligibility from the service. The user must be logged in.
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
      $this->loanDuration = isset($data['loanDuration']) ? $data['loanDuration'] : 0;
      $this->nextLoanDate = isset($data['nextLoanDate']) ? date('d-m-Y', $data['nextLoanDate']) : 0;
      $this->nextLoanDateRaw = isset($data['nextLoanDate']) ?  $data['nextLoanDate'] : 0;

      if ($this->currentLoanCount < $this->maxNumberOfLoans) {
        $this->isEligible = true;
      }
      $this->calculateNextLoanDate();
      $this->loanPercentage = (int) ($this->currentLoanCount / $this->maxNumberOfLoans * 100);
      return $this;
    }
    else {
      $this->status_message = 'Could not check the users status from the from film service';
      $this->logger->logError('Could not get the loans for the user from film service: %response', ['%response' => print_r($response, TRUE)]);

      return false;
    }
  }

  /**
   * Calculate the difference to nextloandate
   *
   */
  private function calculateNextLoanDate() {
    if ($this->isEligible) {
      $this->nextLoanDays = 0;
      $this->nextLoanHours = 0;
      $this->nextLoanMinutes = 0;
    } else {
      $next = new DateTime();
      $next->setTimestamp($this->nextLoanDateRaw);
      $now = new DateTime();
      $diff = ($now->diff($next, true));

      $this->nextLoanDays = $diff->d;
      $this->nextLoanHours = $diff->h;
      $this->nextLoanMinutes = $diff->i;
    }
  }
}
