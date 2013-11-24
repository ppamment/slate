<?php

class Object_KeyValue_KeyConfig extends Pimcore_Model_Abstract {

    /**
     * @var integer
     */
    public $id;

    /** The key
     * @var string
     */
    public $name;

    /** The key description.
     * @var
     */
    public $description;

    /** The key type ("text", "number", etc...)
     * @var
     */
    public $type;

    /** Unit information (just for information)
     * @var
     */
    public $unit;

    /** The group id.
     * @var
     */
    public $group;

    /** Array of possible vales ("select" datatype)
     * @var
     */
    public $possiblevalues;

    /**
     * @var
     */
    public $translator;

    /** Sets the translator id.
     * @param $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /** Returns the translator id.
     * @return mixed
     */
    public function getTranslator()
    {
        return $this->translator;
    }


    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setPossibleValues($values)
    {
        $this->possiblevalues = $values;
        return $this;
    }

    public function getPossibleValues()
    {
        return $this->possiblevalues;
    }



    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }


    /**
     * @param integer $id
     * @return Object_KeyValue_KeyConfig
     */
    public static function getById($id) {
        try {

            $config = new self();
            $config->setId(intval($id));
            $config->getResource()->getById();

            return $config;
        } catch (Exception $e) {

        }
    }


    public static function getByName ($name, $groupId = null) {
        try {
            $config = new self();
            $config->setName($name);
            $config->setGroup($groupId);
            $config->getResource()->getByName();

            return $config;
        } catch (Exception $e) {

        }
    }


    /**
     * @return Object_KeyValue_KeyConfig
     */
    public static function create() {
        $config = new self();
        $config->save();

        return $config;
    }


    /**
     * @param integer $id
     * @return void
     */
    public function setId($id) {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /** Returns the key description.
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /** Sets the key description
     * @param $description
     * @return Object_KeyValue_KeyConfig
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }


    /**
     * Deletes the key value key configuration
     */
    public function delete() {
        Pimcore_API_Plugin_Broker::getInstance()->preDeleteKeyValueKeyConfig($this);
        parent::delete();
        Pimcore_API_Plugin_Broker::getInstance()->postDeleteKeyValueKeyConfig($this);
    }

    /**
     * Saves the key config
     */
    public function save() {
        $isUpdate = false;

        if ($this->getId()) {
            $isUpdate = true;
            Pimcore_API_Plugin_Broker::getInstance()->preUpdateKeyValueKeyConfig($this);
        } else {
            Pimcore_API_Plugin_Broker::getInstance()->preAddKeyValueKeyConfig($this);
        }

        parent::save();

        if ($isUpdate) {
            Pimcore_API_Plugin_Broker::getInstance()->postUpdateKeyValueKeyConfig($this);
        } else {
            Pimcore_API_Plugin_Broker::getInstance()->postAddKeyValueKeyConfig($this);
        }
    }
}
