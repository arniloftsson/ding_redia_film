<?php

/**
 * @file
 * Film service Object.
 */


/**
 * Class RediaFilmObject.
 */
class RediaFilmObject
{
  private $client;
  private $response_object;
  private $identifier;


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
   * Creates a loan at the service. The user must be logged in.
   * 
   * @param RediaFilmUser $user
   *   The film service user.
   */
  public function createLoan(RediaFilmUser $user) {
    $response = $this->client->createLoan($this->identifier, $user);
    file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
  }

    /**
    * Gets at film object from the service.
    * 
    * @param string $identifier
    *   The identifier off the object.
    * 
    * @return array $session //TODO
    *   The session id from the service or null if there is a error.
    */
    public function getObject(string $identifier) {
      $response = $this->client->getObject($identifier);
      file_put_contents("/var/www/drupalvm/drupal/web/debug/redia2.txt", print_r($response , TRUE), FILE_APPEND);
    }

}