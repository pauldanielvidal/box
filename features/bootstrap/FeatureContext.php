<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Romby\Box\Http\Exceptions\NotFoundException;

require_once 'vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    protected $token;

    protected $result;

    protected $baseId;

    protected $localTemp;

    protected $versions;

    protected $randomInt;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($token)
    {
        $this->folders = new \Romby\Box\Services\Folders(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->files = new \Romby\Box\Services\Files(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->comments = new \Romby\Box\Services\Comments(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->collaborations = new \Romby\Box\Services\Collaborations(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->sharedItems = new \Romby\Box\Services\SharedItems(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->users = new \Romby\Box\Services\Users(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->tasks = new \Romby\Box\Services\Tasks(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->groups = new \Romby\Box\Services\Groups(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));
        $this->search = new \Romby\Box\Services\Search(new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client()));

        $this->token = $token;
        $this->randomInt = rand(10000000, 99999999);
    }

    /**
     * @beforeScenario
     */
    public function createTemporaryDirectory()
    {
        $this->baseId = $this->folders->create($this->token, 'tmp_'.microtime(), 0)['id'];
        $this->localTemp = __DIR__.'/'.'tmp_'.microtime();

        mkdir($this->localTemp);
    }

    /**
     * @afterScenario
     */
    public function deleteTemporaryDirectory()
    {
        $this->folders->delete($this->token, $this->baseId, [], true);

        $this->removeDir($this->localTemp);
    }

    /**
     * @beforeScenario
     */
    public function clearGroups()
    {
        foreach($this->groups->all($this->token)['entries'] as $group)
        {
            $this->groups->delete($this->token, $group['id']);
        }
    }

    /**
     * @When I create a folder with the name :name in the base directory
     * @Given I have a folder with the name :name in the base directory
     */
    public function iCreateAFolderWithTheNameInTheBaseDirectory($name)
    {
        $this->result = $this->folders->create($this->token, $name, $this->baseId);
    }

    /**
     * @Then the folder should be created
     */
    public function theFolderShouldBeCreated()
    {
        assertEquals('folder', $this->result['type']);
    }

    /**
     * @When I get information about the folder
     */
    public function iGetInformationAboutTheFolder()
    {
        $this->result = $this->folders->get($this->token, $this->result['id']);
    }

    /**
     * @Then I should receive information about a folder named :name in the base directory
     */
    public function iShouldReceiveInformationAboutAFolderNamedInTheBaseDirectory($name)
    {
        assertEquals($name, $this->result['name']);
        assertEquals($this->baseId, $this->result['parent']['id']);
    }

    /**
     * @When I set the folder's name to :name
     */
    public function iSetTheFolderSNameTo($name)
    {
        $this->folders->update($this->token, $this->result['id'], compact('name'));
    }

    /**
     * @When I copy that folder to the base directory with the name :name
     */
    public function iCopyThatFolderToTheBaseDirectoryWithTheName($name)
    {
        $this->result = $this->folders->copy($this->token, $this->result['id'], $name, $this->baseId);
    }


    /**
     * @When I create a shared link for that folder
     * @Given that folder has a shared link
     */
    public function iCreateASharedLinkForThatFolder()
    {
        $this->folders->createSharedLink($this->token, $this->result['id'], 'open');
    }

    /**
     * @Then the folder should have a shared link
     */
    public function theFolderShouldHaveASharedLink()
    {
        assertNotEmpty($this->result['shared_link']);
    }

    /**
     * @When I delete a shared link for that folder
     */
    public function iDeleteASharedLinkForThatFolder()
    {
        $this->folders->deleteSharedLink($this->token, $this->result['id']);
    }

    /**
     * @Then the folder should have no shared link
     */
    public function theFolderShouldHaveNoSharedLink()
    {
        assertEmpty($this->result['shared_link']);
    }

    /**
     * @Given I have a folder with the name :name in the trash
     */
    public function iHaveAFolderWithTheNameInTheTrash($name)
    {
        $this->result = $this->folders->create($this->token, $name, $this->baseId);

        $this->folders->delete($this->token, $this->result['id']);
    }

    /**
     * @When I get the contents of the trash
     */
    public function iGetTheContentsOfTheTrash()
    {
        $this->result = $this->folders->getTrash($this->token);
    }

    /**
     * @Then I should receive a list of items containing the folder :name
     */
    public function iShouldReceiveAListOfItemsContainingTheFolder($name)
    {
        assertContains($name, array_column($this->result['entries'], 'name'));
    }

    /**
     * @When I delete that folder permanently
     */
    public function iDeleteThatFolderPermanently()
    {
        $this->folders->deleteTrashed($this->token, $this->result['id']);
    }

    /**
     * @Then I should receive a list of items not containing the folder :name
     */
    public function iShouldReceiveAListOfItemsNotContainingTheFolder($name)
    {
        assertNotContains($name, array_column($this->result['entries'], 'name'));
    }


    /**
     * @When I restore that folder to the base directory as :name
     */
    public function iRestoreThatFolderToTheBaseDirectory($name)
    {
        $this->folders->restoreTrashed($this->token, $this->result['id'], $name, $this->baseId);
    }


    /**
     * @Given I have a folder named :name in that directory
     */
    public function iHaveAFolderNamedInThatDirectory($name)
    {
        $this->folders->create($this->token, $name, $this->result['id']);
    }

    /**
     * @When I get the items in the folder
     */
    public function iGetTheItemsInTheFolder()
    {
        $this->result = $this->folders->getItems($this->token, $this->result['id']);
    }

    /**
     * @When I upload the file named :name
     */
    public function iUploadTheFileNamed($name)
    {
        $this->result = $this->files->upload($this->token, $this->localTemp.'/'.$name, $name, $this->baseId);
    }

    /**
     * @Then the file should be uploaded
     */
    public function theFileShouldBeUploaded()
    {
        assertEquals(1, $this->result['total_count']);
    }

    protected function removeDir($dir)
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);

            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir."/".$object) == "dir")
                    {
                        $this->removeDir($dir."/".$object);
                    }
                    else
                    {
                        unlink($dir."/".$object);
                    }
               }
            }

            reset($objects);

            rmdir($dir);
        }
    }

    /**
     * @When I get information about the file
     */
    public function iGetInformationAboutTheFile()
    {
        try
        {
            $this->result = $this->files->get($this->token, $this->result['entries'][0]['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->result = 'not found';
        }
        catch(Exception $exception)
        {
            $this->result = 'unknown exception';
        }
    }

    /**
     * @Then I should receive information about a file named :name in the base directory
     */
    public function iShouldReceiveInformationAboutAFileNamedInTheBaseDirectory($name)
    {
        assertEquals($name, $this->result['name']);
        assertEquals($this->baseId, $this->result['parent']['id']);
    }

    /**
     * @When I set the file's name to :name
     */
    public function iSetTheFileSNameTo($name)
    {
        $this->files->update($this->token, $this->result['entries'][0]['id'], compact('name'));
    }

    /**
     * @When I lock the file
     * @Given the file is locked
     */
    public function iLockTheFile()
    {
        //$this->files->lock($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then the file should be locked
     */
    public function theFileShouldBeLocked()
    {
        // Don't know how to check this!
    }

    /**
     * @Then the file should be unlocked
     */
    public function theFileShouldBeUnlocked()
    {
        // Don't know how to check this!
    }

    /**
     * @When I unlock the file
     */
    public function iUnlockTheFile()
    {
        //$this->files->unlock($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @When I download that file to :name
     */
    public function iDownloadThatFileTo($name)
    {
        $this->files->download($this->token, $this->result['entries'][0]['id'], $this->localTemp.'/'.$name);
    }

    /**
     * @Given I have a remote file named :name with the content :content in the base directory
     */
    public function iHaveARemoteFileNamedWithTheContentInTheBaseDirectory($name, $content)
    {
        $this->iHaveALocalFileNamedWithTheContentInTheBaseDirectory($name, $content);

        $this->iUploadTheFileNamed($name);
    }

    /**
     * @Given I have a local file named :name with the content :content in the base directory
     */
    public function iHaveALocalFileNamedWithTheContentInTheBaseDirectory($name, $content)
    {
        file_put_contents($this->localTemp.'/'.$name, $content);
    }

    /**
     * @Then I should have a local file named :name with the content :content
     */
    public function iShouldHaveALocalFileNamedWithTheContent($name, $content)
    {
        assertTrue(file_exists($this->localTemp . '/' . $name));
        assertEquals($content, file_get_contents($this->localTemp . '/' . $name));
    }

    /**
     * @When I conduct a preflight check for a file named :name in the base directory
     */
    public function iConductAPreflightCheckForAFileNamedInTheBaseDirectory($name)
    {
        try
        {
            $this->result = $this->files->preflightCheck($this->token, $name, $this->baseId);
        }
        catch(\Romby\Box\Http\Exceptions\NameConflictException $exception)
        {
            $this->result = 'name conflict';
        }
        catch(Exception $exception)
        {
            $this->result = 'unknown exception';
        }
    }

    /**
     * @Then I should receive a positive answer
     */
    public function iShouldReceiveAPositiveAnswer()
    {
        assertNotEmpty($this->result['upload_url']);
    }

    /**
     * @Then I should receive a negative answer
     */
    public function iShouldReceiveANegativeAnswer()
    {
        assertEquals('name conflict', $this->result);
    }

    /**
     * @When I delete that file
     */
    public function iDeleteThatFile()
    {
        $this->files->delete($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then I should not be able to find the file
     */
    public function iShouldNotBeAbleToFindTheFile()
    {
        assertEquals('not found', $this->result);
    }

    /**
     * @When I upload a new version of the file from the local file :file
     */
    public function iUploadANewVersionOfTheFileFromTheLocalFile($file)
    {
        $this->files->uploadVersion($this->token, $this->result['entries'][0]['id'], $this->localTemp.'/'.$file);
    }

    /**
     * @When I view the versions of that file
     */
    public function iViewTheVersionsOfThatFile()
    {
        $this->versions = $this->files->getVersions($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then I should receive :count versions
     */
    public function iShouldReceiveVersions($count)
    {
        // This is only available for premium users

        assertEquals($count, $this->versions['total_count']);
    }

    /**
     * @When I promote the first version of the file
     */
    public function iPromoteTheFirstVersionOfTheFile()
    {
        $this->iViewTheVersionsOfThatFile();

        $this->result = $this->files->promoteVersion($this->token, $this->result['entries'][0]['id'], end($this->versions['entries'])['id']);
    }

    /**
     * @Then the file should be promoted
     */
    public function theFileShouldBePromoted()
    {
        assertEquals('file_version', $this->result['type']);
    }

    /**
     * @When I delete the first version of the file
     */
    public function iDeleteTheFirstVersionOfTheFile()
    {
        $this->iViewTheVersionsOfThatFile();

        $this->files->deleteVersion($this->token, $this->result['entries'][0]['id'], end($this->versions['entries'])['id']);
    }

    /**
     * @Then the first version of the file should be removed
     */
    public function theFirstVersionOfTheFileShouldBeRemoved()
    {
        $previousVersions = $this->versions;

        $this->iViewTheVersionsOfThatFile();

        // This is only available for premium users

        assertNotEquals(end($previousVersions['entries'])['id'], end($this->versions['entries'])['id']);
    }

    /**
     * @When I copy that file as :name in the base directory
     */
    public function iCopyThatFileAsInTheBaseDirectory($name)
    {
        $this->result = ['entries' => [$this->files->copy($this->token, $this->result['entries'][0]['id'], $this->baseId, $name)]];
    }

    /**
     * @When I create a shared link for that file
     * @Given that file has a shared link
     */
    public function iCreateASharedLinkForThatFile()
    {
        $this->files->createSharedLink($this->token, $this->result['entries'][0]['id'], 'open');
    }

    /**
     * @Then the file should have a shared link
     */
    public function theFileShouldHaveASharedLink()
    {
        assertNotEmpty($this->result['shared_link']);
    }

    /**
     * @When I delete a shared link for that file
     */
    public function iDeleteASharedLinkForThatFile()
    {
        $this->files->deleteSharedLink($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then the folder should have no shared file
     */
    public function theFolderShouldHaveNoSharedFile()
    {
        assertEmpty($this->result['shared_link']);
    }

    /**
     * @When I get information about the file which is in the trash
     */
    public function iGetInformationAboutTheFileWhichIsInTheTrash()
    {
        $this->result = $this->files->getTrashed($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then I should get information on a file that is in the trash
     */
    public function iShouldGetInformationOnAFileThatIsInTheTrash()
    {
        assertNotEmpty($this->result['trashed_at']);
    }


    /**
     * @When I delete that file permanently
     */
    public function iDeleteThatFilePermanently()
    {
        $this->files->deleteTrashed($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @When I restore that file to the base directory as :name
     */
    public function iRestoreThatFileToTheBaseDirectoryAs($name)
    {
        $this->files->restoreTrashed($this->token, $this->result['entries'][0]['id'], $name, $this->baseId);
    }

    /**
     * @Given I have a remote file named :name with the content :content in the trash
     */
    public function iHaveARemoteFileNamedWithTheContentInTheTrash($name, $content)
    {
        $this->iHaveARemoteFileNamedWithTheContentInTheBaseDirectory($name, $content);

        $this->files->delete($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then the comment should be persisted
     */
    public function theCommentShouldBePersisted()
    {
        assertEquals('comment', $this->result['type']);

        assertNotEmpty($this->result['id']);
    }

    /**
     * @When I save a comment with :message on that file
     */
    public function iSaveACommentWithOnThatFile($message)
    {
        $this->result = $this->comments->create($this->token, $this->result['entries'][0]['id'], 'file', $message);
    }

    /**
     * @When I get information about the comment
     */
    public function iGetInformationAboutTheComment()
    {
        try
        {
            $this->result = $this->comments->get($this->token, $this->result['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->result = 'not found';
        }
        catch(Exception $exception)
        {
            $this->result = 'unknown exception';
        }
    }

    /**
     * @Then I should receive information about a comment with the message :message
     */
    public function iShouldReceiveInformationAboutACommentWithTheMessage($message)
    {
        assertEquals($message, $this->result['message']);
    }

    /**
     * @When I change the message of that comment to :message
     */
    public function iChangeTheMessageOfThatCommentTo($message)
    {
        $this->comments->update($this->token, $this->result['id'], $message);
    }

    /**
     * @When I delete that comment
     */
    public function iDeleteThatComment()
    {
        $this->comments->delete($this->token, $this->result['id']);
    }

    /**
     * @Then I should not be able to find the comment
     */
    public function iShouldNotBeAbleToFindTheComment()
    {
        assertEquals('not found', $this->result);
    }

    /**
     * @Given that file has :count comments
     */
    public function thatFileHasComments($count)
    {
        foreach(range(1, $count) as $index)
        {
            $this->comments->create($this->token, $this->result['entries'][0]['id'], 'file', 'Some Comment ' . $index);
        }
    }

    /**
     * @When I view the comments on the file
     */
    public function iViewTheCommentsOnTheFile()
    {
        $this->result = $this->files->getComments($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @Then I should receive :count comments
     */
    public function iShouldReceiveComments($count)
    {
        assertEquals($count, $this->result['total_count']);
    }

    /**
     * @Then the collaboration should have been persisted
     */
    public function theCollaborationShouldHaveBeenPersisted()
    {
        assertEquals('collaboration', $this->result['type']);

        assertNotEmpty($this->result['id']);
    }

    /**
     * @When I retrieve information about that collaboration
     */
    public function iRetrieveInformationAboutThatCollaboration()
    {
        try
        {
            $this->result = $this->collaborations->get($this->token, $this->result['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->result = 'not found';
        }
        catch(Exception $exception)
        {
            $this->result = 'unknown exception';
        }
    }

    /**
     * @Then I should receive information about a collaboration with :status status
     */
    public function iShouldReceiveInformationAboutACollaborationWithStatus($status)
    {
        assertEquals($status, $this->result['status']);
    }

    /**
     * @Then I should receive information about a collaboration with :role role
     */
    public function iShouldReceiveInformationAboutACollaborationWithRole($role)
    {
        assertEquals($role, $this->result['role']);
    }

    /**
     * @When I update the collaboration role to :role
     */
    public function iUpdateTheCollaborationRoleTo($role)
    {
        $this->collaborations->update($this->token, $this->result['id'], $role);
    }

    /**
     * @When I delete that collaboration
     */
    public function iDeleteThatCollaboration()
    {
        $this->collaborations->delete($this->token, $this->result['id']);
    }

    /**
     * @Then I should not be able to find the collaboration
     */
    public function iShouldNotBeAbleToFindTheCollaboration()
    {
        assertEquals('not found', $this->result);
    }

    /**
     * @When I get the pending collaborations for my user
     */
    public function iGetThePendingCollaborationsForMyUser()
    {
        $this->result = $this->collaborations->getPending($this->token);
    }

    /**
     * @Then I should receive :count collaborations
     */
    public function iShouldReceiveCollaborations($count)
    {
        assertEquals($count, $this->result['total_count']);
    }


    /**
     * @When I add a collaboration with :collaborator to that folder
     */
    public function iAddACollaborationWithToThatFolder($collaborator)
    {
        $this->result = $this->collaborations->create($this->token, $this->result['id'], 'editor', null, null, $collaborator);
    }

    /**
     * @Given I add two collaborations with :arg1 and :arg2 to that folder
     */
    public function iAddTwoCollaborationsWithAndToThatFolder($email1, $email2)
    {
        $this->collaborations->create($this->token, $this->result['id'], 'editor', null, null, $email1);

        $this->collaborations->create($this->token, $this->result['id'], 'editor', null, null, $email2);
    }

    /**
     * @When I view all collaborations for that folder
     */
    public function iViewAllCollaborationsForThatFolder()
    {
        $this->result = $this->folders->getCollaborations($this->token, $this->result['id']);
    }

    /**
     * @When I retrieve information about that shared link
     */
    public function iRetrieveInformationAboutThatSharedLink()
    {
        $this->result = $this->sharedItems->get($this->token, $this->result['shared_link']['url']);
    }

    /**
     * @When I get information about the current user
     */
    public function iGetInformationAboutTheCurrentUser()
    {
        $this->result = $this->users->me($this->token);
    }

    /**
     * @Then I should receive information about a user
     * @Then I should receive information about a user named :name
     */
    public function iShouldReceiveInformationAboutAUser($name = null)
    {
        assertEquals('user', $this->result['type']);

        if( ! is_null($name))
        {
            assertEquals($name, $this->result['name']);
        }
    }

    /**
     * @When I create a user with the email :email and the name :name
     * @Given I have a user with the email :email and the name :name
     */
    public function iCreateAUserWithTheEmailAndTheName($email, $name)
    {
        $email = $this->getUniqueEmail($email);

        $this->result = $this->users->create($this->token, $email, $name);
    }

    /**
     * @When I get information about the user
     */
    public function iGetInformationAboutTheUser()
    {
        try
        {
            $this->result = $this->users->get($this->token, $this->result['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->result = 'not found';
        }
        catch(Exception $exception)
        {
            $this->result = 'unknown exception';
        }
    }

    /**
     * @When I get all users in the enterprise
     */
    public function iGetAllUsersInTheEnterprise()
    {
        $this->result = $this->users->all($this->token);
    }

    /**
     * @Then I should receive a list of all users in the enterprise
     */
    public function iShouldReceiveAListOfAllUsersInTheEnterprise()
    {
        assertNotEmpty($this->result['entries']);
    }

    /**
     * @param $email
     * @return string
     */
    protected function getUniqueEmail($email)
    {
        $emailParts = explode('@', $email);

        $emailParts[0] .= '_' . $this->randomInt;

        return implode('@', $emailParts);
    }

    /**
     * @When I set the user's name to :name
     */
    public function iSetTheUserSNameTo($name)
    {
        $this->users->update($this->token, $this->result['id'], ['name' => $name]);
    }

    /**
     * @When I delete that user
     */
    public function iDeleteThatUser()
    {
        $this->users->delete($this->token, $this->result['id']);
    }

    /**
     * @Then I should not be able to find the user
     */
    public function iShouldNotBeAbleToFindTheUser()
    {
        assertEquals('not found', $this->result);
    }

    /**
     * @When I get all email aliases for that user
     */
    public function iGetAllEmailAliasesForThatUser()
    {
        $this->result = $this->users->getAllEmailAliases($this->token, $this->result['id']);
    }

    /**
     * @Then I should receive :arg1 email alias(es)
     */
    public function iShouldReceiveEmailAlias($count)
    {
        assertEquals($count, $this->result['total_count']);
    }

    /**
     * @When I add the email alias :alias for that user
     */
    public function iAddTheEmailAliasForThatUser($alias)
    {
        $this->users->createEmailAlias($this->token, $this->result['id'], $alias);
    }

    /**
     * @When I create a task for that file with the message :message
     * @Given I have a task for that file with the message :message
     */
    public function iCreateATaskForThatFileWithTheMessage($message)
    {
        $this->task = $this->tasks->create($this->token, $this->result['entries'][0]['id'], $message);
    }

    /**
     * @When I get information about the task
     */
    public function iGetInformationAboutTheTask()
    {
        try
        {
            $this->task = $this->tasks->get($this->token, $this->task['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->task = 'not found';
        }
    }

    /**
     * @Then I should get information about a task with the message :message
     */
    public function iShouldGetInformationAboutATaskWithTheMessage($message)
    {
        assertEquals($message, $this->task['message']);
    }

    /**
     * @When I update the message of that task to :message
     */
    public function iUpdateTheMessageOfThatTaskTo($message)
    {
        $this->tasks->update($this->token, $this->task['id'], $message);
    }

    /**
     * @When I delete that task
     */
    public function iDeleteThatTask()
    {
        $this->tasks->delete($this->token, $this->task['id']);
    }

    /**
     * @Then I should not be able to find the task
     */
    public function iShouldNotBeAbleToFindTheTask()
    {
        assertEquals('not found', $this->task);
    }

    /**
     * @When I create a task assignment for the current user
     * @Given I have a task assignment for the current user
     */
    public function iCreateATaskAssignmentForTheCurrentUser()
    {
        $user = $this->users->me($this->token);

        $this->assignment = $this->tasks->createTaskAssignment($this->token, $this->task['id'], ['id' => $user['id']]);
    }

    /**
     * @When I get information about the task assignment
     */
    public function iGetInformationAboutTheTaskAssignment()
    {
        try
        {
            $this->assignment = $this->tasks->getTaskAssignment($this->token, $this->assignment['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->assignment = 'not found';
        }
    }

    /**
     * @Then I should get information about a task assignment with the status :status
     */
    public function iShouldGetInformationAboutATaskAssignmentWithTheStatus($status)
    {
        assertEquals($status, $this->assignment['resolution_state']);
    }

    /**
     * @When I update the status of that task assignment to :status
     */
    public function iUpdateTheStatusOfThatTaskAssignmentTo($status)
    {
        $this->tasks->updateTaskAssignment($this->token, $this->assignment['id'], null, $status);
    }

    /**
     * @When I delete that task assignment
     */
    public function iDeleteThatTaskAssignment()
    {
        $this->tasks->deleteTaskAssignment($this->token, $this->assignment['id']);
    }

    /**
     * @Then I should not be able to find the task assignment
     */
    public function iShouldNotBeAbleToFindTheTaskAssignment()
    {
        assertEquals('not found', $this->assignment);
    }

    /**
     * @When I create a group named :name
     * @Given I have a group named :name
     */
    public function iCreateAGroupNamed($name)
    {
        $this->result = $this->groups->create($this->token, $name);
    }

    /**
     * @When I get information about the group
     */
    public function iGetInformationAboutTheGroup()
    {
        try
        {
            $this->result = $this->groups->get($this->token, $this->result['id']);
        }
        catch(NotFoundException $exception)
        {
            $this->result = 'not found';
        }
    }

    /**
     * @Then I should get information about a group named :name
     */
    public function iShouldGetInformationAboutAGroupNamed($name)
    {
        assertEquals($name, $this->result['name']);
    }

    /**
     * @When I get all groups
     */
    public function iGetAllGroups()
    {
        $this->result = $this->groups->all($this->token);
    }

    /**
     * @Then I should get information about :count groups.
     */
    public function iShouldGetInformationAboutGroups($count)
    {
        assertEquals($count, $this->result['total_count']);
    }

    /**
     * @When I update the name of that group to :name
     */
    public function iUpdateTheNameOfThatGroupTo($name)
    {
        $this->groups->update($this->token, $this->result['id'], $name);
    }

    /**
     * @When I delete that group
     */
    public function iDeleteThatGroup()
    {
        $this->groups->delete($this->token, $this->result['id']);
    }

    /**
     * @Then I should not be able to find the group
     */
    public function iShouldNotBeAbleToFindTheGroup()
    {
        assertEquals('not found', $this->result);
    }

    /**
     * @When I search for :query
     */
    public function iSearchFor($query)
    {
        $this->result = $this->search->query($this->token, $query);
    }

    /**
     * @Then I should receive :arg1 item(s)
     */
    public function iShouldReceiveItem($count)
    {
        assertEquals($count, $this->result['total_count']);
    }

    /**
     * @When I get all tasks for the file
     */
    public function iGetAllTasksForTheFile()
    {
        $this->result = $this->files->getTasks($this->token, $this->result['entries'][0]['id']);
    }

    /**
     * @When I get the thumbnail for that file
     */
    public function iGetTheThumbnailForThatFile()
    {
        $this->result = $this->files->thumbnail($this->token,  $this->result['entries'][0]['id']);
    }

    /**
     * @Then I should receive a thumbnail
     */
    public function iShouldReceiveAThumbnail()
    {
        assertNotEmpty($this->result);
    }

    /**
     * @When I get all task assignments for the task
     */
    public function iGetAllTaskAssignmentsForTheTask()
    {
        $this->result = $this->tasks->getTaskAssignments($this->token, $this->task['id']);
    }
}
