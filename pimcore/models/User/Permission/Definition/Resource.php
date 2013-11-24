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
 * @package    User
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class User_Permission_Definition_Resource extends Pimcore_Model_Resource_Abstract {
    /**
     * Contains all valid columns in the database table
     *
     * @var array
     */
    protected $validColumns = array();

    /**
     * Get the valid columns from the database
     *
     * @return void
     */
    public function init() {
        $this->validColumns = $this->getValidTableColumns("users_permission_definitions");
    }


    /**
     *
     */
    public function save() {
        try {
            $this->db->insert("users_permission_definitions", array(
                "key" => $this->model->getKey()
            ));
        } catch (Exception $e) {
            Logger::warn($e);
        }
    }
}
