Feature: Users
  In order to use the users in Box.com
  As a developer
  I need to be able to perform operations on the users

  Scenario: Getting the current user's information
    When I get information about the current user
    Then I should receive information about a user

  Scenario: Creating an enterprise user
    When I create a user with the email "johndoe@example.com" and the name "John Doe"
    And I get information about the user
    Then I should receive information about a user named "John Doe"