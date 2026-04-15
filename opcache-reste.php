 <?php
  if (function_exists('opcache_reset')) {
      opcache_reset();
      echo "OPcache vidé OK";
  } else {
      echo "OPcache non actif";
  }
