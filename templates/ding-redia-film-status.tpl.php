
<?php
/**
 * @file
 * The initial react code
 *
 *
 */
?>

<div class="material-item odd">
    <?php // @TODO: Hardcode 73%? ?>
    <div class="chart" data-percent="73" data-scale-color="#ffb400">73%</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

    <?php // @TODO: Not valid path ?>
    <script src="/path/to/jquery.easy-pie-chart.js"></script>
    <script>
      <?php // @TODO: Why the inline javascript ?>
      $(function() {
            $('.chart').easyPieChart({
                // your options goes here
            });
      });
  </script>
</div>

