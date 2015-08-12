<?php
namespace CanalTP\NavitiaIoCoreApiBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use CanalTP\NavitiaIoCoreApiBundle\Entity\User;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements SnippetAcceptingContext, KernelAwareContext
{

    private $user;
    private $response;
    private $kernel;

    public function __construct()
    {
        $this->response = null;
        $this->kernel = null;
        $this->user = null;
    }

    /**
     * @Then I have a JSON response
     */
    public function iHaveAJsonResponse()
    {
        return ($this->response->getHeader('Content-Type') == 'application/json');
    }

    /**
     * @Then I have an array of users
     */
    public function iHaveAnArrayOfUsers()
    {
        $users = $this->response->json();

        return (is_array($users) && count($users) > 0 && $this->checkUserInformation($users[0]));
    }

    /**
     * @Then Response status code should be :httpCode
     */
    public function responseStatusCodeShouldBe($httpCode)
    {
        return ($this->response->getStatusCode() == $httpCode);
    }

    /**
     * @Then I have a user object
     */
    public function iHaveAUserObject()
    {
        $user = $this->response->json();

        return (is_array($user) && $this->checkUserInformation($user));
    }

    private function createUser()
    {
        $manager = $this->kernel->getContainer()->get('doctrine')->getManager();
        $user = new User();

        $user->setUsername($this->getParameter('user_behat_username'));
        $user->setFirstName($this->getParameter('user_behat_firstname'));
        $user->setLastName($this->getParameter('user_behat_lastname'));
        $user->setPassword($this->getParameter('user_behat_password'));
        $user->setEmail($this->getParameter('user_behat_email'));

        $manager->persist($user);
        $manager->flush();

        return $user;
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
        $this->response = $client->get($url, array('auth' =>  array('user_test', 'password_test')));

        return (!empty($this->response));
    }

    /**
     * @When I request :url without authentification
     */
    public function iRequestWithoutAuthentification($url)
    {
        $url = $this->getParameter('selenium_host') . $url;
        $client = $this->getSession()->getDriver()->getClient()->getClient();
        $this->response = $client->get($url, array('auth' =>  array('user_test', 'password_tesst'), 'exceptions' => false));

        return (!empty($this->response));
    }

    /**
     * @BeforeScenario
     */
    public function before(BeforeScenarioScope $scope)
    {
        $this->user = $this->createUser();
    }

    /**
     * @AfterScenario
     */
    public function after(AfterScenarioScope $scope)
    {
        $manager = $this->kernel->getContainer()->get('doctrine')->getManager();

        $manager->remove($this->user);
        $manager->flush();
    }

    private function checkUserInformation(array $user)
    {
        $result = true;

        if ($user['username'] != $this->getParameter('user_behat_username')
            && $user['first_name'] != $this->getParameter('user_behat_firstname')
            && $user['last_name'] != $this->getParameter('user_behat_lastname')
            && $user['email'] != $this->getParameter('user_behat_email')
        ) {
            $result = false;
        }
        return $result;
    }

    private function getParameter($arg)
    {
        return $this->kernel->getContainer()->getParameter($arg);
    }
}
