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
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class Pimcore_View_Helper_EditmodeTooltip extends Zend_View_Helper_Abstract {

    protected static $editmodeTooltipsIncrementer = 0;

    protected function getDefaultEditmodeTooltipOptions(){
        return array("autoHide" => true,
                     "title" => null,
                     "icon" => "/pimcore/static/img/icon/information.png"
        );
    }

    protected function getTooltipIdentifier($id){
        return "editmode_tooltip_" . $id;
    }

    /**
     * Displays a information icon
     *
     * @param $html
     * @param null | string $title
     * @param array $options
     * @return string
     */
    public function editmodeTooltip($html,$title = null,$options = array()){
        if($html){
            $options = array_merge($this->getDefaultEditmodeTooltipOptions(),$options);
            self::$editmodeTooltipsIncrementer++;
            $options["target"] = $this->getTooltipIdentifier(self::$editmodeTooltipsIncrementer);
            $options["html"] = $html;

            if(!is_null($title)){
                $options["title"] = $title;
            }

            $s = "<img id='" . $options["target"] . "' src='" . $options["icon"] . "' alt='' class='pimcore_editmode_tooltip' />";
            $s .= "<script type='text/javascript'>new Ext.ToolTip(" . Zend_Json::encode($options) .");</script>";
            return $s;
        }
    }
}