<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 10:23
 */
class Iwelt_Install_Block_Install_Config extends Mage_Install_Block_Config
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('iwelt/install/config.phtml');
    }


    public function getFormData()
    {
        $data = $this->getData('form_data');
        if (!$data) {
            $data = parent::getFormData();
            $data->setUseRewrites(1);
            $this->setFormData($data);
        }
        return $data;

    }

}