<?php

class Object_KeyValue_GroupConfig_List_Resource extends Pimcore_Model_List_Resource_Abstract {

    /**
     * Loads a list of keyvalue group configs for the specifies parameters, returns an array of config elements
     *
     * @return array
     */
    public function load() {
        $sql = "SELECT id FROM " . Object_KeyValue_GroupConfig_Resource::TABLE_NAME_GROUPS . $this->getCondition() . $this->getOrder() . $this->getOffsetLimit();
        $configsData = $this->db->fetchCol($sql,  $this->model->getConditionVariables());

        $configData = array();
        foreach ($configsData as $config) {
            $configData[] = Object_KeyValue_GroupConfig::getById($config);
        }

        $this->model->setList($configData);
        return $configData;
    }

    public function getDataArray() {
        $configsData = $this->db->fetchAll("SELECT * FROM " . Object_KeyValue_GroupConfig_Resource::TABLE_NAME_GROUPS . $this->getCondition() . $this->getOrder() . $this->getOffsetLimit(), $this->model->getConditionVariables());
        return $configsData;
    }

    public function getTotalCount() {

        try {
            $amount = (int) $this->db->fetchOne("SELECT COUNT(*) as amount FROM " . Object_KeyValue_GroupConfig_Resource::TABLE_NAME_GROUPS . " ". $this->getCondition(), $this->model->getConditionVariables());
        } catch (Exception $e) {

        }

        return $amount;
    }
}
