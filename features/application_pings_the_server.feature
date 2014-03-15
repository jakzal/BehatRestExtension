Feature: Application pings the server
  In order to debug the server's health
  I need to ping the server
  As an Application

  Scenario: Debugging request content and headers
    When the client requests POST "/debug" with:
    """
    X-Debug: 1
    X-Debug-Token: abcdef

    [
      {
        "name": "SymfonyCon 2013",
        "location": "Warsaw"
      }
    ]
    """
    Then the response should be a 200 with json:
    """
    {
      "headers": {
        "X-Debug": "1",
        "X-Debug-Token": "abcdef"
      },
      "content": [
        {
          "name": "SymfonyCon 2013",
          "location": "Warsaw"
        }
      ]
    }
    """

  Scenario: Debugging request content
    When the client requests POST "/debug" with:
      """
      [
        {
          "name": "SymfonyCon 2013",
          "location": "Warsaw"
        }
      ]
      """
    Then the response should be a 200 with json:
    """
    {
      "headers": [],
      "content": [
        {
          "name": "SymfonyCon 2013",
          "location": "Warsaw"
        }
      ]
    }
    """
