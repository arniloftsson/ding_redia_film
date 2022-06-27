<?php

/**
 * @file
 * Logger to log for the Redia film service.
 */


/**
 * Class RediaFilmLogger.
 */
class RediaFilmLogger
{
  private $doLogging;
  private $type;

  /**
   * RediaFilmLogger constructor.
   *
   * @TODO: Wrong parmeter descriptions?
   *
   * @param string $type
   *   The type off the log message.
   * @param bool $apikey
   *   Do debug logging.
   */
  public function __construct($type, $doLogging = false) {
    $this->doLogging = $doLogging;
    $this->type = $type;
  }

  /**
   * Set the customerid
   *
   * @TODO: Wrong parmeter descriptions?
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function logDebug($message, $variables) {
    if ($this->doLogging) {
      watchdog($this->type, $message, $variables, WATCHDOG_DEBUG);
    }
  }

  /**
   * Set the customer id
   *
   * @TODO: Wrong parmeter descriptions?
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function logError($message, array $variables = []) {
      watchdog($this->type, $message, $variables, WATCHDOG_ERROR);
  }

  /**
   * Set the customerid
   *
   * @TODO: Wrong parmeter descriptions?
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function logException(\Exception $exception, $message = null, array $variables = []) {
      watchdog_exception($this->type, $exception, $message, $variables, WATCHDOG_ERROR);

  }
}
