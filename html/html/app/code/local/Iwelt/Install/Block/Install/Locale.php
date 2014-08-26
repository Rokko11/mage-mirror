<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 10:13
 */
class Iwelt_Install_Block_Install_Locale extends Mage_Install_Block_Locale
{
    protected function _construct()
    {
        parent::_construct();
        if (!Mage::getSingleton('install/session')->getTimezone())
            Mage::getSingleton('install/session')->setTimezone('Europe/Berlin');
        if (!Mage::getSingleton('install/session')->getCurrency())
            Mage::getSingleton('install/session')->setCurrency('EUR');
    }

}