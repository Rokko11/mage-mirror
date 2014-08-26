<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 14:50
 */

require_once('Mage/Install/controllers/WizardController.php');

class Iwelt_Install_WizardController extends Mage_Install_WizardController
{

    /**
     * End installation
     */
    public function todoAction()
    {
        $this->_checkIfInstalled();

        $date = (string)Mage::getConfig()->getNode('global/install/date');
        if ($date !== Mage_Install_Model_Installer_Config::TMP_INSTALL_DATE_VALUE) {
            $this->_redirect('*/*');
            return;
        }

        $this->_getInstaller()->finish();

        Mage_AdminNotification_Model_Survey::saveSurveyViewed(true);

        $this->_prepareLayout();
        $this->_initLayoutMessages('install/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('iwelt_install/install_todo', 'install.todo')
        );
        $this->renderLayout();
        Mage::getSingleton('install/session')->clear();
    }}