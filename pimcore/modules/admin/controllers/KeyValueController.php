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
 * @copyright  Copyright (c) 2009-2012 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class Admin_KeyValueController extends Pimcore_Controller_Action_Admin
{
    public function deletegroupAction() {
        $id = $this->_getParam("id");

        $config = Object_KeyValue_GroupConfig::getById($id);
        $config->delete();

        $this->_helper->json(array("success" => true));
    }

    public function addgroupAction() {
        $name = $this->_getParam("name");
        $alreadyExist = false;
        $config = Object_KeyValue_GroupConfig::getByName($name);


        if(!$config) {
            $config = new Object_KeyValue_GroupConfig();
            $config->setName($name);
            $config->save();
        }

        $this->_helper->json(array("success" => !$alreadyExist, "id" => $config->getName()));
    }

    public function getgroupAction() {
        $id = $this->_getParam("id");
        $config = Object_KeyValue_GroupConfig::getByName($id);

        $data = array(
            "id" => $id,
            "name" => $config->getName(),
            "description" => $config->getDescription()
        );

        $this->_helper->json($data);
    }



    public function groupsAction() {
        if ($this->_getParam("data")) {
            $dataParam = $this->_getParam("data");
            $data = Zend_Json::decode($dataParam);

            $id = $data["id"];
            $config = Object_KeyValue_GroupConfig::getById($id);

            foreach ($data as $key => $value) {
                if ($key != "id") {
                    $setter = "set" . $key;
                    $config->$setter($value);
                }
            }

            $config->save();

            $this->_helper->json(array("success" => true));
        } else {

            $start = 0;
            $limit = 15;
            $orderKey = "name";
            $order = "ASC";

            if ($this->_getParam("dir")) {
                $order = $this->_getParam("dir");
            }

            if ($this->_getParam("sort")) {
                $orderKey = $this->_getParam("sort");
            }

            if ($this->_getParam("limit")) {
                $limit = $this->_getParam("limit");
            }
            if ($this->_getParam("start")) {
                $start = $this->_getParam("start");
            }

            if ($this->_getParam("overrideSort") == "true") {
                $orderKey = "id";
                $order = "DESC";
            }

            $list = new Object_KeyValue_GroupConfig_List();

            $list->setLimit($limit);
            $list->setOffset($start);
            $list->setOrder($order);
            $list->setOrderKey($orderKey);


            if($this->_getParam("filter")) {
                $condition = "";
                $filterString = $this->_getParam("filter");
                $filters = json_decode($filterString);

                $db = Pimcore_Resource::get();
                $count = 0;

                foreach($filters as $f) {
                    if ($count > 0) {
                        $condition .= " OR ";
                    }
                    $count++;
                    $condition .= $db->getQuoteIdentifierSymbol() . $f->field . $db->getQuoteIdentifierSymbol() . " LIKE " . $db->quote("%" . $f->value . "%");
                }
                $list->setCondition($condition);
            }

            $list->load();
            $configList = $list->getList();

            $rootElement = array();

            $data = array();
            foreach($configList as $config) {
                $name = $config->getName();
                if (!$name) {
                    $name = "EMPTY";
                }
                $data[] = array(
                    "id" => $config->getId(),
                    "name" => $name,
                    "description" => $config->getDescription()
                );
            }
            $rootElement["data"] = $data;
            $rootElement["success"] = true;
            $rootElement["total"] = $list->getTotalCount();
            return $this->_helper->json($rootElement);
        }
    }


    public function propertiesAction() {
        if ($this->_getParam("data")) {
            $dataParam = $this->_getParam("data");
            $data = Zend_Json::decode($dataParam);

            $id = $data["id"];
            $config = Object_KeyValue_KeyConfig::getById($id);

            foreach ($data as $key => $value) {
                if ($key != "id") {
                    $setter = "set" . $key;
                    if (method_exists($config, $setter)) {
                        $config->$setter($value);
                    }
                }
            }

            $config->save();

            $this->_helper->json(array("success" => true));
        } else {

            $start = 0;
            $limit = 15;
            $orderKey = "name";
            $order = "ASC";

            if ($this->_getParam("dir")) {
                $order = $this->_getParam("dir");
            }

            if ($this->_getParam("sort")) {
                $orderKey = $this->_getParam("sort");
            }

            if ($this->_getParam("overrideSort") == "true") {
                $orderKey = "id";
                $order = "DESC";
            }

            if ($this->_getParam("limit")) {
                $limit = $this->_getParam("limit");
            }
            if ($this->_getParam("start")) {
                $start = $this->_getParam("start");
            }

            $list = new Object_KeyValue_KeyConfig_List();

            if ($limit > 0) {
                $list->setLimit($limit);
            }
            $list->setOffset($start);
            $list->setOrder($order);
            $list->setOrderKey($orderKey);

            if($this->_getParam("filter")) {
                $db = Pimcore_Resource::get();
                $condition = "";
                $filterString = $this->_getParam("filter");
                $filters = json_decode($filterString);

                $count = 0;

                foreach($filters as $f) {
                    if ($count > 0) {
                        $condition .= " OR ";
                    }
                    $count++;
                    $condition .= $db->getQuoteIdentifierSymbol() . $f->field . $db->getQuoteIdentifierSymbol() . " LIKE " . $db->quote("%" . $f->value . "%");
                }


                $list->setCondition($condition);
            }

            if ($this->_getParam("groupIds") || $this->_getParam("keyIds")) {
                $db = Pimcore_Resource::get();

                if ($this->_getParam("groupIds")) {
                    $ids = Zend_Json::decode($this->_getParam("groupIds"));
                    $col = "group";
                } else {
                    $ids = Zend_Json::decode($this->_getParam("keyIds"));
                    $col = "id";
                }

                $condition = $db->getQuoteIdentifierSymbol() . $col . $db->getQuoteIdentifierSymbol() . " IN (";
                $count = 0;
                foreach ($ids as $theId) {
                    if ($count > 0) {
                        $condition .= ",";
                    }
                    $condition .= $theId;
                    $count++;
                }

                $condition .= ")";
                $list->setCondition($condition);
            }

            $list->load();
            $configList = $list->getList();

            $rootElement = array();

            $data = array();
            foreach($configList as $config) {
                $name = $config->getName();
                if (!$name) {
                    $name = "EMPTY";
                }

                $groupDescription = null;
                if ($config->getGroup()) {
                    try {
                        $group = Object_KeyValue_GroupConfig::getById($config->getGroup());
                        $groupDescription = $group->getDescription();
                        $groupName = $group->getName();
                    } catch (Exception $e) {

                    }

                    if (empty($groupDescription)) {
                        $groupDescription = $group->getName();
                    }
                }

                $data[] = array(
                    "id" => $config->getId(),
                    "name" => $name,
                    "description" => $config->getDescription(),
                    "type" => $config->getType(),
                    "unit" => $config->getUnit(),
                    "possiblevalues" => $config->getPossibleValues(),
                    "group" => $config->getGroup(),
                    "groupdescription" => $groupDescription,
                    "groupName" => $groupName,
                    "translator" => $config->getTranslator()
                );
            }
            $rootElement["data"] = $data;
            $rootElement["success"] = true;
            $rootElement["total"] = $list->getTotalCount();
            return $this->_helper->json($rootElement);
        }
    }

    public function addpropertyAction() {
        $name = $this->_getParam("name");
        $alreadyExist = false;
//
//        try {
//            $config = Object_KeyValue_KeyConfig::getByName($name);
//            $alreadyExist = true;
//        } catch (Exception $e) {
//            $alreadyExist = false;
//        }

        if(!$alreadyExist) {
            $config = new Object_KeyValue_KeyConfig();
            $config->setName($name);
            $config->setType("text");
            $config->save();
        }

        $this->_helper->json(array("success" => !$alreadyExist, "id" => $config->getName()));
    }

    public function deletepropertyAction() {
        $id = $this->_getParam("id");

        $config = Object_KeyValue_KeyConfig::getById($id);
        $config->delete();

        $this->_helper->json(array("success" => true));
    }

    /**
     * Exports group and key config into XML format.
     */
    public function exportAction() {
        $this->removeViewRenderer();

        $data = Object_KeyValue_Helper::export();
        header("Content-type: application/xml");
        header("Content-Disposition: attachment; filename=\"keyvalue_export.xml\"");
        echo $data;
    }


    public function testmagicAction() {
        $obj = Object_Concrete::getById(61071);
        $pairs = $obj->getKeyValuePairs();

        $value = $pairs->getab123();
        Logger::debug("value=" . $value);

        $pairs->setab123("new valuexyz");
        $pairs->setdddd("dvalue");
        $obj->save();
    }

    public function getTranslatorConfigsAction() {
        $list = new Object_KeyValue_TranslatorConfig_List();
        $list->load();
        $items = $list->getList();
        $result = array();
        foreach ($items as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "name" => $item->getName(),
                "translator" => $item->getTranslator()
            );
        }

        $this->_helper->json(array("configurations" => $result));
    }

    public function translateAction() {
        $success = false;
        $keyId = $this->getParam("keyId");
        $objectId = $this->getParam("objectId");
        $recordId = $this->getParam("recordId");
        $text = $this->getParam("text");
        $translatedValue = $text;

        try {
            $keyConfig = Object_KeyValue_KeyConfig::getById($keyId);
            $translatorID = $keyConfig->getTranslator();
            $translatorConfig = Object_KeyValue_TranslatorConfig::getById($translatorID);
            $className = $translatorConfig->getTranslator();
            if (Pimcore_Tool::classExists($className)) {
                $translator = new $className();
                $translatedValue = $translator->translate($text);
                if (!$translatedValue) {
                    $translatedValue = $text;
                }
            }

            $this->_helper->json(array("success" => true,
                "keyId" => $this->getParam("keyId"),
                "text" => $text,
                "translated" => $translatedValue,
                "recordId" => $recordId
            ));
        } catch (Exception $e) {

        }

        $this->_helper->json(array("success" => $success));
    }

}
