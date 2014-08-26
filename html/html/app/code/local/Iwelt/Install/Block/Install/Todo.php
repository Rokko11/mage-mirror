<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 25.08.14
 * Time: 14:54
 */

class Iwelt_Install_Block_Install_Todo extends Mage_Install_Block_Abstract
{
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->setTemplate('iwelt/install/todo.phtml');
    }


}