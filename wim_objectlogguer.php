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

        $this->displayName = $this->l('wim_objectlogguer');
        $this->description = $this->l('Este es mi primer modulo');
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
            $this->registerHook('displayhome') &&
            //OTRO HOOK
            $this->registerHook('actionObject');


        
    }
} 