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
   * @param bool $doLogging
   *   Do debug logging.
   */
  public function __construct($type, $doLogging = false) {
    $this->doLogging = $doLogging;
    $this->type = $type;
  }

  /**
   * Set the customerid
   *
   * @param string $message
   *   Message to log.
   * 
   * @param array $variables
   *   Variables in the message.
   */
  public function logDebug($message, array $variables = []) {
    if ($this->doLogging) {
      watchdog($this->type, $message, $variables, WATCHDOG_DEBUG);
    }
  }

  /**
   * Set the customer id
   *
   * @param string $message
   *   Message to log.
   * 
   * @param array $variables
   *   Variables in the message.
   */
  public function logError($message, array $variables = []) {
      watchdog($this->type, $message, $variables, WATCHDOG_ERROR);
  }

  /**
   * Set the customerid
   *
   * @param Exception $exception
   *   The exception thrown.
   *
   * @param string $message
   *   Message to log.
   * 
   * @param array $variables
   *   Variables in the message.
   */
  public function logException(\Exception $exception, $message = null, array $variables = []) {
      watchdog_exception($this->type, $exception, $message, $variables, WATCHDOG_ERROR);

  }
}
