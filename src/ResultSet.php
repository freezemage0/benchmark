<?php

namespace Freezemage\Benchmark;

use NumberFormatter;

class ResultSet {
    /** @var array<string, Result> */
    private array $results = [];

    public function append(string $name, Result $result): ResultSet {
        $this->results[$name] = $result;

        return $this;
    }

    public function prettyPrint(?NumberFormatter $numberFormatter = null, bool $onlyAverage = false): string {
        $numberFormatter ??= $this->createDefaultNumberFormatter();

        $output = [];
        $maxInfoLength = $maxValueLength = 0;

        foreach ($this->results as $name => $result) {
            $resultOutput = [
                    'Run name' => $name,
                    'Total iterations' => count($result->runs)
            ];

            if (!$onlyAverage) {
                foreach ($result->runs as $index => $run) {
                    $index += 1;
                    $resultOutput["Iteration #{$index}"] = "{$this->format($numberFormatter, $run)}";
                }
            }

            $resultOutput["Average"] = "{$this->format($numberFormatter, $result->average)}";

            foreach ($resultOutput as $key => $value) {
                $keyLength = strlen($key);
                if ($keyLength > $maxInfoLength) {
                    $maxInfoLength = $keyLength;
                }

                $valueLength = strlen($value);
                if ($valueLength > $maxValueLength) {
                    $maxValueLength = $valueLength;
                }
            }

            $output[] = $resultOutput;
        }

        $delimiter = str_repeat('=', $maxInfoLength + $maxValueLength + 7);

        $print = [$delimiter];
        foreach ($output as $subset) {
            foreach ($subset as $info => $value) {
                $info = $this->padRight($info, $maxInfoLength);
                $value = $this->padRight($value, $maxValueLength);
                $print[] = "| {$info} | {$value} |";
            }
            $print[] = $delimiter;
        }

        return implode("\n", $print);
    }

    private function format(NumberFormatter $numberFormatter, float $value): string {
        $value = (int) ($value / 1_000);
        return "{$numberFormatter->format($value)} microseconds";
    }

    public function createDefaultNumberFormatter(): NumberFormatter {
        return NumberFormatter::create(
                setlocale(LC_NUMERIC, ''),
                NumberFormatter::DEFAULT_STYLE
        );
    }

    private function padRight(string $string, int $length): string {
        $stringLength = mb_strlen($string);
        if ($stringLength >= $length) {
            return $string;
        }

        $padString = str_repeat(' ', $length - $stringLength);
        return $string . $padString;
    }
}