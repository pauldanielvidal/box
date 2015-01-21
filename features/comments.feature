Feature: Files
  In order to use the comments in Box.com
  As a developer
  I need to be able to perform operations on the comments

  Scenario: Adding a comment to a file
    Given I have a remote file named "Comment Me.txt" with the content "comment me" in the base directory
    When I save a comment with "This Is A Comment" on that file
    Then the comment should be persisted