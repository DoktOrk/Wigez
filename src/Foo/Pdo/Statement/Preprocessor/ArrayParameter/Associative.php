<?php

declare(strict_types=1);

namespace Foo\Pdo\Statement\Preprocessor\ArrayParameter;

class Associative
{
    /**
     * @param string $query
     * @param array  $parameters
     * @param array  $whereInParameters
     *
     * @return bool
     */
    public function process(string &$query, array &$parameters, array $whereInParameters)
    {
        $partials = [];
        foreach ($whereInParameters as $key => $values) {
            if (is_numeric($key)) {
                continue;
            }

            $partials[$key] = $this->getQueryPartial($parameters, $key);
        }

        if (!$partials) {
            return false;
        }

        $query      = $this->replaceQueryPartials($query, $partials);
        $parameters = $this->injectParameterPartials($parameters, $partials);

        return true;
    }

    /**
     * @param array  $parameters
     * @param string $key
     *
     * @return array
     */
    private function getQueryPartial(array $parameters, string $key)
    {
        $values = $parameters[$key];

        $inQueryParts = array_fill(0, count($values), "${key}__expanded");

        array_walk(
            $inQueryParts,
            function (&$value, $key) {
                $value = $value . $key;
            }
        );


        return $inQueryParts;
    }

    /**
     * @param string $query
     * @param array  $partials
     *
     * @return string
     */
    private function replaceQueryPartials(string $query, array $partials)
    {
        foreach ($partials as $key => $inQueryParts) {
            $inQueryParts = array_map(
                function ($value) {
                    return ":$value";
                },
                $inQueryParts
            );
            $inQuery      = implode(', ', $inQueryParts);

            $query = str_replace(":$key", $inQuery, $query);
        }

        return $query;
    }

    /**
     * @param array $parameters
     * @param array $partials
     *
     * @return array
     */
    private function injectParameterPartials(array $parameters, array $partials)
    {
        foreach ($partials as $origKey => $newKeys) {
            $replacement = array_combine($newKeys, $parameters[$origKey]);

            $parameters = $this->arraySpliceAssoc(
                $parameters,
                $origKey,
                1,
                $replacement
            );
        }

        return $parameters;
    }

    /**
     * @param array  $input
     * @param string $key
     * @param int    $length
     * @param array  $replacement
     *
     * @return array
     */
    private function arraySpliceAssoc(array &$input, string $key, int $length, array $replacement)
    {
        $keyIndices = array_flip(array_keys($input));
        $offset     = $keyIndices[$key];

        $beginning = array_slice($input, 0, $offset, true);
        $end       = array_slice($input, $offset + $length, null, true);

        $input = $beginning + $replacement + $end;

        return $input;
    }
}
