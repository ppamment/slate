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

class Tool_Tracking_Event extends Pimcore_Model_Abstract {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $category;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $label;

    /**
     * @var int
     */
    public $timestamp;

    /**
     * @var string
     */
    public $data;

    /**
    * @param integer $id
    * @return Tool_Tracking_Event
    */
    public static function getById($id) {
        $event = new self();
        $event->getResource()->getById(intval($id));

        return $event;
    }

    /**
     * @param $category
     * @param $action
     * @param $label
     * @param $day
     * @param $month
     * @param $year
     */
    public static function getByDate($category, $action, $label, $day, $month, $year) {
        $event = new self();
        try {
            $event->getResource()->getByDate($category, $action, $label, $day, $month, $year);
        } catch (Exception $e) {
            $event->setTimestamp(mktime(1,0,0,$month, $day, $year));
            $event->setCategory($category);
            $event->setAction($action);
            $event->setLabel($label);
        }

        return $event;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }



}
