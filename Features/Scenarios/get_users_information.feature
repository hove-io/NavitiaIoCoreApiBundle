Feature: Get user's information
    Background:
        Given The following people exist:
        | username   | first_name | last_name  | password  | email                 | project_type  | company    | website               |
        | jmaulny    | Julien     | Maulny     | tomate    | jmaulny@canaltp.fr    | profesional   | myCompanyA | http://mycompanya.com |
        | lroche     | Ludovic    | Roche      | concombre | lroche@canaltp.fr     | personal      | myCompanyB | http://mycompanyb.com |
        | tnoury     | Thomas     | Noury      | 123456    | tnoury@canaltp.fr     | profesional   | myCompanyC | http://mycompanyc.com |
        | rabikhalil | Rémy       | Abi Khalil | aubergine | rabikhalil@canaltp.fr | personal      | myCompanyD | http://mycompanyd.com |
    Scenario: Check security
        When I request "/api/users" without authentification
        Then Response status code should be 401
    Scenario: Check Response status
        When I request "/api/users"
        Then Response status code should be 200
    Scenario: Check response type
        When I request "/api/users"
        Then I have a "application/json" response
    Scenario: Get all users
        When I request "/api/users"
        Then I should have 4 users
    Scenario Outline: Check status code for specific user
        When I request "/api/users/<username>"
        Then Response status code should be 200
        Examples:
            | username   |
            | lroche     |
            | tnoury     |
    Scenario Outline: Check response type for specific user
        When I request "/api/users/<username>"
        Then I have a "application/json" response
        Examples:
            | username   |
            | jmaulny    |
            | rabikhalil |
    Scenario Outline: Get specific user object
        When I request "/api/users/<username>"
        Then I have a user object
        Examples:
            | username   |
            | lroche     |
            | tnoury     |
            | rabikhalil |
