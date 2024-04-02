<?php
namespace Customer\Dataphoto\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class SaveImage extends Action
{
    protected $filesystem;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
    }

    public function execute()
    {
        $imageFile = $this->getRequest()->getFiles('image_field_name');
        $basePath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('mymodule/images/');
        $imageName = 'my_image.jpg';

        $imageFile->moveTo($basePath . $imageName);

        // Save image data to the database (as explained in the previous response)

        // Redirect or perform other actions
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index'); // Redirect back to the listing page
        return $resultRedirect;
    }
}
