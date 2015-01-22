Feature: Shared items
  In order to use the shared items in Box.com
  As a developer
  I need to be able to perform operations on the shared items

  Scenario: Getting a shared item
    Given I have a folder with the name "Share Me" in the base directory
    And I create a shared link for that folder
    And I get information about the folder
    When I retrieve information about that shared link
    Then I should receive information about a folder named "Share Me" in the base directory