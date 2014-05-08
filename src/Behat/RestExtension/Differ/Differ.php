<?php

namespace Behat\RestExtension\Differ;

interface Differ
{
    /**
     * @param string $actual
     * @param string $expected
     *
     * @return string|null A difference or null
     */
    public function diff($actual, $expected);
}