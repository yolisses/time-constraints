<?php

require_once __DIR__ . '/randomly_chosen_number.php';

function createDuration(int $duration)
{
    $scaled_duration = $duration * RANDOMLY_CHOSEN_NUMBER;
    return $scaled_duration;
}