<?php
echo elgg_view('input/date');

echo elgg_view('input/dropzone');

// if using third-party input views, make sure they initialize their scripts inline
// require(['third-party-input']);
// and not using elgg_require_js(), because currently those are not intialized on
// ajax requests