<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 11:51
 */
class Iwelt_Install_Model_Install_Installer_Db extends Mage_Install_Model_Installer_Db
{
    public function checkDbConnectionData($data)
    {
        $data = $this->_getCheckedData($data);
        $link = mysql_connect($data['db_host'], $data['db_user'], $data['db_pass']);
        mysql_query("CREATE DATABASE IF NOT EXISTS " . $data['db_name'], $link);
        mysql_close($link);
        return parent::checkDbConnectionData($data);
    }

}