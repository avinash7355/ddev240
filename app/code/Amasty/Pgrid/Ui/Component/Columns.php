<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */
namespace Amasty\Pgrid\Ui\Component;

class Columns extends \Magento\Ui\Component\AbstractComponent
{
    public const NAME = 'amasty_columns';

    public function getComponentName()
    {
        return static::NAME;
    }

    public function prepare()
    {
        $amasty = $this->getContext()->getRequestParam('amasty');

        if (isset($amasty['columns'])) {
            $configData = $this->getContext()->getDataProvider()->getConfigData();
            $configData['amasty_columns'] = $amasty['columns'];

            $this->getContext()->getDataProvider()->setConfigData($configData);
        }

        $config = $this->getConfig();

        parent::prepare();
    }
}
