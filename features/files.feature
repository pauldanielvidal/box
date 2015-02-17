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

  Scenario: Copying a file
    Given I have a remote file named "Original.txt" with the content "test content" in the base directory
    When I copy that file as "Copy.txt" in the base directory
    And I get information about the file
    Then I should receive information about a file named "Copy.txt" in the base directory

  Scenario: Creating a shared link for a file
    Given I have a remote file named "Share Me.txt" with the content "share me" in the base directory
    When I create a shared link for that file
    And I get information about the file
    Then the file should have a shared link

  Scenario: Deleting a shared link for a file
    Given I have a remote file named "Unshare Me.txt" with the content "unshare me" in the base directory
    And that file has a shared link
    When I delete a shared link for that file
    And I get information about the file
    Then the folder should have no shared file

  Scenario: Getting a file that has been trashed
    Given I have a remote file named "Trash Me.txt" with the content "trash me" in the base directory
    When I delete that file
    And I get information about the file which is in the trash
    Then I should get information on a file that is in the trash

  Scenario: Permanently delete a file in the trash
    Given I have a remote file named "Permanently Trash Me.txt" with the content "permanently trash me" in the trash
    When I delete that file permanently
    And I get the contents of the trash
    Then I should receive a list of items not containing the folder "Testing delete trashed"

  Scenario: Restoring a trashed file
    Given I have a remote file named "Restore Me.txt" with the content "restore me" in the trash
    When I restore that file to the base directory as "I Am Restored.txt"
    And I get information about the file
    Then I should receive information about a file named "I Am Restored.txt" in the base directory

  Scenario: Getting all comments on a file
    Given I have a remote file named "Comment Me.txt" with the content "comment me" in the base directory
    And that file has 2 comments
    When I view the comments on the file
    Then I should receive 2 comments

  Scenario: Getting the tasks for a file
    Given I have a remote file named "Make A Task.txt" with the content "make a task" in the base directory
    And I have a task for that file with the message "A Simple Task"
    And I have a task for that file with the message "Another Simple Task"
    When I get all tasks for the file
    Then I should receive 2 items

  Scenario: Getting a thumbnail for a file
    Given I have a remote file named "Thumbnail.txt" with the content "thumbnail" in the base directory
    When I get the thumbnail for that file
    Then I should receive a thumbnail
