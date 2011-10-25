<?php
class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \A\Config\Config
     */
    protected $_config;

    public function setUp()
    {
        parent::setUp();
        $this->_config = new \A\Config();
    }

    public function testType()
    {
        $this->assertInstanceOf('\A\Config\Config', $this->_config);
    }

    public function testSetGetVariable()
    {
        $varName = 'variable';
        $varValue = 25;

        $this->_config->$varName = $varValue;

    }
}