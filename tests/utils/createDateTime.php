<?php

function createDateTime(int $second): DateTimeImmutable
{
    return new DateTimeImmutable("2023-01-01 00:00:$second");
}