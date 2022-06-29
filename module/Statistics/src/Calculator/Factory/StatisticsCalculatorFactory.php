<?php

namespace Statistics\Calculator\Factory;

use Statistics\Calculator\CalculatorComposite;
use Statistics\Calculator\CalculatorInterface;
use Statistics\Calculator\StatisticCollector\AverageNumberPerUser;
use Statistics\Calculator\StatisticCollector\AveragePostLength;
use Statistics\Calculator\StatisticCollector\MaxPostLength;
use Statistics\Calculator\StatisticCollector\TotalPostsPerWeek;
use Statistics\Enum\StatsEnum;

final class StatisticsCalculatorFactory
{
    private const CALCULATOR_CLASS_MAP = [
        StatsEnum::AVERAGE_POST_LENGTH          => AveragePostLength::class,
        StatsEnum::MAX_POST_LENGTH              => MaxPostLength::class,
        StatsEnum::TOTAL_POSTS_PER_WEEK         => TotalPostsPerWeek::class,
        StatsEnum::AVERAGE_POST_NUMBER_PER_USER => AverageNumberPerUser::class,
    ];

    public static function create(): CalculatorInterface
    {
        $calculator = new CalculatorComposite();

        foreach (self::CALCULATOR_CLASS_MAP as $className) {
            $calculator->addChild(new $className());
        }

        return $calculator;
    }
}
