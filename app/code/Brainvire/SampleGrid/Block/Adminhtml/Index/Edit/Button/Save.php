<?php
namespace Brainvire\SampleGrid\Block\Adminhtml\Index\Edit\Button;
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
                                'targetName' => 'samplegrid_new_form.samplegrid_new_form',
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
