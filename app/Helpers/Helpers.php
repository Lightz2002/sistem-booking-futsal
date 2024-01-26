<?php


use Carbon\Carbon;

if (!function_exists('todayDate')) {
  function todayDate()
  {
    return Carbon::now()->format('Y-m-d');
  }
}

if (!function_exists('formatToRupiah')) {
  function formatToRupiah($number)
  {
    return 'Rp ' . number_format($number, 0, ',', '.');
  }
}

if (!function_exists('addOneDayIfPastMidnight')) {
  function addOneDayIfPastMidnight($time)
  {
    $dateTime = Carbon::parse($time);

    // Check if the time is between midnight and 5 AM
    if ($dateTime->hour >= 0 && $dateTime->hour < 5) {
      // Add one day
      $dateTime->addDay();
    }

    return $dateTime->format("Y-m-d H:i:s");
  }
}

if (!function_exists('formatToDateTime')) {
  function formatToDateTime($time)
  {
    return Carbon::createFromTimeString($time)->format("Y-m-d $time");
  }
}


if (!function_exists('sqlRawAddOneDayIfPastMidnight')) {
  function sqlRawAddOneDayIfPastMidnight($column)
  {
    return "CONCAT(
      CASE WHEN HOUR(`$column`) BETWEEN 0 AND 5
          THEN DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY)
          ELSE DATE_FORMAT(NOW(), '%Y-%m-%d')
      END , ' ', `$column`)";
  }
}

if (!function_exists('sqlStartTimePastMidnight')) {
  function sqlStartTimePastMidnight()
  {
    return sqlRawAddOneDayIfPastMidnight('start_time');
  }
}


if (!function_exists('sqlEndTimePastMidnight')) {
  function sqlEndTimePastMidnight()
  {
    return sqlRawAddOneDayIfPastMidnight('end_time');
  }
}
