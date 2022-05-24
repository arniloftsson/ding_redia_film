<?php

/**
 * @file
 * Film service Objects.
 */


/**
 * Class RediaFilmObject.
 */
class RediaFilmObjects extends RediaFilmAbstractObject
{
  private $response_object;
  private $identifier;

    /**
   * Creates a loan at the service. The user must be logged in.
   * 
   * @param RediaFilmUser $user
   *   The film service user.
   */
  public function createLoan(RediaFilmUser $user, RediaFilmObject $object) {
    $response = $this->client->createLoan($object->id, $user->getSessionid());
    
    //file_put_contents("/var/www/drupalvm/drupal/web/debug/create1.txt", print_r($response , TRUE), FILE_APPEND);
  }

  /**
   * Gets at film object from the service.
   * 
   * @param string $identifier
   *   The identifier off the object.
   * 
   * @return RediaFilmObject 
   *   The objects from the service.
   */
  public function hasTrailer($identifier) {
    $objects = $this->getObjects([$identifier]);
    $libry_object= reset($objects);
    if (isset($libry_object->trailers) && !empty($libry_object->trailers)) {
      return true;
    }
    return false;
  }

   /**
    * Gets at film object from the service.
    * 
    * @param string $identifier
    *   The identifier off the object.
    * 
    * @return RediaFilmObject 
    *   The objects from the service.
    */
  public function getObject($identifier) {
    $objects = $this->getObjects([$identifier]);
    return reset($objects);
  }

   /**
    * Get film objects from the service.
    * 
    * @param array $identifiers
    *   The identifiers off the object.
    * 
    * @return array $libry_objects
    *   The objects from the service.
    */
  public function getObjects(array $identifiers) {
    $libry_objects = [];
    $response = $this->client->getObject($identifiers);
    if ($this->hasResult($response)) {
      $data = $this->getData($response);
      foreach ($data as $key => $object) {
        file_put_contents("/var/www/drupalvm/drupal/web/debug/object2.txt", print_r($object, TRUE), FILE_APPEND);
        if (isset($object['data'])) {
          $item_data = $object['data'];
          $libry_object = new RediaFilmObject();
          $libry_object->id = isset($item_data['identifier']) ? $item_data['identifier'] : null;
          $libry_object->tingObjectId = isset($item_data['faust']) ? $item_data['faust'] : null;
          $libry_object->title = isset($item_data['title']) ? $item_data['title'] : null;
          $libry_object->creators = isset($item_data['creators']) ? $item_data['creators'] : null;
          $libry_object->info = $item_data;
          if (isset($item_data['media']) && isset($item_data['media']['trailers'])) {
            $libry_object->trailers = $item_data['media']['trailers'];
          }

          $libry_objects[$key] = $libry_object;
        }      
      }
    } else {
      //$this->logger->logError('Couldnt get the objects from the film service: %response', ['%response' => print_r($response, TRUE)]);
    }
    //file_put_contents("/var/www/drupalvm/drupal/web/debug/object4.txt", print_r($response , TRUE), FILE_APPEND);
    return $libry_objects;
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
  public function getToken(RediaFilmUser $user) {
    $token = null;
    $response = $this->client->getToken($user->getSessionid());
    //file_put_contents("/var/www/drupalvm/drupal/web/debug/token1.txt", print_r($response , TRUE), FILE_APPEND);
    if ($this->hasResult($response)) {
      $data = $this->getData($response);
      if (isset($data['token'])) {
        return $data['token'];
      }
    }
    return $token;
  }
}
