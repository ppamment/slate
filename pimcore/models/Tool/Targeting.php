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
 * @package    Document
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class Tool_Targeting extends Pimcore_Model_Abstract {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description = "";

    /**
     * @var array
     */
    public $conditions = array();

    /**
     * @var Tool_Targeting_Actions
     */
    public $actions;

    /**
     * @param int|Tool_Targeting $targetId
     * @return bool
     */
    public static function inTarget($target) {
        if($target instanceof Tool_Targeting) {
            $targetId = $target->getId();
        } else if (is_string($target)) {
            $target = self::getByName($target);
            if(!$target) {
                return false;
            } else {
                $targetId = $target->getId();
            }
        } else {
            $targetId = (int) $target;
        }

        if(array_key_exists("_ptc", $_GET) && intval($targetId) == intval($_GET["_ptc"])) {
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function fireEvent ($key, $value = null) {
        if($value === null) {
            $value = true;
        }

        $front = Zend_Controller_Front::getInstance();
        $plugin = $front->getPlugin("Pimcore_Controller_Plugin_Targeting");
        if($plugin instanceof Pimcore_Controller_Plugin_Targeting) {
            $plugin->addEvent($key, $value);
        }
    }

    /**
     * Static helper to retrieve an instance of Tool_Targeting by the given ID
     *
     * @param integer $id
     * @return Tool_Targeting
     */
    public static function getById($id) {
        try {
            $target = new self();
            $target->setId(intval($id));
            $target->getResource()->getById();
            return $target;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Static helper to retrieve an instance of Tool_Targeting by the given name
     * @param integer $id
     * @return Tool_Targeting
     */
    public static function getByName($name) {
        try {
            $target = new self();
            $target->setName($name);
            $target->getResource()->getByName();
            return $target;
        } catch (Exception $e) {
            return null;
        }
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param \Tool_Targeting_Actions $actions
     */
    public function setActions($actions)
    {
        if(!$actions) {
            $actions = new Tool_Targeting_Actions();
        }
        $this->actions = $actions;
        return $this;
    }

    /**
     * @return \Tool_Targeting_Actions
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $conditions
     */
    public function setConditions($conditions)
    {
        if(!$conditions) {
            $conditions = array();
        }
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
