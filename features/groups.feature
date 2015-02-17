Feature: Groups
  In order to use the groups in Box.com
  As a developer
  I need to be able to perform operations on the groups

  Scenario: Creating a group and getting information about it
    When I create a group named "New Group"
    And I get information about the group
    Then I should get information about a group named "New Group"

  Scenario: Getting all groups
    Given I have a group named "My First Group"
    And I have a group named "My Second Group"
    When I get all groups
    Then I should get information about 2 groups.

  Scenario: Updating a group
    Given I have a group named "My Group"
    When I update the name of that group to "Updated Group"
    And I get information about the group
    Then I should get information about a group named "Updated Group"

  Scenario: Deleting a group
    Given I have a group named "Unused Group"
    When I delete that group
    And I get information about the group
    Then I should not be able to find the group
