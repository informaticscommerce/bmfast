<?php
namespace Mage4\ExtendMagentoCustomer\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig       = $eavConfig;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $attributesInfo = [
            'company_email' => [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Company Email',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'system'=> false,
                'group'=> 'General',
                'global' => true,
                'position' => 1000,
                'visible_on_front' => true,
            ],
            'ein_id' => [
                'type' => 'int',
                'input' => 'text',
                'label' => 'Company EIN Id',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'system'=> false,
                'group'=> 'General',
                'global' => true,
                'position' => 1001,
                'visible_on_front' => true,
            ],
            'reseller_id' => [
                'type' => 'int',
                'input' => 'text',
                'label' => 'Company Reseller Id',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'system'=> false,
                'group'=> 'General',
                'global' => true,
                'position' => 1002,
                'visible_on_front' => true,
            ],
            'job_position' => [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Job Position',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'system'=> false,
                'group'=> 'General',
                'global' => true,
                'position' => 1003,
                'visible_on_front' => true,
            ],
        ];

        foreach ($attributesInfo as $attributeCode => $attributeParams) {
            $eavSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, $attributeCode, $attributeParams);

            $customAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, $attributeCode);

            $customAttribute->setData(
                'used_in_forms',
                ['adminhtml_customer_address','adminhtml_customer','customer_address_edit','customer_register_address','customer_address'] //list of forms where you want to display the custom attribute
            );
            $customAttribute->save();
        }
    }
}
