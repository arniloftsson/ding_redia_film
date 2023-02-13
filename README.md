# ding_redia_film

This module gives access to the Redia film service. It takes full control af a
page and renders its own react components.S

In order for this to work we use the search-blank variant off at site template.
This needs to be configured on install. Go to structure -> pages. Choose default
site template. Choose search-blank variant. There goto "udvalgsregler".

Direct url:

/admin/structure/pages/nojs/operation/site_template/handlers/site_template_search_blank/criteria

Click on the blue configuration icon to the right.

Add following under "Stier":

film/redia
film/redia/*
