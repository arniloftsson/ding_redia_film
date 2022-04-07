<?php

/**
 * @file
 * Film service Object.
 */


/**
 * Class RediaFilmObject.
 */
abstract class RediaFilmAbstractObject
{
  protected $client;

  /**
   * RediaFilmObject constructor.
   *
   * @param RediaFilmRequest $client
   *   The service endpoint for digital article service.
   */
  public function __construct(RediaFilmRequest $client) {
    $this->client = $client;
  }

 /**
   * Check the result element in the response from the film service.
   * 
   * @param array $response
   *   The repsonse from the film service.
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
   *   The repsonse from the film service.
   */
  public function getData(array $response) {
    if (isset($response['result']) &&  isset($response['result']['data'])) {
      return $response['result']['data'];
    } else {
      return null;
    }
  }
}