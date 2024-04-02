<?php
/**
 * Code standard by : RH
 */
namespace Brainvire\CustomIndexer\Model;

use Magento\Framework\Indexer\ActionInterface as IndexerInterface;
use Magento\Framework\Mview\ActionInterface as MviewInterface;

class Indexer implements IndexerInterface, MviewInterface
{

    /**
     * It's used by mview. It will execute when process indexer in "Update on schedule" Mode.
     */
    public function execute($ids)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/quoteToCart.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);
		$logger->info($ids);
        file_put_contents(BP.'/var/log/executefact.log', 'currentPointer-->'.print_r($ids).PHP_EOL,FILE_APPEND);
    }

    /**
     * Add code here for execute full indexation
     */
    public function executeFull()
    {
    }

    /**
     * Add code here for execute partial indexation by ID list
     */
    public function executeList(array $ids)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/quoteToCart.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);
		$logger->info($ids);
        file_put_contents(BP.'/var/log/executeList.log', 'currentPointer-->'.print_r($ids, true).PHP_EOL,FILE_APPEND);

    }

    /**
     * Add code here for execute partial indexation by ID
     */
    public function executeRow($id)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/quoteToCart.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);
		$logger->info($id);
        file_put_contents(BP.'/var/log/executeRow.log', 'currentPointer-->'.print_r($id).PHP_EOL,FILE_APPEND);

    }
}