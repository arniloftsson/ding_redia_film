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
   * @param string $type
   *   The type off the log message.
   * @param bool $apikey
   *   Do debug logging.
   */
  public function __construct(string $type, bool $doLogging = false) {
    $this->doLogging = $doLogging;
    $this->type = $type;
  }

  /**
   * Set the customerid
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function logDebug(string $message, array $variables) {
    if ($this->doLogging) {
      watchdog($this->type, $message, $variables, WATCHDOG_DEBUG);
    }
  }

  /**
   * Set the customerid
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function logError(string $message, array $variables = []) {
      watchdog($this->type, $message, $variables, WATCHDOG_ERROR);
  }

  /**
   * Set the customerid
   *
   * @param string $customerId
   *   Redia specifik customerid.
   */
  public function logException(\Exception $exception, string $message = null, array $variables = []) {
      watchdog_exception($this->type, $exception, $message, $variables, WATCHDOG_ERROR);

  }
}
