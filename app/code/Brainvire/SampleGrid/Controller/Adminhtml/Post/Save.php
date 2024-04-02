<?php
namespace Brainvire\SampleGrid\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Brainvire\SampleGrid\Model\Custom;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @var Blog
     */
    protected $rhblog;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Blog $rhblog
     * @param Session $adminsession
     */
    public function __construct(
        Action\Context $context,
        Custom $rhblog,
        Session $adminsession
    ) {
        parent::__construct($context);
        $this->rhblog = $rhblog;
        $this->adminsession = $adminsession;
    }

    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $blog_id = $this->getRequest()->getParam('id');
            if ($blog_id) {
                $this->rhblog->load($blog_id);
            }

            if (isset($data['category_id']) && is_array($data['category_id'])) {
                $data['category_id'] = implode(',', $data['category_id']);
            }
             if (isset($data['color']) && is_array($data['color'])) {
                $data['color'] = implode(',', $data['color']);
            }
            $imageName = $data['image'][0]['file'];
            $data['image'] = $imageName;

            $this->rhblog->setData($data);

            try {
                $this->rhblog->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->rhblog->getBlogId(), '_current' => true]);
                    }
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}