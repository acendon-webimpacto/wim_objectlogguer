<?php
require_once 'classes/ObjectLogger.php';
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

        $this->displayName = 'wim_objectlogguer';

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
            //HOOK ADD/UPDATE/DELETE
            $this->registerHook('actionObjectAddAfter') &&
            $this->registerHook('actionObjectUpdateAfter') &&
            $this->registerHook('actionObjectDeleteAfter');        
    }

 
    public function uninstall()
    {
        return parent::uninstall();
    }

    public function accionesBd($params, $event) {
        $obj = new ObjectLogger();
        $obj->affected_object = $params['object']->id;
        $obj->action_type = $event;
        $obj->object_type =  get_class($params['object']);
        if($event == 'add') {
            $obj->message = "Object with id " . $params['object']->id . ' ' . $event . 'ed';
        } else {
            $obj->message = "Object with id " . $params['object']->id . ' ' . $event . 'd';
        } 
        $obj->date_add = date("Y-m-d H:i:s");
        if(get_class($params['object']) != 'ObjectLogger') {
            $obj->add();
        }
    }

    public function hookActionObjectDeleteAfter($params) {
        $this->accionesBd($params, 'delete');
    }

    public function hookActionObjectAddAfter($params) {
        $this->accionesBd($params, 'add');
    }

    public function hookActionObjectUpdateAfter($params) {
        $this->accionesBd($params, 'update');
    }

} 
