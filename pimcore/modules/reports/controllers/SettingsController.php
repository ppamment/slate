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

class Reports_SettingsController extends Pimcore_Controller_Action_Admin_Reports {
    
    public function getAction () {

        $this->checkPermission("system_settings");

        $conf = $this->getConfig();

        $response = array(
            "values" => $conf->toArray(),
            "config" => array()
        );

        $this->_helper->json($response);
    }
    
    public function saveAction () {

        $this->checkPermission("system_settings");

        $values = Zend_Json::decode($this->getParam("data"));

        $config = new Zend_Config($values, true);
        $writer = new Zend_Config_Writer_Xml(array(
            "config" => $config,
            "filename" => PIMCORE_CONFIGURATION_DIRECTORY . "/reports.xml"
        ));
        $writer->write();

        $this->_helper->json(array("success" => true));
    }

    public function cleanupExistingContentAnalysisDataAction() {


        $patterns = explode("\n", $this->getParam("excludePatterns"));

        if(count($patterns) > 0) {
            $service = new Tool_ContentAnalysis_Service();
            $service->cleanupExistingData($patterns);
        }

        $this->_helper->json(array("success" => true));
    }
}
