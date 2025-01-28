<?php

require_once __DIR__ . '/randomly_chosen_number.php';

function createInstant(int $instant)
{
    $scaled_instant = $instant * RANDOMLY_CHOSEN_NUMBER;
    return (new DateTimeImmutable("2021-01-01 00:00:00"))->modify("$scaled_instant seconds");
}