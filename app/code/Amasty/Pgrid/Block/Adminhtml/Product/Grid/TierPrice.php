<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Block\Adminhtml\Product\Grid;

use Amasty\Pgrid\Block\Adminhtml\Product\Grid\TierPrice\Content;
use Magento\Catalog\Block\Adminhtml\Form;

class TierPrice extends Form
{
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        // FieldNameSuffix should be 'amastyItems' for getting tier prices information in request.
        $form->setFieldNameSuffix('amastyItems');
        $fieldset = $form->addFieldset('tiered_price', ['legend' => $this->getLegend()]);

        $fieldset->addField(
            'tier_price',
            'text',
            [
                'name' => 'tier_price',
                'class' => 'requried-entry',
                'label' => $this->getTabLabel(),
                'title' => $this->getTabTitle()
            ]
        );

        $form->getElement(
            'tier_price'
        )->setRenderer(
            $this->getLayout()->createBlock(Content::class)
        );
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
