<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Input;

use Youngx\Bundle\AdminBundle\Module\DistrictModule\Entity\DistrictEntity;
use Youngx\Bundle\jQueryBundle\Input\CXSelectInput;

class SelectCategoryInput extends CXSelectInput
{
    protected function init()
    {
        parent::init();

        $this->setUrl($this->context->generateUrl('category-ajax-cxselect'));;
        $this->setSelectsTotal(4);
    }

    public function setValue($value)
    {
        $current = $this->context->repository()->load('district', $value);
        if ($current && $current instanceof DistrictEntity) {
            $paths = $current->getPaths();
            $values = array();
            foreach ($paths as $i => $entity) {
                $values[$this->selects[$i]] = $paths[$i]->getId();
            }
            $this->setValues($values);
        }
    }
}