<?php
namespace Mage4\CustomB2BAttr\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
   private $customerSetupFactory;

   public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory)
   {
       $this->customerSetupFactory = $customerSetupFactory;
   }

   public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
   {
       $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
       if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $customerSetup->removeAttribute(
                'customer_address',
                'company_email'
            );
           $customerSetup->removeAttribute(
               'customer_address',
               'ein_id'
           );
           $customerSetup->removeAttribute(
               'customer_address',
               'reseller_id'
           );
           $customerSetup->removeAttribute(
               'customer_address',
               'job_position'
           );
       }
   }
}
