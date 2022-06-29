<?php

namespace Statistics\Builder;

use DateTime;
use Statistics\Dto\ParamsTo;

final class ParamsBuilder
{
    public static function buildParamsTo(DateTime $date): ParamsTo
    {
        $startDate = (clone $date)->modify('first day of this month');
        $endDate   = (clone $date)->modify('last day of this month');

        return (new ParamsTo())
            ->setStartDate($startDate)
            ->setEndDate($endDate);
    }
}
