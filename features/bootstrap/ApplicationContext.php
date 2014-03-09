<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;

class ApplicationContext implements SnippetAcceptingContext
{
    /**
     * @Given the system knows about the following events:
     */
    public function theSystemKnowsAboutTheFollowingEvents(TableNode $table)
    {
        $responseFile = sys_get_temp_dir().'/events';

        file_put_contents($responseFile, json_encode($table->getHash()));
    }
}
