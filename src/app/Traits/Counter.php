<?php

namespace App\Traits;

trait Counter
{
    private static array $counter = [];

    public function getNextCounter(string $name, int $start = 1001): int
    {
        if (!isset(self::$counter[$name])) {
            self::$counter[$name] = $start;
        } else {
            self::$counter[$name]++;
        }

        return self::$counter[$name];
    }
}
