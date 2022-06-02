<?php

/**
 * @file
 * Film service abstract controoler
 */


/**
 * Class RediaFilmAbstractController.
 */
abstract class RediaFilmAbstractController
{
  protected $client;
  protected $logger;

  /**
   * RediaFilmAbstractController constructor.
   *
   * @param RediaFilmRequest $client
   *   The service endpoint for digital article service.
   */
  public function __construct(RediaFilmRequest $client) {
    $this->client = $client;
    $this->logger = $client->getLogger();
  }

 /**
   * Check the result element in the response from the film service.
   * 
   * @param bool $response
   *   If the result element is set.
   */
  public function hasResult(array $response) {
    if (isset($response['result']) &&  isset($response['result']['result']) && $response['result']['result']) {
      return TRUE;
    } else {
      return false;
    }
  }

 /**
   * Get the data element from the response from the film service.
   * 
   * @param array $response
   *   The data element of the film service respons.
   */
  public function getData(array $response) {
    if (isset($response['result']) &&  isset($response['result']['data'])) {
      return $response['result']['data'];
    } else {
      return null;
    }
  }
}