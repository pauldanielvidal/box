Feature: Folders
  In order to use the folders in Box.com
  As a developer
  I need to be able to perform operations on the folders

  Scenario: Getting the items in a folder
    Given I have a folder with the name "Testing getting items" in the base directory
    And I have a folder named "Subdirectory" in that directory
    When I get the items in the folder
    Then I should receive a list of items containing the folder "Subdirectory"

  Scenario: Creating a folder
    When I create a folder with the name "My New Folder" in the base directory
    Then the folder should be created

  Scenario: Getting information on a folder
    Given I have a folder with the name "Testing get information" in the base directory
    When I get information about the folder
    Then I should receive information about a folder named "Testing get information" in the base directory

  Scenario: Updating a folder
    Given I have a folder with the name "Testing update" in the base directory
    When I set the folder's name to "Testing update 2"
    And I get information about the folder
    Then I should receive information about a folder named "Testing update 2" in the base directory

  Scenario: Copying a folder
    Given I have a folder with the name "Testing copy" in the base directory
    When I copy that folder to the base directory with the name "Testing copy 2"
    And I get information about the folder
    Then I should receive information about a folder named "Testing copy 2" in the base directory

  Scenario: Creating a shared link for a folder
    Given I have a folder with the name "Testing create shared links" in the base directory
    When I create a shared link for that folder
    And I get information about the folder
    Then the folder should have a shared link

  Scenario: Deleting a shared link for a folder
    Given I have a folder with the name "Testing delete shared links" in the base directory
    And that folder has a shared link
    When I delete a shared link for that folder
    And I get information about the folder
    Then the folder should have no shared link

  Scenario: Getting items in the trash
    Given I have a folder with the name "Testing trash" in the trash
    When I get the contents of the trash
    Then I should receive a list of items containing the folder "Testing trash"

  Scenario: Permanently delete an item in the trash
    Given I have a folder with the name "Testing delete trashed" in the trash
    When I delete that folder permanently
    And I get the contents of the trash
    Then I should receive a list of items not containing the folder "Testing delete trashed"

  Scenario: Restoring a trashed folder
    Given I have a folder with the name "Testing restore trashed" in the trash
    When I restore that folder to the base directory as "Restored folder"
    And I get information about the folder
    Then I should receive information about a folder named "Restored folder" in the base directory

  Scenario: Viewing a folders collaborations
    Given I have a folder with the name "Testing get collaborations" in the base directory
    And I add two collaborations with "johndoe@example.com" and "janedoe@example.com" to that folder
    When I view all collaborations for that folder
    Then I should receive 2 collaborations