Feature: Files
  In order to use the files in Box.com
  As a developer
  I need to be able to perform operations on the files

  Scenario: Getting information about a file
    Given I have a remote file named "File.txt" in the base directory
    When I get information about the file
    Then I should receive information about a file named "File.txt" in the base directory

  Scenario: Updating a file
    Given I have a remote file named "File.txt" in the base directory
    When I set the file's name to "Updated file.txt"
    And I get information about the file
    Then I should receive information about a file named "Updated file.txt" in the base directory

  Scenario: Locking a file
    Given I have a remote file named "File To Lock.txt" in the base directory
    When I lock the file
    Then the file should be locked

  Scenario: Unlocking a file
    Given I have a remote file named "File To Unlock.txt" in the base directory
    And the file is locked
    When I unlock the file
    Then the file should be unlocked

  Scenario: Uploading a file
    Given I have a local file named "Local File.txt"
    When I upload the file named "Local File.txt"
    Then the file should be uploaded