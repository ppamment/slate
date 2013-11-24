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
 * @package    Webservice
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

abstract class Webservice_Data {

    public function map($object) {
        $keys = get_object_vars($this);
        $blockedKeys = array("childs");
        foreach ($keys as $key => $value) {

                $method = "get" . $key;
                if (method_exists($object, $method) && !in_array($key, $blockedKeys)) {
                    if ($object->$method()) {
                        $this->$key = $object->$method();

                        // check for a pimcore data type
                        if ($this->$key instanceof Element_Interface) {
                            $this->$key = $this->$key->getId();
                        }

                        // if the value is an object or array call the mapper again for the value
                        if (is_object($this->$key) || is_array($this->$key)) {
                            $type = "out";
                            if (strpos(get_class($this), "_In")!==FALSE) {
                                $type = "in";
                            }
                            $className = Webservice_Data_Mapper::findWebserviceClass($this->$key, "out");
                            $this->$key = Webservice_Data_Mapper::map($this->$key, $className, $type);
                        }


                    }
                }


        }


    }

    private function mapProperties($value) {
        if (is_array($value)) {
            $result = array();

            foreach ($value as $property) {
                if ($property instanceof stdClass) {
                    $newProperty = new Property();
                    $vars = get_object_vars($property);
                    foreach ($vars as $varName => $varValue) {
                        $newProperty->$varName = $property->$varName;
                    }
                    $result[] = $newProperty;
                } else {
                    $result[] = $property;
                }
            }
            $value = $result;

        }
        return $value;
    }

    public function reverseMap($object, $disableMappingExceptions = false, $idMapper = null) {

        $keys = get_object_vars($this);
        foreach ($keys as $key => $value) {
            $method = "set" . $key;
            if (method_exists($object, $method)) {
                if ($object instanceof Element_Interface && $key == "properties") {
                    $value = $this->mapProperties($value);
                }
                $object->$method($value);
            }
        }

        if ($object instanceof Element_Interface) {
            // Classes do not have properties
            $object->setProperties(null);
        }

        if (is_array($this->properties)) {
            foreach ($this->properties as $propertyWs) {

                $propertyWs = (array) $propertyWs;

                $dat = $propertyWs["data"];
                $type = $propertyWs["type"];
                if (in_array($type, array("object", "document", "asset"))) {

                    $id = $propertyWs["data"];
                    $type = $propertyWs["type"];
                    $dat = null;
                    if ($idMapper) {
                        $id = $idMapper->getMappedId($type, $id);
                    }

                    if ($id) {
                        $dat = Element_Service::getElementById($type, $id);
                    }

                    if (is_numeric($propertyWs["data"]) and !$dat) {
                        if (!$idMapper || !$idMapper->ignoreMappingFailures()) {
                            throw new Exception("cannot import property [ " . $type . " ] because it references unknown " . $propertyWs["data"]);
                        } else {
                            $idMapper->recordMappingFailure($object->getId(), $type, $propertyWs["data"]);
                        }
                    }
                } else if ($type == "date"){
                    $dat = new Pimcore_Date(strtotime($propertyWs["data"]));
                } else {
                    $dat = $propertyWs["data"];
                }


                $object->setProperty($propertyWs["name"], $propertyWs["type"], $dat, $propertyWs["inherited"]);
            }
        }
    }
}
