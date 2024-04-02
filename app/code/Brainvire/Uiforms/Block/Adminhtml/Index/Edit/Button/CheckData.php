<?php
namespace Brainvire\Uiforms\Block\Adminhtml\Index\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class CheckData extends Generic implements ButtonProviderInterface
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $js = "
            require(['jquery'], function ($) {
                function checkDataFunction() {
                    alert('Hello, this is a custom alert message!');
                }
            });
        ";
        
        return [
            'label' => __('Check Data'),
            'class' => 'save primary',
            'on_click' => "checkDataFunction()",
            'sort_order' => 30,
            'js_callback' => $js,
        ];
    }
}
