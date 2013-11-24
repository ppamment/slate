<?php

class Object_KeyValue_TranslatorConfig extends Pimcore_Model_Abstract {

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    public $translator;


    /**
     * @param integer $id
     * @return Object_KeyValue_TranslatorConfig
     */
    public static function getById($id) {
        try {

            $config = new self();
            $config->setId(intval($id));
            $config->getResource()->getById();

            return $config;
        } catch (Exception $e) {
            Logger::warning($e);
        }
    }


    public static function getByName ($name) {
        try {
            $config = new self();
            $config->setName($name);
            $config->getResource()->getByName();

            return $config;
        } catch (Exception $e) {
            Logger::warning($e);
        }
    }


    /**
     * @return Object_KeyValue_TranslatorConfig
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

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

}
