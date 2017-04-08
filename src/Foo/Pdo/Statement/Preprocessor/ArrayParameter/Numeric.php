<?php

declare(strict_types=1);

namespace Foo\Pdo\Statement\Preprocessor\ArrayParameter;

class Numeric
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
            if (!is_numeric($key)) {
                continue;
            }

            $partials[$key] = $this->getInQueryPartial($parameters, $key);
        }

        if (!$partials) {
            return false;
        }

        $query      = $this->replaceQueryPartials($query, $partials);
        $parameters = $this->injectParameterPartials($parameters, $partials);

        return true;
    }

    /**
     * @param array $parameters
     * @param int   $key
     *
     * @return array
     */
    private function getInQueryPartial(array $parameters, int $key)
    {
        $values = $parameters[$key];

        $queryParts = array_fill(0, count($values), '?');

        return $queryParts;
    }

    /**
     * @param string $query
     * @param array  $partials
     *
     * @return string
     */
    private function replaceQueryPartials(string $query, array $partials)
    {
        $queryParts = explode('?', $query);
        $lastIndex  = count($queryParts) - 1;
        $query      = '';
        foreach ($queryParts as $index => $queryPart) {
            if ($index == $lastIndex) {
                $query .= $queryPart;
            } elseif (array_key_exists($index, $partials)) {
                $query .= $queryPart . implode(', ', $partials[$index]);
            } else {
                $query .= $queryPart . '?';
            }
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
        $newParameters = [];
        foreach ($parameters as $index => $value) {
            if (!array_key_exists($index, $partials)) {
                $newParameters[] = $value;

                continue;
            }

            foreach ($parameters[$index] as $inArrayValue) {
                $newParameters[] = $inArrayValue;
            }
        }

        return $newParameters;
    }
}
