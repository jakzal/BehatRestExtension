<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit_Framework_Assert as PHPUnit;

class BehatRunnerContext implements Context
{
    /**
     * @var BehatRunner
     */
    private $behatRunner;

    /**
     * @BeforeScenario
     */
    public function bootstrap()
    {
        $this->behatRunner = new BehatRunner(sprintf('%s/%s/', sys_get_temp_dir(), uniqid('BehatRestExtension_')));
    }

    /**
     * @AfterScenario
     */
    public function removeWorkDir()
    {
        $this->behatRunner->removeWorkspace();
    }

    /**
     * @Given /^a behat configuration:$/
     */
    public function givenBehatConfiguration(PyStringNode $content)
    {
        $this->behatRunner->dumpFile('behat.yml', $content->getRaw());
    }

    /**
     * @Given /^(?:|an?|the )(?:|context |class |feature |.*)file "(?P<fileName>[^"]*)" contains:$/
     */
    public function aContextFileNamedWith($fileName, PyStringNode $content)
    {
        $this->behatRunner->dumpFile($fileName, $content->getRaw());
    }

    /**
     * @When /^I run behat$/
     */
    public function iRunBehat()
    {
        $this->behatRunner->run();
    }

    /**
     * @Then /^it should pass$/
     */
    public function itShouldPass()
    {
        try {
            PHPUnit::assertSame(0, $this->behatRunner->getExitCode(), 'Command terminated with an error');
            PHPUnit::assertStringNotMatchesFormat('PHP Warning:', $this->behatRunner->getFullOutput());
            PHPUnit::assertStringNotMatchesFormat('PHP Notice:', $this->behatRunner->getFullOutput());
        } catch (\Exception $e) {
            echo $this->behatRunner->getFullOutput();

            throw $e;
        }
    }

    /**
     * @Then /^it should fail$/
     */
    public function itShouldFail()
    {
        try {
            PHPUnit::assertNotSame(0, $this->behatRunner->getExitCode(), 'Command succeeded');
        } catch (\Exception $e) {
            echo $this->behatRunner->getFullOutput();

            throw $e;
        }
    }

    /**
     * @Then /^it should pass with:$/
     */
    public function itShouldPassWith(PyStringNode $expectedOutput)
    {
        $this->itShouldPass();

        foreach ($expectedOutput->getStrings() as $expectedLine) {
            PHPUnit::assertRegExp('/'.preg_quote($expectedLine, '/').'/sm', $this->behatRunner->getFullOutput());
        }
    }

    /**
     * @Then /^it should fail with:$/
     */
    public function itShouldFailWith(PyStringNode $expectedOutput)
    {
        $this->itShouldFail();

        foreach ($expectedOutput->getStrings() as $expectedLine) {
            PHPUnit::assertRegExp('/'.preg_quote($expectedLine, '/').'/sm', $this->behatRunner->getFullOutput());
        }
    }
}
