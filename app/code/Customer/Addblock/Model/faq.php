<?php

namespace Customer\Addblock\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Customer\Addblock\Api\Data\faqInterface;

class faq extends AbstractModel implements IdentityInterface, FaqInterface
{
    const CACHE_TAG = 'Customer_Addblock';

    protected function _construct()
    {
        $this->_init('Customer\Addblock\Model\ResourceModel\faq');
    }

    public function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }

    public function getAnswer()
    {
        return $this->getData(self::ANSWER);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function setQuestion($question)
    {
        return $this->setData(self::QUESTION, $question);
    }

    public function setAnswer($answer)
    {
        return $this->setData(self::ANSWER, $answer);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }
}
