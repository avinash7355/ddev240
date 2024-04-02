<?php
namespace Brainvire\Widget\Block\Adminhtml\Edit\Button;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
class Save extends Generic implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'widget_new_form.widget_new_form',
                                'actionName' => 'save',
                                'params' => [
                                    true, 
                                    [
                                        'back' => null // Change this to 'close' if you want to save and close.
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'sort_order' => 90, // You can adjust this value as needed.
        ];
    }
}
