<?php

// app/Helpers/Helpers.php

if (!function_exists('formatToRupiah')) {
  function formatToRupiah($number)
  {
    return 'Rp ' . number_format($number, 0, ',', '.');
  }
}
