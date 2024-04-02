<?php
/**
 * Created By : Rohan Hapani
 */
namespace Brainvire\CustomSlider\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class PageWidget extends Template implements BlockInterface
{
   protected $_template = "Brainvire_CustomSlider::widget/page_widget.phtml";
}