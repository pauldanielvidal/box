Feature: Tasks
  In order to use the tasks in Box.com
  As a developer
  I need to be able to perform operations on the tasks

  Scenario: Creating a task and getting information about it
    Given I have a remote file named "Remote File.txt" with the content "remote file" in the base directory
    When I create a task for that file with the message "Review this file"
    And I get information about the task
    Then I should get information about a task with the message "Review this file"

  Scenario: Updating a task
    Given I have a remote file named "Remote File.txt" with the content "remote file" in the base directory
    And I have a task for that file with the message "Review this file"
    When I update the message of that task to "Message was updated"
    And I get information about the task
    Then I should get information about a task with the message "Message was updated"

  Scenario: Deleting a task
    Given I have a remote file named "Remote File.txt" with the content "remote file" in the base directory
    And I have a task for that file with the message "Review this file"
    When I delete that task
    And I get information about the task
    Then I should not be able to find the task

  Scenario: Creating a task assignment and getting information about it
    Given I have a remote file named "Remote File.txt" with the content "remote file" in the base directory
    And I have a task for that file with the message "Review this file"
    When I create a task assignment for the current user
    And I get information about the task assignment
    Then I should get information about a task assignment with the status "incomplete"

  Scenario: Updating a task assignment
    Given I have a remote file named "Remote File.txt" with the content "remote file" in the base directory
    And I have a task for that file with the message "Review this file"
    And I have a task assignment for the current user
    When I update the status of that task assignment to "completed"
    And I get information about the task assignment
    Then I should get information about a task assignment with the status "completed"

  Scenario: Deleting a task assignment
    Given I have a remote file named "Remote File.txt" with the content "remote file" in the base directory
    And I have a task for that file with the message "Review this file"
    And I have a task assignment for the current user
    When I delete that task assignment
    And I get information about the task assignment
    Then I should not be able to find the task assignment
