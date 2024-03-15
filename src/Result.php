<?php

namespace Freezemage\Benchmark;

use NumberFormatter;

final class Result {
    public function __construct(public readonly array $runs, public readonly float $average) {
    }

    public function prettyPrint(string $name = 'Default run', ?NumberFormatter $numberFormatter = null): string {
        return (new ResultSet())->append($name, $this)->prettyPrint($numberFormatter);
    }
}