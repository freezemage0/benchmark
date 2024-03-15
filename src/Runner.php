<?php

namespace Freezemage\Benchmark;

use Closure;
use Exception;

final class Runner {
    private int $iterations = 5;
    private array $parameters = [];
    private Closure $dataProvider;

    public function iterations(int $iterations): Runner {
        $this->iterations = $iterations;

        return $this;
    }

    public function dataProvider(callable $dataProvider): Runner {
        $this->dataProvider = $dataProvider(...);

        return $this;
    }

    public function parameters(array $parameters): Runner {
        $this->parameters = $parameters;

        return $this;
    }

    public function evaluate(callable $method): Result {
        $runs = [];
        for ($i = 0; $i < $this->iterations; $i += 1) {
            $parameters = isset($this->dataProvider) ? ($this->dataProvider)() : $this->parameters;

            $start = hrtime(true);
            $method($parameters);
            $end = hrtime(true);

            $runs[] = $end - $start;
        }

        return new Result($runs, array_sum($runs) / count($runs));
    }
}