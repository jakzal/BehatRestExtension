@wip
Feature: Configuring plugins
  As a Developer
  I need to configure http client plugins
  In order to do less work when sending requests

  Scenario: Configuring request headers
    Given a behat configuration:
    """
    default:
      extensions:
        Zalas\Behat\RestExtension:
          plugins:
            header_defaults:
                User-Agent: Foo Agent
            header_set:
                Accept: application/json
            header_remove: [X-Foo]
            header_append:
                Forwarded: for=test
    """
    When I make an http request in a scenario
    Then the "User-Agent" header should be set to "Foo Agent"
    And the "Accept" header should be set to "application/json"
    But the "X-Foo" header should be removed
    And the "for=test" value should be appended to the "Forwarded" header

