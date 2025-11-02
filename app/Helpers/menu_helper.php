<?php

if (!function_exists('is_active')) {
  // return boolean: true jika segmen-1 cocok
  function is_active(string $segment): bool
  {
    $uri = service('uri');
    return $uri->getSegment(1) === trim($segment, '/');
  }
}

if (!function_exists('active_class')) {
  // biar enak: kembalikan nama class kalau aktif
  function active_class(string $segment, string $class = 'active'): string
  {
    return is_active($segment) ? $class : '';
  }
}
