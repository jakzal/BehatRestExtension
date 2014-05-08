Feature: Application gets a list of events
  In order to find events
  I need to retrieve a list of events
  As an Application

  Scenario: Retrieving a list of events
    Given the system knows about the following events:
      | id | name                     | location |
      | 1  | SymfonyCon 2013          | Warsaw   |
      | 2  | SymfonyDay Portugal 2014 | Lisbon   |
      | 3  | SymfonyLive London 2014  | London   |
    When the client requests GET "/events"
    Then the response should be a 200 with json:
    """
    [
      {
        "id": @integer@,
        "name": "SymfonyCon 2013",
        "location": "Warsaw"
      },
      {
        "id": 2,
        "name": "SymfonyDay Portugal 2014",
        "location": "Lisbon"
      },
      {
        "id": 3,
        "name": "SymfonyLive London 2014",
        "location": "London"
      }
    ]
    """