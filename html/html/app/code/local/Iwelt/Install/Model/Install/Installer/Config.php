<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 11:06
 */
class Iwelt_Install_Model_Install_Installer_Config extends Mage_Install_Model_Installer_Config
{
    public function install()
    {
        parent::install();
        $config = Mage::getSingleton('install/installer')->getDataModel()->getConfigData();
        if ($config['install_sample']) {

            $cmd = false;
            $mysqlPath = $config['install_sample_mysql_path'];

            $commands = array(
                $mysqlPath,
                'mysql',
                '/usr/bin/mysql',
                '/usr/local/bin/mysql'
            );
            foreach ($commands as $path) {
                if (exec("which $path")) {
                    $cmd = $path;
                    break;
                }
            }

            if ($cmd) {
                $resources = Mage::getModuleDir('data', 'Iwelt_Install') . DS . 'resources';
                $dump = $resources . DS . 'magento_sample_data_for_1.9.0.0.sql';
                $media = $resources . DS . 'media';
                if (file_exists($dump) && file_exists($media)) {
                    $user = $config['db_user'];
                    $pass = $config['db_pass'];
                    $host = $config['db_host'];
                    $dbName = $config['db_name'];

                    exec("$cmd -u$user -p$pass -h$host $dbName < $dump", $output = array(), $worked);
                    $mediaDir = Mage::getBaseDir('media');
                    exec("mkdir -p $mediaDir && cp -R $media/* $mediaDir");
                }
            } else {

            }
        }
    }

}