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

        $this->token = $token;
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
        $this->folders->delete($this->baseId, $this->token, [], true);

        $this->removeDir($this->localTemp);
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
        $this->result = $this->folders->get($this->result['id'], $this->token);
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
        $this->folders->update($this->result['id'], $this->token, compact('name'));
    }

    /**
     * @When I copy that folder to the base directory with the name :name
     */
    public function iCopyThatFolderToTheBaseDirectoryWithTheName($name)
    {
        $this->result = $this->folders->copy($this->result['id'], $this->token, $name, $this->baseId);
    }


    /**
     * @When I create a shared link for that folder
     * @Given that folder has a shared link
     */
    public function iCreateASharedLinkForThatFolder()
    {
        $this->folders->createSharedLink($this->result['id'], $this->token, 'open');
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
        $this->folders->deleteSharedLink($this->result['id'], $this->token);
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
        $this->folders->delete($this->result['id'], $this->token);
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
        $this->folders->deleteTrashed($this->result['id'], $this->token);
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
        $this->folders->restoreTrashed($this->result['id'], $this->token, $name, $this->baseId);
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
        $this->result = $this->folders->getItems($this->result['id'], $this->token);
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
            $this->result = $this->files->get($this->result['entries'][0]['id'], $this->token);
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
        $this->files->update($this->result['entries'][0]['id'], $this->token, compact('name'));
    }

    /**
     * @When I lock the file
     * @Given the file is locked
     */
    public function iLockTheFile()
    {
        //$this->files->lock($this->result['entries'][0]['id'], $this->token);
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
        //$this->files->unlock($this->result['entries'][0]['id'], $this->token);
    }

    /**
     * @When I download that file to :name
     */
    public function iDownloadThatFileTo($name)
    {
        $this->files->download($this->result['entries'][0]['id'], $this->token, $this->localTemp.'/'.$name);
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
        assertTrue(file_exists($this->localTemp.'/'.$name));
        assertEquals($content, file_get_contents($this->localTemp.'/'.$name));
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
        $this->files->delete($this->result['entries'][0]['id'], $this->token);
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
        $this->files->uploadVersion($this->result['entries'][0]['id'], $this->token, $this->localTemp.'/'.$file);
    }

    /**
     * @When I view the versions of that file
     */
    public function iViewTheVersionsOfThatFile()
    {
        $this->versions = $this->files->getVersions($this->result['entries'][0]['id'], $this->token);
    }

    /**
     * @Then I should receive :count versions
     */
    public function iShouldReceiveVersions($count)
    {
        // This is only available for premium users

        //assertEquals($count, $this->versions['total_count']);
    }

    /**
     * @When I promote the first version of the file
     */
    public function iPromoteTheFirstVersionOfTheFile()
    {
        $this->iViewTheVersionsOfThatFile();

        $this->result = $this->files->promoteVersion($this->result['entries'][0]['id'], $this->token, end($this->versions['entries'])['id']);
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

        $this->files->deleteVersion($this->result['entries'][0]['id'], $this->token, end($this->versions['entries'])['id']);
    }

    /**
     * @Then the first version of the file should be removed
     */
    public function theFirstVersionOfTheFileShouldBeRemoved()
    {
        $previousVersions = $this->versions;

        $this->iViewTheVersionsOfThatFile();

        // This is only available for premium users

        //assertNotEquals(end($previousVersions['entries'])['id'], end($this->versions['entries'])['id']);
    }

    /**
     * @When I copy that file as :name in the base directory
     */
    public function iCopyThatFileAsInTheBaseDirectory($name)
    {
        $this->result = ['entries' => [$this->files->copy($this->result['entries'][0]['id'], $this->token, $this->baseId, $name)]];
    }


    /**
     * @When I create a shared link for that file
     * @Given that file has a shared link
     */
    public function iCreateASharedLinkForThatFile()
    {
        $this->files->createSharedLink($this->result['entries'][0]['id'], $this->token, 'open');
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
        $this->files->deleteSharedLink($this->result['entries'][0]['id'], $this->token);
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
        $this->result = $this->files->getTrashed($this->result['entries'][0]['id'], $this->token);
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
        $this->files->deleteTrashed($this->result['entries'][0]['id'], $this->token);
    }

    /**
     * @When I restore that file to the base directory as :name
     */
    public function iRestoreThatFileToTheBaseDirectoryAs($name)
    {
        $this->files->restoreTrashed($this->result['entries'][0]['id'], $this->token, $name, $this->baseId);
    }

    /**
     * @Given I have a remote file named :name with the content :content in the trash
     */
    public function iHaveARemoteFileNamedWithTheContentInTheTrash($name, $content)
    {
        $this->iHaveARemoteFileNamedWithTheContentInTheBaseDirectory($name, $content);
        $this->files->delete($this->result['entries'][0]['id'], $this->token);
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
            $this->result = $this->comments->get($this->result['id'], $this->token);
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
        $this->comments->update($this->result['id'], $this->token, $message);
    }

    /**
     * @When I delete that comment
     */
    public function iDeleteThatComment()
    {
        $this->comments->delete($this->result['id'], $this->token);
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
        $this->result = $this->files->getComments($this->result['entries'][0]['id'], $this->token);
    }

    /**
     * @Then I should receive :count comments
     */
    public function iShouldReceiveComments($count)
    {
        assertEquals($count, $this->result['total_count']);
    }

}
