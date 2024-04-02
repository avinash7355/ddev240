<?php
namespace Brainvire\CustomSystem\Model\Config\Backend;
use Magento\Config\Model\Config\Backend\File;
class Image extends File
{
    /**
     * Validate uploaded file before saving
     *
     * @return $this
     */
    public function beforeSave()
    {
        $file = $this->getFileData();
        if ($file) {
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($fileExtension !== 'png') {
                throw new \Magento\Framework\Exception\LocalizedException(__('This image is not valid fileExtension.'));
            }
        }
        return parent::beforeSave();
    }
}