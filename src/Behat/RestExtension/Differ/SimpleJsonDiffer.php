<?php

namespace Behat\RestExtension\Differ;

class SimpleJsonDiffer implements Differ
{
    /**
     * {@inheritdoc}
     */
    public function diff($expected, $actual)
    {
        $expectedJson = json_decode($expected);
        $actualJson = json_decode($actual);

        if ($expectedJson != $actualJson) {
            return sprintf('Expected to get "%s" but received: "%s"', json_encode($expectedJson, JSON_PRETTY_PRINT), json_encode($actualJson, JSON_PRETTY_PRINT));
        }
    }
}
