<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 10:38
 */
class Iwelt_Install_Model_Install_Config extends Mage_Install_Model_Config
{
    protected $_order;

    public function getWritableFullPathsForCheck()
    {
        $media = Mage::getBaseDir('media');
        exec("mkdir -p $media");
        return parent::getWritableFullPathsForCheck();

    }


    public function getWizardSteps()
    {
        $steps = array();
        $this->_order = array();

        foreach ((array) $this->getNode(self::XML_PATH_WIZARD_STEPS) as $stepName => $step) {
            $stepObject = new Varien_Object((array) $step);
            $stepObject->setName($stepName);

            $inserted = false;

            if ($before = (string) $step->attributes()->before) {
                if ($position = array_search($before, $this->_order)) {
                    array_splice($this->_order, $position, 0, array($stepName));
                    $inserted = true;
                }
            }

            if (!$inserted) {
                $this->_order[] = $stepName;
            }

            $steps[] = $stepObject;
        }

        usort($steps, array(
                           $this,
                           'sortSteps'
                      )
        );

        return $steps;
    }

    /**
     * Sorts the steps by their names
     *
     * @param $a
     * @param $b
     * @return int
     */
    protected function sortSteps($a, $b)
    {
        $a = array_search($a->getName(), $this->_order);
        $b = array_search($b->getName(), $this->_order);
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}