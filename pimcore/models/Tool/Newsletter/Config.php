<?php
/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @category   Pimcore
 * @package    Tool
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */
 
class Tool_Newsletter_Config {

    /**
     * @var string
     */
    public $name = "";

    /**
     * @var string
     */
    public $description = "";

    /**
     * @var int
     */
    public $document;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $objectFilterSQL;

    /**
     * @var string
     */
    public $testEmailAddress;

    /**
     * @var bool
     */
    public $googleAnalytics = true;

    /**
     * @static
     * @param  $name
     * @return Tool_Newsletter_Config
     */
    public static function getByName ($name) {
        $letter = new self();
        $letter->setName($name);
        if(!$letter->load()) {
            throw new Exception("newsletter definition : " . $name . " does not exist");
        }

        return $letter;
    }

    /**
     * @static
     * @return string
     */
    public static function getWorkingDir () {
        $dir = PIMCORE_CONFIGURATION_DIRECTORY . "/newsletter";
        if(!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    /**
     * @return string
     */
    public function getPidFile() {
        return PIMCORE_SYSTEM_TEMP_DIRECTORY . "/newsletter__" . $this->getName() . ".pid";
    }

    /**
     * @return void
     */
    public function save () {

        $arrayConfig = object2array($this);

        $config = new Zend_Config($arrayConfig);
        $writer = new Zend_Config_Writer_Xml(array(
            "config" => $config,
            "filename" => $this->getConfigFile()
        ));
        $writer->write();

        return true;
    }

    /**
     * @return void
     */
    public function load () {

        $configXml = new Zend_Config_Xml($this->getConfigFile());
        $configArray = $configXml->toArray();

        foreach ($configArray as $key => $value) {
            $setter = "set" . ucfirst($key);
            if(method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function delete() {
        if(is_file($this->getConfigFile())) {
            unlink($this->getConfigFile());
        }
    }

    /**
     * @return string
     */
    protected function getConfigFile () {
        return self::getWorkingDir() . "/" . $this->getName() . ".xml";
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }

    /**
     * @return int
     */
    public function getDocument()
    {
        return $this->document;
    }



    /**
     * @param boolean $googleAnalytics
     */
    public function setGoogleAnalytics($googleAnalytics)
    {
        $this->googleAnalytics = (bool) $googleAnalytics;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getGoogleAnalytics()
    {
        return $this->googleAnalytics;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $objectFilterSQL
     */
    public function setObjectFilterSQL($objectFilterSQL)
    {
        $this->objectFilterSQL = $objectFilterSQL;
    }

    /**
     * @return string
     */
    public function getObjectFilterSQL()
    {
        return $this->objectFilterSQL;
    }

    /**
     * @param string $testEmailAddress
     */
    public function setTestEmailAddress($testEmailAddress)
    {
        $this->testEmailAddress = $testEmailAddress;
    }

    /**
     * @return string
     */
    public function getTestEmailAddress()
    {
        return $this->testEmailAddress;
    }


}