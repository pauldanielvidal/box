Feature: Collaborations
  In order to use the collaborations in Box.com
  As a developer
  I need to be able to perform operations on the collaborations

  Scenario: Adding a collaboration
    Given I have a folder with the name "Collaboration Folder" in the base directory
    When I add a collaboration with "johndoe@example.com" to that folder
    Then the collaboration should have been persisted

  Scenario: Retrieving a collaboration
    Given I have a folder with the name "Collaboration Folder" in the base directory
    When I add a collaboration with "johndoe@example.com" to that folder
    When I retrieve information about that collaboration
    Then I should receive information about a collaboration with "accepted" status

  Scenario: Editing a collaboration
    Given I have a folder with the name "Collaboration Folder" in the base directory
    When I add a collaboration with "johndoe@example.com" to that folder
    When I update the collaboration role to "viewer"
    When I retrieve information about that collaboration
    Then I should receive information about a collaboration with "viewer" role

  Scenario: Removing a collaboration
    Given I have a folder with the name "Collaboration Folder" in the base directory
    When I add a collaboration with "johndoe@example.com" to that folder
    When I delete that collaboration
    When I retrieve information about that collaboration
    Then I should not be able to find the collaboration

  Scenario: Getting pending collaborations
    When I get the pending collaborations for my user
    Then I should receive 0 collaborations