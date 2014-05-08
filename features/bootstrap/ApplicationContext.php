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

        file_put_contents($responseFile, $this->toJson($table->getHash()));
    }

    /**
     * @param array $content
     *
     * @return string
     */
    private function toJson(array $content)
    {
        $content = $this->fixContent($content);

        return json_encode($content);
    }

    /**
     * @param mixed $content
     *
     * @return mixed
     */
    private function fixContent($content)
    {
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->fixContent($value);
            }
        } else if (preg_match('/^[0-9]+$/', $content)) {
            $content = (int) $content;
        }

        return $content;
    }
}
