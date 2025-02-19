<?php
namespace Aureatelabs\NewProductType\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{
   private $eavSetupFactory;

   public function __construct(EavSetupFactory $eavSetupFactory)
   {
       $this->eavSetupFactory = $eavSetupFactory;
   }

   public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
   {
       /** @var EavSetup $eavSetup */
       $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

       //associate these attributes with new product type
       $fieldList = [
           'price',
           'special_price',
           'special_from_date',
           'special_to_date',
           'minimal_price',
           'cost',
           'tier_price',
           'weight',
       ];
       // make these attributes applicable to new product type
       foreach ($fieldList as $field) {
           $applyTo = explode(
               ',',
               $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
           );
           if (!in_array('new_product_type', $applyTo)) {
               $applyTo[] = 'new_product_type';
               $eavSetup->updateAttribute(
                   \Magento\Catalog\Model\Product::ENTITY,
                   $field,
                   'apply_to',
                   implode(',', $applyTo)
               );
           }
       }
   }
}