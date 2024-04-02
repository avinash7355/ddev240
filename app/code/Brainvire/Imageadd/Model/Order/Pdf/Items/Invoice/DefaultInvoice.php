<?php
namespace Brainvire\Imageadd\Model\Order\Pdf\Items\Invoice;

use Brainvire\Imageadd\Model\Order\Pdf\Invoice;
use Magento\Framework\App\Filesystem\DirectoryList;


class DefaultInvoice extends \Magento\Sales\Model\Order\Pdf\Items\Invoice\DefaultInvoice
{
    public function draw()
    {
    $order = $this->getOrder();
    $item = $this->getItem();
    $pdf = $this->getPdf();
    $page = $this->getPage();
    $lines = [];

    // draw Product image         // Step 1 
    $productImage = $this->getProductImage($item, $page);
    

    // draw Product name
    $lines[0] = [['text' => $this->string->split($item->getName(), 35, true, true), 'feed' => 35]];

    // draw Product image                  // Step 2
    $lines[0][] = array(
    'text' => '',
    'feed' => 35,
    'is_image' => true,
    'image' => $productImage,
    );
    // draw SKU
    $lines[0][] = [
        'text' => $this->string->split($this->getSku($item), 17),
        'feed' => 370,
        'align' => 'right',
    ];
    // draw QTY
    $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 475, 'align' => 'right'];
    // draw item Prices
    $i = 0;
    $prices = $this->getItemPricesForDisplay();
    $feedPrice = 425;
    $feedSubtotal = $feedPrice + 140;
    foreach ($prices as $priceData) {
        if (isset($priceData['label'])) {
            // draw Price label
               $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right'];
            // draw Subtotal label
               $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right'];
               $i++;
        }
        // draw Price
           $lines[$i][] = [
            'text' => $priceData['price'],
            'feed' => $feedPrice,
            'font' => 'bold',
               'align' => 'right',
        ];
        // draw Subtotal
           $lines[$i][] = [
            'text' => $priceData['subtotal'],
            'feed' => $feedSubtotal,
            'font' => 'bold',
               'align' => 'right',
        ];
        $i++;
    }
    // draw Tax
    $lines[0][] = [
        'text' => $order->formatPriceTxt($item->getTaxAmount()),
        'feed' => 515,
        'font' => 'bold',
        'align' => 'right',
    ];
    // custom options
    $options = $this->getItemOptions();
    if ($options) {
        foreach ($options as $option) {
            // draw options label
               $lines[][] = [
                   'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                   'font' => 'italic',
                   'feed' => 35,
            ];
            if ($option['value']) {
                if (isset($option['print_value'])) {
                       $printValue = $option['print_value'];
                } else {
                       $printValue = $this->filterManager->stripTags($option['value']);
                }
                   $values = explode(', ', $printValue);
                   foreach ($values as $value) {
                       $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                }
            }
        }
    }
    $lineBlock = ['lines' => $lines, 'height' => 20];
    
    $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true],1);
    
       $this->setPage($page);
    }

    /*
    * Return Value of custom attribute       // Step 3
    * */
    private function getProductImage($item, &$page)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productId = $item->getOrderItem()->getProductId();
        $image = $objectManager->get('Magento\Catalog\Model\Product')->load($productId)->getSmallImage();
        if ($image) {
            try {
                $mediaDirectory = $objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::MEDIA);
                $imagePath = $mediaDirectory->getAbsolutePath('/catalog/product' . $image);
                if ($mediaDirectory->isFile($imagePath)) {
                    $image = \Zend_Pdf_Image::imageWithPath($imagePath);
                    
                    $imageWidth = 150; // set image width to 150 pixels
                    $imageHeight = 150; // set image height to 150 pixels
                    $top = 450;
                    $left = 30;
                    $bottom = $top - $imageHeight;
                    $right = $left + $imageWidth;
                    $page->drawImage($image, $left, $bottom, $right, $top);
                }
            } catch (Exception $e) {
                return false;
            }
        }
    }
}