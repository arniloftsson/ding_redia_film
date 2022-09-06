<?php

/**
 * @file
 *
 *
 */
?>
<div class="redia-film-status">
    <div class="redia-film-status-title">Film</div>
    <div class="redia-film-status-chart" data-percent="<?php print $user->loanPercentage?>" data-scale-color="#ffb400">
        <?php print $user->currentLoanCount?>
    </div>
    <div class="redia-film-status-max-loans"><?php print t('Out of %currentloancount', ['%currentloancount' => $user->maxNumberOfLoans]) ?></div>
</div>