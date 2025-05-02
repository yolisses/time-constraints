<?php

namespace Yolisses\TimeConstraints;

use Yolisses\TimeConstraints\TimeConstraint;
use Yolisses\TimeConstraints\Interval\TimeIntervalOperations;

/**
 * Apply a logical AND between time constraints.
 */
class AndTimeConstraint extends TimeConstraint
{
    /**
     * @param array<TimeConstraint> $time_constraints
     */
    public function __construct(public array $time_constraints)
    {
    }

    public function getIntervals(\DateTimeImmutable $start_instant, \DateTimeImmutable $end_instant): array
    {
        if (empty($this->time_constraints)) {
            return [];
        }

        $intervals = $this->time_constraints[0]->getIntervals($start_instant, $end_instant);

        foreach ($this->time_constraints as $time_constraint) {
            $intervals = TimeIntervalOperations::intersection(
                $intervals,
                $time_constraint->getIntervals($start_instant, $end_instant)
            );
        }

        return $intervals;
    }
}