<?php
/**
 * @file
 * Data object for the film service object data.
 */


/**
 * Class RediaFilmObject.
 */
class RediaFilmObject
{
  public $id;
  public $cover;
  public $creators;
  public $offset;
  public $tingObjectId;
  public $title;

  public $expireDate;
  public $loanDate;
  public $progress;
}

/**
 * Class RediaFilmWatchObject.
 */
class RediaFilmWatchObject
{
  public $bookmarkId;
  public $hasTrailer = false;
  public $id;
  public $info;
  public $offset;
  public $title;
  public $token;
  public $trailers;
}