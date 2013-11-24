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

class Webservice_Data_Document_Hardlink_In extends Webservice_Data_Document_Link {

    public function reverseMap($object, $disableMappingExceptions = false, $idMapper = null) {

        $sourceId = $this->sourceId;
        $this->sourceId = null;

        parent::reverseMap($object, $disableMappingExceptions, $idMapper);


        if ($idMapper) {
            $sourceId = $idMapper->getMappedId("document", $sourceId);
        }

        if ($idMapper) {
            $idMapper->recordMappingFailure($object->getId(), "document", $sourceId);
        }

        $object->setSourceId = $sourceId;
    }


}
