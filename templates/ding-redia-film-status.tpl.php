<?php

/**
 * @file
 * Template for status element in loan status.
 *
 */
?>
<div class="redia-film-status">
    <div class="redia-film-pie">
        <div class="redia-film-status-title"><?php print t('Film') ?>"</div>
        <div class="redia-film-status-chart" data-percent="<?php print $user->loanPercentage ?>" >
            <?php print $user->currentLoanCount ?>
        </div>
        <div class="redia-film-status-max-loans"><?php print t('Out of %currentloancount', ['%currentloancount' => $user->maxNumberOfLoans]) ?></div>
    </div>
    <?php if (!$user->isEligible) : ?>
        <div class="redia-film-next-loan">
            <div class="redia-film-next-text">
                <?php print t('You can lend a movie again in..') ?>
            </div>
            <div class="redia-film-next-dates-container">
                <div class="redia-film-next-diff">
                    <div class="redia-film-next-diff-element">
                        <div class="redia-film-next-diff-date">
                            <?php print sprintf('%02d', $user->nextLoanDays) ?>
                        </div>
                        <div class="redia-film-next-diff-label">
                            <?php print t('Days') ?>
                        </div>
                    </div>
                    <div class="redia-film-next-diff-element">
                        <div class="redia-film-next-diff-date">
                            <?php print sprintf('%02d', $user->nextLoanHours) ?>
                        </div>
                        <div class="redia-film-next-diff-label">
                            <?php print t('Hours') ?>
                        </div>
                    </div>
                    <div class="redia-film-next-diff-element">
                        <div class="redia-film-next-diff-date">
                            <?php print sprintf('%02d', $user->nextLoanMinutes) ?>
                        </div>
                        <div class="redia-film-next-diff-label">
                            <?php print t('Min') ?>
                        </div>
                    </div>
                </div>
                <div class="redia-film-next-date">
                   <?php print format_date($user->nextLoanDateRaw, 'long')?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>