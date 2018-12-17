<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use \Behat\Mink\Element\NodeElement;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenarioResize()
    {
        if ($this->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver)
        {
            $this->getSession()->resizeWindow(1440, 900, 'current');
        }
    }

    /**
     * @Given I submit the field :field
     *
     * @param $field
     */
    public function iSubmitField($field)
    {
        $input = $this->getSession()->getPage()->find('named', [ 'id_or_name', $field]);
        $input->submit();
    }

    /**
     * @Then I click element :element
     *
     * @param $element
     */
    public function iClickElement($element)
    {
        $click = $this->getSession()->getPage()->find('named', ['id_or_name' , $element]);
        $click->click();
    }

    /**
     * @Then I execute scenario :scenario
     *
     * @param string $scenario
     *
     * @return bool
     *
     * @throws ErrorException
     */
    public function iExecuteScenario($scenario)
    {
        $output = shell_exec('vendor/bin/behat --name="' . escapeshellarg($scenario) . '"');
        $validation = strpos($output,"Mink\\Exception");

        if($validation)
        {
            throw new ErrorException("Scenario throws an error");
        }

        return true;
    }

    /**
     * @When I wait :seconds seconds for request to complete
     *
     * @param mixed $seconds
     */
    public function iWaitSecondsForRedirect($seconds)
    {
        $this->getSession()->wait(
            $seconds * 1000
        );
    }

    /**
     * @Then I focus on :field
     *
     * @param $field
     */
    public function iFocusOn($field)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $page->find("named", array("id_or_name", $field))->focus();
    }

}
