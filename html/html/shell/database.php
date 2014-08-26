<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * Magento Compiler Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Shell_Database extends Mage_Shell_Abstract
{
    protected $user;
    protected $pass;
    protected $name;
    protected $host;
    protected $_includeMage = false;

    public function __construct()
    {
        require_once $this->_getRootPath() . 'app/Mage.php';
        require_once($this->_getRootPath() . 'app/code/core/Mage/Core/Model/Factory.php');
        parent::__construct();
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        $localXml = simplexml_load_file($this->_getRootPath() . "app/etc/local.xml");
        $host = (string) $localXml->global->resources->default_setup->connection->host;
        $user = (string) $localXml->global->resources->default_setup->connection->username;
        $pass = (string) $localXml->global->resources->default_setup->connection->password;
        $name = (string) $localXml->global->resources->default_setup->connection->dbname;

        if ($this->getArg('overwrite')) {
            $file = $this->getFileName('dump.sql');
            $statement = "mysql -u{$user} -p{$pass} -h{$host} {$name} < $file";
            exec($statement);
        } else {
            Mage::app($this->_appCode, $this->_appType);
        }

        if ($this->getArg('dump')) {
            $file = $this->getFileName('dump.sql');

            $excludedTables = $this->getExcludedTablesSuffix($name);

            $statement = " echo \"SET FOREIGN_KEY_CHECKS=0;\" > $file &&
                           mysqldump -u{$user} -p{$pass} -h{$host} --no-data {$name} >> $file &&
                           mysqldump -u{$user} -p{$pass} -h{$host} --no-create-info $excludedTables {$name} >> $file
                           echo \"SET FOREIGN_KEY_CHECKS=1;\" >> $file
                          ";
            echo "$statement\n";
            exec($statement);
        } else if ($this->getArg('export')) {
            $file = $this->getFileName('export.sql');
            $statement = "mysqldump -u{$user} -p{$pass} -h{$host} {$name} > $file";
            exec($statement);
        } else if ($this->getArg('overwrite')) {
        } else if ($this->getArg('config')) {
            $this->setConfig();
        } else {
            echo $this->usageHelp();
        }
    }

    public function getExcludedTablesSuffix($name)
    {
        $suffix = "";
        foreach ($this->getExcludedTables() as $table) {
            $suffix .= "--ignore-table=$name.$table ";
        }
        return $suffix;
    }

    public function getExcludedTables()
    {
        $excludedTables = array(
            'cataloginventory_stock_status_idx',
            'cataloginventory_stock_status_tmp',
            'catalog_category_anc_categs_index_idx',
            'catalog_category_anc_categs_index_tmp',
            'catalog_category_anc_products_index_idx',
            'catalog_category_anc_products_index_tmp',
            'catalog_category_flat_store_1',
            'catalog_category_flat_store_2',
            'catalog_category_flat_store_3',
            'catalog_category_product_index',
            'catalog_category_product_index_enbl_idx',
            'catalog_category_product_index_enbl_tmp',
            'catalog_category_product_index_idx',
            'catalog_category_product_index_tmp',
            'catalog_product_bundle_price_index',
            'catalog_product_bundle_stock_index',
            'catalog_product_enabled_index',
            'catalog_product_flat_1',
            'catalog_product_flat_2',
            'catalog_product_flat_3',
            'catalog_product_index_eav_decimal_idx',
            'catalog_product_index_eav_decimal_tmp',
            'catalog_product_index_eav_idx',
            'catalog_product_index_eav_tmp',
            'catalog_product_index_group_price',
            'catalog_product_index_price',
            'catalog_product_index_price_bundle_idx',
            'catalog_product_index_price_bundle_opt_idx',
            'catalog_product_index_price_bundle_opt_tmp',
            'catalog_product_index_price_bundle_sel_idx',
            'catalog_product_index_price_bundle_sel_tmp',
            'catalog_product_index_price_bundle_tmp',
            'catalog_product_index_price_cfg_opt_agr_idx',
            'catalog_product_index_price_cfg_opt_agr_tmp',
            'catalog_product_index_price_cfg_opt_idx',
            'catalog_product_index_price_cfg_opt_tmp',
            'catalog_product_index_price_downlod_idx',
            'catalog_product_index_price_downlod_tmp',
            'catalog_product_index_price_final_idx',
            'catalog_product_index_price_final_tmp',
            'catalog_product_index_price_idx',
            'catalog_product_index_price_opt_agr_idx',
            'catalog_product_index_price_opt_agr_tmp',
            'catalog_product_index_price_opt_idx',
            'catalog_product_index_price_opt_tmp',
            'catalog_product_index_price_tmp',
            'catalog_product_index_tier_price',
            'catalog_product_index_website',
            'core_url_rewrite',
            'log_customer',
            'log_quote',
            'log_summary',
            'log_summary_type',
            'log_url',
            'log_url_info',
            'log_visitor',
            'log_visitor_info',
            'log_visitor_online',
            'api_session',
            'core_session',
            'dataflow_session',
            'persistent_session',
        );

        foreach ($excludedTables as $key => $table) {
            $excludedTables[$key] = Mage::getSingleton('core/resource')->getTableName($table);
        }
        return $excludedTables;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f database.php -- [options]

  dump                          Dump a predefined selection of tables
  export                        Dump all tables. Full dump.
  overwrite                     Reverts the dump-Database.
  config                        Updates config node
                                -path "my/path" -value "my_value"
  help                          This help

USAGE;
    }

    private function getMysqlDir()
    {
        return dirname($this->_getRootPath()) . "/mysqldump";
    }

    private function getFileName($string)
    {
        return "{$this->getMysqlDir()}/$string";
    }

    public function setConfig()
    {
        $path = $this->getArg('path');
        $value = $this->getArg('value');
        if (($path && $value) && !is_bool($path) && !is_bool($value)) {
            if (!is_null(Mage::getStoreConfig($path))) {
                Mage::app()->getConfig()->saveConfig($path, $value);
            } else {
                echo "Config not saved: Node '$path' not found\n";
            }
        } else {
            echo "Config not saved: " . (!$path ? "Path" : "Value for '$path'") . " not specified";
        }
    }
}

$shell = new Mage_Shell_Database();
$shell->run();

