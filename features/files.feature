Feature: Files
  In order to use the files in Box.com
  As a developer
  I need to be able to perform operations on the files

  Scenario: Getting information about a file
    Given I have a remote file named "File.txt" with the content "test content" in the base directory
    When I get information about the file
    Then I should receive information about a file named "File.txt" in the base directory

  Scenario: Updating a file
    Given I have a remote file named "File.txt" with the content "test content" in the base directory
    When I set the file's name to "Updated file.txt"
    And I get information about the file
    Then I should receive information about a file named "Updated file.txt" in the base directory

  Scenario: Locking a file
    Given I have a remote file named "File To Lock.txt" with the content "test content" in the base directory
    When I lock the file
    Then the file should be locked

  Scenario: Unlocking a file
    Given I have a remote file named "File To Unlock.txt" with the content "test content" in the base directory
    And the file is locked
    When I unlock the file
    Then the file should be unlocked

  Scenario: Uploading a file
    Given I have a local file named "Local File.txt" with the content "test content" in the base directory
    When I upload the file named "Local File.txt"
    Then the file should be uploaded

  Scenario: Downloading a file
    Given I have a remote file named "File To Download.txt" with the content "test content" in the base directory
    When I download that file to "Local Downloaded File.txt"
    Then I should have a local file named "Local Downloaded File.txt" with the content "test content"

  Scenario: Conducting a successful preflight check
    When I conduct a preflight check for a file named "Unique Filename.txt" in the base directory
    Then I should receive a positive answer

  Scenario: Conducting a failed preflight check
    Given I have a remote file named "Not So Unique.txt" with the content "test content" in the base directory
    When I conduct a preflight check for a file named "Not So Unique.txt" in the base directory
    Then I should receive a negative answer

  Scenario: Deleting a file
    Given I have a remote file named "Delete Me.txt" with the content "test content" in the base directory
    When I delete that file
    And I get information about the file
    Then I should not be able to find the file

  Scenario: Uploading a new version of a file
    Given I have a remote file named "Old Version.txt" with the content "test content" in the base directory
    And I have a local file named "New Version.txt" with the content "new content" in the base directory
    When I upload a new version of the file from the local file "New Version.txt"
    When I download that file to "Local Downloaded File.txt"
    Then I should have a local file named "Local Downloaded File.txt" with the content "new content"

  Scenario: Getting the existing versions of a file
    Given I have a remote file named "Old Version.txt" with the content "test content" in the base directory
    And I have a local file named "New Version.txt" with the content "new content" in the base directory
    When I upload a new version of the file from the local file "New Version.txt"
    And I view the versions of that file
    Then I should receive 2 versions

  Scenario: Promoting an old version of a file
    Given I have a remote file named "Old Version.txt" with the content "test content" in the base directory
    And I have a local file named "New Version.txt" with the content "new content" in the base directory
    When I upload a new version of the file from the local file "New Version.txt"
    And I promote the first version of the file
    Then the file should be promoted

  Scenario: Deleting an old version of a file
    Given I have a remote file named "Old Version.txt" with the content "test content" in the base directory
    And I have a local file named "New Version.txt" with the content "new content" in the base directory
    When I upload a new version of the file from the local file "New Version.txt"
    And I delete the first version of the file
    Then the first version of the file should be removed