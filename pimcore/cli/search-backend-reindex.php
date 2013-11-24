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

include_once("startup.php");

// clear all data
$db = Pimcore_Resource::get();
$db->query("TRUNCATE `search_backend_data`;");

$elementsPerLoop = 100;
$types = array("asset","document","object");

foreach ($types as $type) {
    $listClassName = ucfirst($type) . "_List";
    $list = new $listClassName();
    $elementsTotal = $list->getTotalCount();

    for($i=0; $i<(ceil($elementsTotal/$elementsPerLoop)); $i++) {
        $list->setLimit($elementsPerLoop);
        $list->setOffset($i*$elementsPerLoop);

        echo "Processing " .$type . ": " . ($list->getOffset()+$elementsPerLoop) . "/" . $elementsTotal . "\n";

        $elements = $list->load();
        foreach ($elements as $element) {
            try {
                $searchEntry = Search_Backend_Data::getForElement($element);
                if($searchEntry instanceof Search_Backend_Data and $searchEntry->getId() instanceof Search_Backend_Data_Id ) {
                    $searchEntry->setDataFromElement($element);
                } else {
                    $searchEntry = new Search_Backend_Data($element);
                }

                $searchEntry->save();
            } catch (Exception $e) {
                Logger::err($e);
            }
        }
        Pimcore::collectGarbage();
    }
}

$db->query("OPTIMIZE TABLE search_backend_data;");

