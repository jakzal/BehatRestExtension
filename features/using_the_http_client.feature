Feature: Using the http client
  As a Developer
  I need an access to an http client
  In order to make requests to the REST API I'm testing

  Scenario: Making requests
    Given a behat configuration:
    """
    default:
      suites:
        default:
          contexts: [PostcodeSearchContext]
      extensions:
        Zalas\Behat\RestExtension: ~
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

            $this->lastResponse = $this->httpClient->sendRequest($this->messageFactory->createRequest('GET', $uri));
        }

        /**
         * @Then I should see its location
         */
        public function iShouldSeeItsLocation()
        {
            PHPUnit::assertInstanceOf(ResponseInterface::class, $this->lastResponse);
            PHPUnit::assertSame(200, $this->lastResponse->getStatusCode(), 'Got a successful response');

            $json = json_decode($this->lastResponse->getBody(), true);
            PHPUnit::assertInternalType('array', $json, 'Response contains a query result');
            PHPUnit::arrayHasKey('result', $json, 'Result found in the response');
            PHPUnit::assertArrayHasKey('latitude', $json['result'], 'Latitude found in the response');
            PHPUnit::assertArrayHasKey('longitude', $json['result'], 'Longitude found in the response');
            PHPUnit::assertInternalType('double', $json['result']['latitude'], 'Latitude is a double');
            PHPUnit::assertInternalType('double', $json['result']['longitude'], 'Longitude is a double');
        }

        /**
         * @Then I should be informed the postcode was not found
         */
        public function iShouldBeInformedThePostcodeWasNotFound()
        {
            PHPUnit::assertSame(404, $this->lastResponse->getStatusCode(), '404 Not Found');
        }
    }
    """
    And the feature file "features/postcode_search.feature" contains:
    """
    Feature: Postcode search
      As a Deliverer
      I want to find a postcode
      In order to deliver package on time

      Scenario: Searching for a valid postcode
        When I search for "SE50NP"
        Then I should see its location

      Scenario: Searching for an incomplete postcode
        When I search for "SE5"
        Then I should be informed the postcode was not found
    """
    When I run behat
    Then it should pass

  Scenario: Configuring a client
    Given a behat configuration:
    """
    default:
      suites:
        default:
          contexts: [PostcodeSearchContext]
      extensions:
        Zalas\Behat\RestExtension:
          guzzle:
            config:
              base_uri: http://localhost:8000/
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
            $uri = sprintf('/postcodes/%s', $postcode);

            $this->lastResponse = $this->httpClient->sendRequest($this->messageFactory->createRequest('GET', $uri));
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

      Scenario: Searching for a valid postcode
        When I search for "SE50NP"
        Then I should see its location
    """
    When I run behat
    Then it should pass

  Scenario: Changing the http client adapter
    Given a behat configuration:
    """
    default:
      extensions:
        Zalas\Behat\RestExtension:
          buzz: ~
    """
    And a feature using an http client
    When I run behat
    Then it should pass
    And the "buzz" http client should have been used
