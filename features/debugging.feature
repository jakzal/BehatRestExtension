@wip
Feature: Debugging
  As a Developer
  I need to review requests and their corresponding responses
  In order to find the reason a scenario failed

  Scenario: Reviewing requests and responses
    Given a behat configuration:
    """
    default:
      suites:
        default:
          contexts: [PostcodeSearchContext]
      extensions:
        Zalas\Behat\RestExtension:
          debug: true
    """
    And the context file "features/bootstrap/PostcodeSearchContext.php" contains:
    """
    <?php

    use Behat\Behat\Context\Context;
    use PHPUnit_Framework_Assert as PHPUnit;
    use Psr\Http\Message\ResponseInterface;
    use Http\Client\HttpClient;
    use Http\Message\MessageFactory;

    class PostcodeSearchContext implements Context
    {
        private $httpClient;
        private $messageFactory;
        private $lastResponse;

        public function __construct(HttpClient $httpClient, MessageFactory $messageFactory)
        {
            $this->httpClient = $httpClient;
            $this->messageFactory = $messageFactory;
        }

        /**
         * @When I search for :postcode
         */
        public function iSearchFor($postcode)
        {
            $uri = sprintf('http://localhost:8000/postcodes/%s', $postcode);

            $this->lastResponse = $this->httpClient->sendRequest(
                $this->messageFactory->createRequest('POST', $uri, ['Accept' => 'application/json'], '{"foo":"bar"}')
            );
        }

        /**
         * @Then I should see its location
         */
        public function iShouldSeeItsLocation()
        {
            PHPUnit::assertInstanceOf(ResponseInterface::class, $this->lastResponse);
            PHPUnit::assertSame(200, $this->lastResponse->getStatusCode(), 'Got a successful response');
        }
    }
    """
    And the feature file "features/postcode_search.feature" contains:
    """
    Feature: Postcode search
      As a Deliverer
      I want to find a postcode
      In order to deliver package on time

      Scenario: Searching for an incomplete postcode
        When I search for "SE5"
        Then I should see its location
    """
    When I run behat
    Then it should fail with:
    """
    >>>
    GET http://localhost:8000/postcodes/SE5
    Accept: application/json

    {
        "foo":"bar"
    }
    <<<
    HTTP/1.1 404 Not Found
    Connection: close
    Content-type: application/json
    Host: localhost:8000
    X-Powered-By: foo

    {
        "status":404,
        "error":"Postcode not found"
    }
    """

