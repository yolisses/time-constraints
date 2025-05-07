<?php

namespace Yolisses\TimeConstraints;

use League\Period\Sequence;

class SequencesIntersection
{
    /**
     * Compute the intersection of n sequences.
     *
     * @param Sequence[] $sequences Array of Sequence objects
     * @return Sequence A new Sequence containing the intersecting periods
     */
    public static function intersection(array $sequences): Sequence
    {
        // Handle edge cases
        if (empty($sequences)) {
            return new Sequence();
        }
        if (count($sequences) === 1) {
            return clone $sequences[0]; // Return a copy of the single sequence
        }

        // Initialize result with periods from the first sequence
        $result = clone $sequences[0];

        // Iterate through the remaining sequences
        foreach (array_slice($sequences, 1) as $sequence) {
            $intersections = [];

            // Compare each period in the current result with each period in the new sequence
            foreach ($result as $period1) {
                foreach ($sequence as $period2) {
                    if ($period1->overlaps($period2)) {
                        $intersections[] = $period1->intersect($period2);
                    }
                }
            }

            // Update result with the intersections found
            $result = new Sequence(...$intersections);

            // Early exit if no intersections remain
            if ($result->isEmpty()) {
                return new Sequence();
            }
        }

        return $result;
    }
}