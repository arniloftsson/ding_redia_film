<?php

/**
 * @file
 * A loan status item.
 * 
 */
?>

<div class="material-item odd redia-film-item">
  <div class="redia-film-object-cover">
    <?php print $loan->cover ?>
  </div>

  <div class="redia-film-object-content">
    <div class="redia-film-all-object-type">
      <?php print $loan->type ?>
    </div>
    <div class="redia-film-object-title">
      <h2><?php print $loan->title ?></h2>
    </div>
    <div class="redia-film-object-creator">
      <?php print $loan->creators ?>
    </div>
    <div class="redia-film-loan-status">
      <div class="redia-film-loan-status-date">
        <div class="redia-film-loan-status-date-label">
          <?php print $loan->loanDateLabel ?>
        </div>
        <div class="redia-film-loan-status-date-text">
          <?php print $loan->loanDate ?>
        </div>
      </div>
      <div class="redia-film-loan-expire-date">
      <div class="redia-film-loan-expire-date-label">
          <?php print $loan->expireDateLabel ?>
        </div>
        <div class="redia-film-loan-expire-date-text">
          <?php print $loan->expireDate ?>
        </div>
      </div>
    </div>
  </div>

  <div class="redia-film-object-actions">
    <?php print $loan->watchButton ?>
  </div>
</div>