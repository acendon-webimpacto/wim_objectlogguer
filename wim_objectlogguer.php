<?php
/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Adrián Cendón
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once 'classes/ObjectLogger.php';
if (!defined('_PS_VERSION_'))
    exit;

class Wim_ObjectLogguer extends Module {

    public function __construct()
    {
        $this->name = 'wim_objectlogguer';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Adrian_Cendon';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        $this->displayName = 'wim_objectlogguer';
        $this->description = 'Este es mi módulo';

        parent::__construct();
    }

    public function install()
    {
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

    public function accionesBd($params, $event)
    {
        $obj = new ObjectLogger();
        $obj->affected_object = $params['object']->id;
        $obj->action_type = $event;
        $obj->object_type =  get_class($params['object']);
        if ($event == 'add') {
            $obj->message = "Object with id " . $params['object']->id . ' ' . $event . 'ed';
        } else {
            $obj->message = "Object with id " . $params['object']->id . ' ' . $event . 'd';
        }
        $obj->date_add = date("Y-m-d H:i:s");
        if (get_class($params['object']) != 'ObjectLogger') {
            $obj->add();
        }
    }

    public function hookActionObjectDeleteAfter($params)
    {
        $this->accionesBd($params, 'delete');
    }

    public function hookActionObjectAddAfter($params)
    {
        $this->accionesBd($params, 'add');
    }

    public function hookActionObjectUpdateAfter($params)
    {
        $this->accionesBd($params, 'update');
    }

}
?>