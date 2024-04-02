<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Customer\Addblock\Model\ResourceModel\faq;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Vault Payment Tokens collection
 */
class Collection extends AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Customer\Addblock\Model\faq::class, \Customer\Addblock\Model\ResourceModel\faq::class);
    }
}




