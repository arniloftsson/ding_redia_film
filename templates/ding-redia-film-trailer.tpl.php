<?php

/**
 * @file
 * A film trailer.
 * 
 */
?>

<div class="redia-film-trailer">
  <video id="redia-film-trailer-video" controls autoplay="true">
  <?php foreach($trailers as $trailer): ?>
    <source src="<?php print $trailer['source'] ?>" 
      type="<?php print $trailer['format']?>">
    <?php endforeach; ?>

    Sorry, your browser doesn't support embedded videos.
</video>
</div>