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

class Tool_Setup extends Pimcore_Model_Abstract {

    /**
     * @param array $config
     */
    public function config ($config = array()) {
        // write configuration file
        $settings = array(
            "general" => array(
                "timezone" => "Europe/Berlin",
                "language" => "en",
                "validLanguages" => "en",
                "debug" => "1",
                "loginscreenimageservice" => "1",
                "loglevel" => array(
                    "debug" => "1",
                    "info" => "1",
                    "notice" => "1",
                    "warning" => "1",
                    "error" => "1",
                    "critical" => "1",
                    "alert" => "1",
                    "emergency" => "1"
                ),
                "custom_php_logfile" => "1"
            ),
            "database" => array(
                "adapter" => "Mysqli",
                "params" => array(
                    "host" => "localhost",
                    "username" => "root",
                    "password" => "",
                    "dbname" => "",
                    "port" => "3306",
                )
            ),
            "documents" => array(
                "versions" => array(
                    "steps" => "10"
                ),
                "default_controller" => "default",
                "default_action" => "default",
                "error_pages" => array(
                    "default" => "/"
                ),
                "createredirectwhenmoved" => "",
                "allowtrailingslash" => "no",
                "allowcapitals" => "no",
                "generatepreview" => "1"
            ),
            "objects" => array(
                "versions" => array(
                    "steps" => "10"
                )
            ),
            "assets" => array(
                "versions" => array(
                    "steps" => "10"
                )
            ),
            "services" => array(),
            "cache" => array(
                "excludeCookie" => ""
            ),
            "httpclient" => array(
                "adapter" => "Zend_Http_Client_Adapter_Socket"
            )
        );

        $settings = array_replace_recursive($settings, $config);

        // create initial /website/var folder structure
        // @TODO: should use values out of startup.php (Constants)
        $varFolders = array("areas","assets","backup","cache","classes","config","email","log","plugins","recyclebin","search","system","tmp","versions","webdav");
        foreach($varFolders as $folder) {
            @mkdir(PIMCORE_WEBSITE_VAR . "/" . $folder, 0777, true);
        }

        $config = new Zend_Config($settings, true);
        $writer = new Zend_Config_Writer_Xml(array(
            "config" => $config,
            "filename" => PIMCORE_CONFIGURATION_SYSTEM
        ));
        $writer->write();
    }

    /**
     *
     */
    public function contents($config = array()) {

        $defaultConfig = array(
            "username" => "admin",
            "password" => md5(microtime())
        );

        $settings = array_replace_recursive($defaultConfig, $config);

        $this->getResource()->contents();

        $user = User::create(array(
            "parentId" => 0,
            "username" => $settings["username"],
            "password" => Pimcore_Tool_Authentication::getPasswordHash($settings["username"], $settings["password"]),
            "active" => true
        ));
        $user->setAdmin(true);
        $user->save();
    }

}