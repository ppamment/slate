<?php
class Object_KeyValue_KeyConfig_Resource extends Pimcore_Model_Resource_Abstract {

    const TABLE_NAME_KEYS = "keyvalue_keys";

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
        $this->validColumns = $this->getValidTableColumns(self::TABLE_NAME_KEYS);
    }

    /**
     * Get the data for the object from database for the given id, or from the ID which is set in the object
     *
     * @param integer $id
     * @return void
     */
    public function getById($id = null) {

        if ($id != null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchRow("SELECT * FROM " . self::TABLE_NAME_KEYS . " WHERE id = ?", $this->model->getId());

        $this->assignVariablesToModel($data);
    }

    public function getByName($name = null) {

        if ($name != null) {
            $this->model->setName($name);
        }

        $name = $this->model->getName();
        $groupId = $this->model->getGroup();

        $stmt = "SELECT * FROM " . self::TABLE_NAME_KEYS . " WHERE name = '" . $name . "'";
        if ($groupId > 0) {
            $stmt .= " AND `group` = " . $groupId;
        }

        $data = $this->db->fetchRow($stmt);

        if($data["id"]) {
            $this->assignVariablesToModel($data);
        } else {
            throw new Exception("KeyConfig with name: " . $this->model->getName() . " does not exist");
        }
    }


    /**
     * Save object to database
     *
     * @return void
     */
    public function save() {
        if ($this->model->getId()) {
            return $this->model->update();
        }
        return $this->create();
    }

    /**
     * Deletes object from database
     *
     * @return void
     */
    public function delete() {
        $this->db->delete(self::TABLE_NAME_KEYS, $this->db->quoteInto("id = ?", $this->model->getId()));
    }

    /**
     * Save changes to database, it's an good idea to use save() instead
     *
     * @return void
     */
    public function update() {
        try {
            $type = get_object_vars($this->model);

            foreach ($type as $key => $value) {
                if (in_array($key, $this->validColumns)) {
                    if(is_bool($value)) {
                        $value = (int) $value;
                    }
                    if(is_array($value) || is_object($value)) {
                        $value = serialize($value);
                    }

                    $data[$key] = $value;
                }
            }

            $this->db->update(self::TABLE_NAME_KEYS, $data, $this->db->quoteInto("id = ?", $this->model->getId()));
        }
        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new record for the object in database
     *
     * @return boolean
     */
    public function create() {
        $this->db->insert(self::TABLE_NAME_KEYS, array());

        $this->model->setId($this->db->lastInsertId());

        return $this->save();
    }
}
