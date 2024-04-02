<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Controller\Adminhtml\TierPrice;

use Amasty\Pgrid\Block\Adminhtml\Product\Grid\TierPrice;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\LayoutFactory;
use Psr\Log\LoggerInterface;

class GetModal extends Action
{
    public const ADMIN_RESOURCE = 'Magento_Catalog::products';

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Action\Context $context,
        LayoutFactory $layoutFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->layoutFactory = $layoutFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);

        if ($entityId = $this->getRequest()->getParam('entity_id')) {
            try {
                $layout = $this->layoutFactory->create();
                $tierPrice = $layout->createBlock(
                    TierPrice::class,
                    'tier_prices',
                    ['data' => ['entity_id' => $entityId]]
                );

                return $result->setContents($tierPrice->toHtml());
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        return $result->setContents('');
    }
}
