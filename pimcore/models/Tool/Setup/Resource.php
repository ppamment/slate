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

class Tool_Setup_Resource extends Pimcore_Model_Resource_Abstract {


    public function database () {

        $mysqlInstallScript = file_get_contents(PIMCORE_PATH . "/modules/install/mysql/install.sql");

        // remove comments in SQL script
        $mysqlInstallScript = preg_replace("/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/","",$mysqlInstallScript);

        // get every command as single part
        $mysqlInstallScripts = explode(";",$mysqlInstallScript);

        // execute every script with a separate call, otherwise this will end in a PDO_Exception "unbufferd queries, ..." seems to be a PDO bug after some googling
        foreach ($mysqlInstallScripts as $m) {
            $sql = trim($m);
            if(strlen($sql) > 0) {
                $sql .= ";";
                $this->db->query($m);
            }
        }

        // reset the database connection
        Pimcore_Resource::reset();
    }

    public function contents () {

        $this->db->insert("assets", array(
            "id" => 1,
            "parentId" => 0,
            "type" => "folder",
            "filename" => "",
            "path" => "/",
            "creationDate" => time(),
            "modificationDate" => time(),
            "userOwner" => 1,
            "userModification" => 1
        ));
        $this->db->insert("documents", array(
            "id" => 1,
            "parentId" => 0,
            "type" => "page",
            "key" => "",
            "path" => "/",
            "index" => 999999,
            "published" => 1,
            "creationDate" => time(),
            "modificationDate" => time(),
            "userOwner" => 1,
            "userModification" => 1
        ));
        $this->db->insert("documents_page", array(
            "id" => 1,
            "controller" => "",
            "action" => "",
            "template" => "",
            "title" => "",
            "description" => "",
            "keywords" => ""
        ));
        $this->db->insert("objects", array(
            "o_id" => 1,
            "o_parentId" => 0,
            "o_type" => "folder",
            "o_key" => "",
            "o_path" => "/",
            "o_index" => 999999,
            "o_published" => 1,
            "o_creationDate" => time(),
            "o_modificationDate" => time(),
            "o_userOwner" => 1,
            "o_userModification" => 1
        ));


        $this->db->insert("users", array(
            "parentId" => 0,
            "name" => "system",
            "admin" => 1,
            "active" => 1
        ));
        $this->db->update("users",array("id" => 0), $this->db->quoteInto("name = ?", "system"));


        $userPermissions = array(
            array("key" => "assets"),
            array("key" => "classes"),
            array("key" => "clear_cache"),
            array("key" => "clear_temp_files"),
            array("key" => "document_types"),
            array("key" => "documents"),
            array("key" => "objects"),
            array("key" => "plugins"),
            array("key" => "predefined_properties"),
            array("key" => "routes"),
            array("key" => "seemode"),
            array("key" => "system_settings"),
            array("key" => "thumbnails"),
            array("key" => "translations"),
            array("key" => "redirects"),
            array("key" => "glossary" ),
            array("key" => "reports"),
            array("key" => "document_style_editor"),
            array("key" => "recyclebin"),
            array("key" => "seo_document_editor"),
            array("key" => "robots.txt"),
            array("key" => "http_errors"),
            array("key" => "tag_snippet_management"),
            array("key" => "qr_codes"),
            array("key" => "targeting"),
            array("key" => "notes_events"),
            array("key" => "backup"),
            array("key" => "bounce_mail_inbox"),
            array("key" => "website_settings"),
            array("key" => "newsletter"),
        );
        foreach ($userPermissions as $up) {
            $this->db->insert("users_permission_definitions", $up);
        }
    }
}
