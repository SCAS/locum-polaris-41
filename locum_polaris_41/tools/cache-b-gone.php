<?php
// Put this file in the root directory of you Drupal installation.  Works with Drupal 6.x
// Have not tested with 7+

include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
drupal_flush_all_caches();