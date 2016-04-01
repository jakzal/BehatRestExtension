@fixtures
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
