<?php

if (!function_exists('is_active')) {
  // return boolean: true jika segmen-1 cocok
  function is_active(string $segment): bool
  {
    $uri = service('uri');
    return $uri->getSegment(1) === trim($segment, '/');
  }
}
