Feature: Comments
  In order to use the comments in Box.com
  As a developer
  I need to be able to perform operations on the comments

  Scenario: Adding a comment to a file
    Given I have a remote file named "Comment Me.txt" with the content "comment me" in the base directory
    When I save a comment with "This Is A Comment" on that file
    Then the comment should be persisted

  Scenario: Getting information about a comment
    Given I have a remote file named "Comment Me.txt" with the content "comment me" in the base directory
    And I save a comment with "This Is A Comment" on that file
    When I get information about the comment
    Then I should receive information about a comment with the message "This Is A Comment"

  Scenario: Changing a comment's message
    Given I have a remote file named "Comment Me.txt" with the content "comment me" in the base directory
    And I save a comment with "This Is A Comment" on that file
    When I change the message of that comment to "This Is My New Comment"
    And I get information about the comment
    Then I should receive information about a comment with the message "This Is My New Comment"

  Scenario: Deleting a comment
    Given I have a remote file named "Comment Me.txt" with the content "comment me" in the base directory
    And I save a comment with "This Is A Comment" on that file
    When I delete that comment
    And I get information about the comment
    Then I should not be able to find the comment
