<?php

/**
 * @file
 * A loan status item.
 * 
 */
?>

<div class="redia-film-trailer">
  <!-- <iframe id="redia-film-trailer-iframe"
   title="Movie Trailer" 
   width="1024"
   height="436"
   src="http://link.theplatform.eu/s/jGxigC/media/mBVxOTNEkFyk">
  </iframe> -->
  <video controls autoplay="true">
  <?php foreach($trailers as $trailer): ?>
    <source src="<?php print $trailer['source'] ?>" 
      type="<?php print $trailer['format']?>">
    <?php endforeach; ?>

    Sorry, your browser doesn't support embedded videos.
</video>
</div>