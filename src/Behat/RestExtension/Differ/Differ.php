<?php

namespace Behat\RestExtension\Differ;

interface Differ
{
    /**
     * @param string $expected
     * @param string $actual
     *
     * @return string|null A difference or null
     */
    public function diff($expected, $actual);
}