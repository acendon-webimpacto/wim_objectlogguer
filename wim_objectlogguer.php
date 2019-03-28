<?php
if(!defined('_PS_VERSION_'))
    exit;

class Wim_objectlogguer extends Module {
    public function __construct() {
        $this->name = 'wim_objectlogguer';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Adrián Cendón';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();
    }

    public function install() {
        Db::getInstance()->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."objectlogguer`(
                `id_objectlogguer` int(11) AUTO_INCREMENT,
                `affected_object` int(11),
                `action_type` varchar(255),
                `object_type` varchar(255),
                `message` text,
                `date_add` datetime,
                PRIMARY KEY (`id_objectlogguer`)
            ) ENGINE="._MYSQL_ENGINE_."DEFAULT CHARSET=UTF8;"
        );

        return parent::install() && 
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            //HOOK ADD/DELETE/UPDATE
            $this->registerHook('actionObjectAddBefore') &&
            $this->registerHook('actionObjectAddAfter') &&
            $this->registerHook('actionObjectUpdateBefore') &&
            $this->registerHook('actionObjectUpdateAfter') &&
            $this->registerHook('actionObjectDeleteBefore') &&
            $this->registerHook('actionObjectDeleteAfter');        
    }

 
    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionObjectDeleteAfter($params) {
        Db::getInstance()->insert('objectlogguer',array(
            'affected_object' => $params['object']->id, 
            'action_type' => "delete",
            'object_type' =>  get_class($params['object']),
            'message' => "Object with id " . $params['object']->id . " deleted",
            'date_add' => date("Y-m-d H:i:s"),
        ));
    }

    /*public function hookActionObjectDeleteBefore($params) {
        Db::getInstance()->insert('objectlogguer',array(
            'affected_object' => $params['object']->id, 
            'action_type' => "delete",
            'object_type' =>  get_class($params['object']),
            'message' => "Object with id " . $params['object']->id . " deleted",
            'date_add' => date("Y-m-d H:i:s"),
        ));
    }*/

    public function hookActionObjectAddAfter($params) {
        Db::getInstance()->insert('objectlogguer',array(
            'affected_object' => $params['object']->id, 
            'action_type' => "add",
            'object_type' =>  get_class($params['object']),
            'message' => "Object with id " . $params['object']->id . " added",
            'date_add' => date("Y-m-d H:i:s"),
        ));
    }

    /*public function hookActionObjectAddBefore() {
        Db::getInstance()->insert('objectlogguer',array(
            'affected_object' => $params['object']->id, 
            'action_type' => "add",
            'object_type' =>  get_class($params['object']),
            'message' => "Object with id " . $params['object']->id . " added",
            'date_add' => date("Y-m-d H:i:s"),
        ));
    }*/

    public function hookActionObjectUpdateAfter($params) {
        Db::getInstance()->insert('objectlogguer',array(
            'affected_object' => $params['object']->id, 
            'action_type' => "update",
            'object_type' =>  get_class($params['object']),
            'message' => "Object with id " . $params['object']->id . " updated",
            'date_add' => date("Y-m-d H:i:s"),
        ));
    }

    
    /*public function hookActionObjectUpdateBefore($params) {
        Db::getInstance()->insert('objectlogguer',array(
            'affected_object' => $params['object']->id, 
            'action_type' => "update",
            'object_type' =>  get_class($params['object']),
            'message' => "Object with id " . $params['object']->id . " updated",
            'date_add' => date("Y-m-d H:i:s"),
        ));
    }*/

} 
