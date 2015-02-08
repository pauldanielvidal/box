Feature: Search
  In order to use the search in Box.com
  As a developer
  I need to be able to perform operations on the search

  Scenario: Searching for a file
    Given I have a remote file named "Some file" with the content "Some file content" in the base directory
    And I have a remote file named "Other file" with the content "Other file content" in the base directory
    When I search for "Some"
    Then I should receive 1 item

  Scenario: Searching for a folder
    Given I have a folder with the name "Some directory" in the base directory
    And I have a folder with the name "Other directory" in the base directory
    When I search for "Some"
    Then I should receive 1 item
