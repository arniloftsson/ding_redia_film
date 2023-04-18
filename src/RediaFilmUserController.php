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
  public function getLoans($getObjects = true) {
    $libry_loans = null;
    $ids = [];

    $user_token = ding_redia_film_get_user_token();
    $cached_response = cache_get('film-user-loans-' . $user_token);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/cache17.txt", print_r($cached_response, TRUE), FILE_APPEND);
    if (!$cached_response) {
      $response = $this->client->getLoans($this->session_id);
      file_put_contents("/var/www/drupalvm/drupal/web/debug/cache16.txt", print_r($response, TRUE), FILE_APPEND);
    } else {
      $response = json_decode($cached_response->data, true);
      file_put_contents("/var/www/drupalvm/drupal/web/debug/cache15.txt", print_r($response, TRUE), FILE_APPEND);
    }

    if ($this->hasResult($response)) {
      cache_set('film-user-loans-' . $user_token, json_encode($response), 'cache', 3600);
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
    }
    else {
      $this->status_message = 'Could not get the loans for the user from film service';
      $this->logger->logError('Could not get the loans for the user from film service: %response', ['%response' => print_r($response, TRUE)]);
    }

    return $libry_loans;
  }

  /**
   * Has the user already checked out the film.
   *
   * @param string $id
   *   The token from the user login to the adgangsplatform.
   *
   * @return bool $isCheckout
   *   Is the film checked out.
   */
  public function isCheckedOut($id) {
    $libry_loans = $this->getLoans(false);
    foreach ($libry_loans as $loans) {
      if ($loans == $id) {
        return true;
      }
    }

    return false;
  }

 /**
   * Gets the user's eligibility from the service. The user must be logged in.
   *
   * @return bool $eligible
   *   If the user is eligible to loan a film.
   */
  public function getUserEligible() {
    $user_token = ding_redia_film_get_user_token();
    $cached_response = cache_get('film-user-eligible-' . $user_token);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/cache7.txt", print_r($cached_response, TRUE), FILE_APPEND);
    if (!$cached_response) {
      $response = $this->client->getUserEligible($this->session_id);
      file_put_contents("/var/www/drupalvm/drupal/web/debug/cache6.txt", print_r($response, TRUE), FILE_APPEND);
    } else {
      $response = json_decode($cached_response->data, true);
      file_put_contents("/var/www/drupalvm/drupal/web/debug/cache5.txt", print_r($response, TRUE), FILE_APPEND);
    }

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
      cache_set('film-user-eligible-' . $user_token, json_encode($response), 'cache', 3600);
      return true;
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
  private function calculateNextLoanDate()
  {
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
