<?php

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;

abstract class AbstractCalculator implements StatisticCollectorInterface
{
    protected const UNITS = null;

    protected ParamsTo $parameters;

    public function setParameters(ParamsTo $params): self
    {
        $this->parameters = $params;

        return $this;
    }

    public function accumulateData(SocialPostTo $postTo): void
    {
        if (false === $this->checkPost($postTo)) {
            return;
        }

        $this->doAccumulate($postTo);
    }

    public function calculate(): StatisticsTo
    {
        return $this->doCalculate()
                    ->setName($this->parameters->getStatName())
                    ->setUnits(static::UNITS);
    }

    protected function checkPost(SocialPostTo $postTo): bool
    {
        if (null !== $this->parameters->getStartDate()
            && $this->parameters->getStartDate() > $postTo->getDate()
        ) {
            return false;
        }

        if (null !== $this->parameters->getEndDate()
            && $this->parameters->getEndDate() < $postTo->getDate()
        ) {
            return false;
        }

        return true;
    }

    abstract protected function doAccumulate(SocialPostTo $postTo): void;

    abstract protected function doCalculate(): StatisticsTo;
}
