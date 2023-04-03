<?php

/**
 * @file
 * Controller for objects from the film service.
 */


/**
 * Class RediaFilmObjectsController .
 */
class RediaFilmObjectsController extends RediaFilmAbstractController
{
  /**
   * Creates a loan at the service. The user must be logged in.
   *
   * @param RediaFilmUserController $user
   *   The film service user.
   * @param RediaFilmObject $object
   *   The film to loan.
   * @return bool $success
   *   The loan was created or not.
   */
  public function createLoan(RediaFilmUserController $user, RediaFilmObject $object) {
    $response = $this->client->createLoan($object->id, $user->getSessionid());
    if ($this->hasResult($response)) {
      $data = $this->getData($response);
      if (isset($data) && isset($data['success']) && $data['success']) {
        return true;
      }
    }
    $this->logger->logError('Could not create the loan at the film service: %response', ['%response' => print_r($response, TRUE)]);
    return false;
  }

  /**
   * Checks if a film has trailer.
   *
   * @param string $identifier
   *   The identifier off the object.
   */
  public function hasTrailer($identifier) {
    $libry_object = $this->getObject($identifier);

    return isset($libry_object->trailers) && !empty($libry_object->trailers);
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
        if (isset($object['data'])) {
          $libry_objects[$key] = $this->createObject($object['data']);
        }
      }
    }
    else {
      $this->logger->logError('Could not get the objects from the film service: %response', ['%response' => print_r($response, TRUE)]);
    }

    return $libry_objects;
  }

   /**
    * Creates a RediaFilmObject.
    *
    * @param array $item_data
    *   The idata for the object.
    *
    * @return RediaFilmObject
    *   The objects from the service.
    */
    public function createObject(array $item_data) {
      $libry_object = new RediaFilmObject();
      $libry_object->id = isset($item_data['identifier']) ? $item_data['identifier'] : null;
      $libry_object->tingObjectId = isset($item_data['faust']) ? $item_data['faust'] : null;
      $libry_object->title = isset($item_data['originalTitle']) ? $item_data['originalTitle'] : null;
      $libry_object->creators = isset($item_data['creators']) ? $item_data['creators'] : null;
      $libry_object->info = $item_data;

      if (isset($item_data['media']) && isset($item_data['media']['trailers'])) {
        $libry_object->trailers = $item_data['media']['trailers'];
      }

      return $libry_object;
    }

  /**
   * Gets the token from the service in order to watch the film. The user must be logged in.
   *
   * @param RediaFilmUserController $user
   *   The RediaFilmUserController to use.
   *
   * @return string|null $token
   *   The session id from the service or null if there is a error.
   */
  public function getToken(RediaFilmUserController $user) {
    $token = null;
    $response = $this->client->getToken($user->getSessionid());
    if ($this->hasResult($response)) {
      $data = $this->getData($response);
      if (isset($data['token'])) {
        $token = $data['token'];
      }
    }

    return $token;
  }

  /**
   * Is there a bookmark for the film.
   *
   * @param string $identifier
   *   The identifier for the film.
   *
   * @return int $offset
   *   A offset for the film else 0.
   */
  public function getBookmark(RediaFilmUserController $user, RediaFilmObject $object) {
    $response = $this->client->getBookmarks($user->getSessionid());
    //file_put_contents("/var/www/drupalvm/drupal/web/debug/film1.txt", print_r($response, TRUE), FILE_APPEND);
    if ($this->hasResult($response)) {
      $data = $this->getData($response);
      foreach ($data as $bookmark) {
        if ($bookmark['identifier'] == $object->id) {
          return $bookmark['timestamp'];
        }
      }
    }

    return 0;
  }

    /**
   * Set a bookmark for the film.
   *
   * @param string $identifier
   *   The identifier for the film.
   *
   * @param int $offset
   *   A offset for the film else 0.
   */
  public function setBookmark(RediaFilmUserController $user, $bookmarkId, $offset) {
    $bookmarks =  $this->client->setBookmark($bookmarkId, $offset, $user->getSessionid());
    //file_put_contents("/var/www/drupalvm/drupal/web/debug/film5.txt", print_r($bookmarks, TRUE), FILE_APPEND);
  }
}
