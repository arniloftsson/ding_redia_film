
<?php
/**
 * @file
 * The initial react code
 * 
 *
 */
?>

<div id="root"></div>
  <script
    crossorigin
    src="https://unpkg.com/react@17/umd/react.production.min.js"
    ></script>
    <script
    crossorigin
      src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"
      ></script>
      <script
    type="module"
      src="https://film.libry.dk/libry-film-widget-app.umd.js"
      ></script>
      <script>
    window.addEventListener("DOMContentLoaded", (event) => {
    window.LibryFilm.init({ 
      containerId: "root",
      baseUrl: "/film/libry",
     <?php print $options; ?>, 
    });
  });
  </script>
