Feature: Get user's information
    Scenario: Check security
        When I request "/api/users" without authentification
      Then Response status code should be 401
    Scenario: Get all users
        When I request "/api/users"
        Then I have a JSON response
        And I have an array of users
        Then Response status code should be 200
    Scenario Outline: Get specific user
        When I request "/api/users/<username>"
        Then I have a JSON response
        And I have a user object
        Then Response status code should be 200
        Examples:
            | username |
            | TestBehat |
