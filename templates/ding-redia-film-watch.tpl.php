<?php

/**
 * @file
 * A loan status item.
 * 
 */
?>

<div id="root">
      <libry-play-movie>
        <script>
          window.addEventListener(
            "LibryPlayMovieLoad",
            async (e) => {
              const media = await getMedia();
              /**
               * setProps takes an object of 
               * {
               *  mpxToken: string, // the mpx token
               *  info: LibraryObject, // The data from getObjects api
               *  goBack: function // function invoked when back button is clicked
               * }
               *
               */
              window.LibryPlayMovie.setProps({
                ...media,
                goBack: () =>  history.back(),
              });
            },
            false
          );
          async function getMedia() {
            /**
             * Example data.
            */
             return {
              mpxToken: "<?php print $object->token ?>",
              info: <?php print json_encode($object->info) ?>
            };
          }
        </script>
      </libry-play-movie>
    </div>
    <script
      src="https://unpkg.com/react@18/umd/react.production.min.js"
      crossorigin
    ></script>
    <script
      src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"
      crossorigin
    ></script>
    <script src="https://player-libry.web.app/libry-player.umd.js"></script>