Feature: Users
  In order to use the users in Box.com
  As a developer
  I need to be able to perform operations on the users

  Scenario: Getting the current user's information
    When I get information about the current user
    Then I should receive information about a user

  Scenario: Getting all users
    When I get all users in the enterprise
    Then I should receive a list of all users in the enterprise

  Scenario: Creating an enterprise user
    When I create a user with the email "johndoe@example.com" and the name "John Doe"
    And I get information about the user
    Then I should receive information about a user named "John Doe"

  Scenario: Getting information on a user
    Given I have a user with the email "getmyinformation@example.com" and the name "Get My Information"
    When I get information about the user
    Then I should receive information about a user named "Get My Information"

  Scenario: Updating a user
    Given I have a user with the email "updatetmyinformation@example.com" and the name "Update My Information"
    When I set the user's name to "My Information Is Updated"
    And I get information about the user
    Then I should receive information about a user named "My Information Is Updated"

  Scenario: Deleting a user
    Given I have a user with the email "deleteme@example.com" and the name "Delete Me"
    When I delete that user
    And I get information about the user
    Then I should not be able to find the user

  Scenario: Getting all email aliases for a user
    Given I have a user with the email "userwithoutaliases@example.com" and the name "No Aliases"
    When I get all email aliases for that user
    Then I should receive 0 email aliases

  Scenario: Adding an email alias for a user:
    Given I have a user with the email "aliasme@example.com" and the name "Alias Me"
    When I add the email alias "myalias@example.com" for that user
    When I get all email aliases for that user
    Then I should receive 1 email alias
