<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use CanalTP\NavitiaIoUserBundle\Entity\User;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements SnippetAcceptingContext, KernelAwareContext
{
    private $users;
    private $response;
    private $kernel;

    public function __construct()
    {
        $this->response = null;
        $this->kernel = null;
        $this->users = array();
    }

    private function assert($isValid, $msg)
    {
        if (!$isValid) {
            throw new \Exception($msg);
        }
    }

    /**
     * @Then I have a :contentType response
     */
    public function iHaveAJsonResponse($contentType)
    {
        $this->assert(
            ($this->response->getHeader('Content-Type') == $contentType),
            'Content-Type of this response is not correct'
        );
    }

    /**
     * @Then Response status code should be :httpCode
     */
    public function responseStatusCodeShouldBe($httpCode)
    {
        $this->assert(
            ($this->response->getStatusCode() == $httpCode),
            'Bad response code'
        );
    }

    /**
     * @Then I have a user object
     */
    public function iHaveAUserObject()
    {
        $properties = array('username', 'first_name', 'last_name', 'email', 'project_type', 'keys');
        $response = $this->response->json();

        $this->assert(
            is_array($response['users']),
            'User object not found'
        );
        foreach ($properties as $property) {
            $this->assert(
                array_key_exists($property, $response['users']),
                'User object exist but doesn\'t have ' . $property . ' property'
            );
        }
    }

    public function setKernel(KernelInterface $kernelInterface)
    {
        $this->kernel = $kernelInterface;
    }

    /**
     * @When I request :url
     */
    public function iRequest($url)
    {
        $url = $this->getParameter('selenium_host') . $url;
        $client = $this->getSession()->getDriver()->getClient()->getClient();
        $this->response = $client->get($url, array('auth' =>  array('toto', 'toto42')));

        $this->assert(
            !empty($this->response),
            'No Response found'
        );
    }

    /**
     * @When I request :url without authentification
     */
    public function iRequestWithoutAuthentification($url)
    {
        $url = $this->getParameter('selenium_host') . $url;
        $client = $this->getSession()->getDriver()->getClient()->getClient();
        $this->response = $client->get($url, array('auth' =>  array('user_test', 'password_tesst'), 'exceptions' => false));

        $this->assert(
            !empty($this->response),
            'No Response found'
        );
    }

    /**
     * @Given The following people exist:
     */
    public function theFollowingPeopleExist(TableNode $table)
    {
        $hash = $table->getHash();

        foreach ($hash as $row) {
            $this->users[$row['username']] = $this->createUser($row);
        }
    }

    /**
     * @Then I should have :number users
     */
    public function iShouldHaveUsers($number)
    {
        $response = $this->response->json();

        $this->assert(
            (array_key_exists('users', $response)),
            '"users" property not found in response'
        );

        $this->assert(
            (count($response['users']) == $number),
            'Bad user number'
        );
    }

    /**
     * @AfterScenario
     */
    public function after(AfterScenarioScope $scope)
    {
        $this->removeAllUsers();
    }

    private function getParameter($arg)
    {
        return $this->kernel->getContainer()->getParameter($arg);
    }

    private function removeAllUsers()
    {
        $manager = $this->kernel->getContainer()->get('doctrine')->getManager();

        foreach ($this->users as $user) {
            $manager->remove($user);
        }
        $manager->flush();
    }

    private function createUser($data)
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $user = new User();

        $user->setUsername($data['username']);
        $user->setFirstname($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setProjectType($data['project_type']);
        $user->setCompany($data['company']);
        $user->setWebsite($data['website']);

        $em->persist($user);
        $em->flush();

        return $user;
    }
}
