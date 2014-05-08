<?php

namespace Behat\RestExtension\Differ;

use Coduo\PHPMatcher\Matcher;

class CoduoDiffer implements Differ
{
    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @param Matcher $matcher
     */
    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * {@inheritdoc}
     */
    public function diff($actual, $expected)
    {
        if (!$this->matcher->match($actual, $expected)) {
            return $this->matcher->getError();
        }
    }
}
