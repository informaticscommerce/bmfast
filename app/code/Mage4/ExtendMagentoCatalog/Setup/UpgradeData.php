<?php

namespace Mage4\ExtendMagentoCatalog\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;


class UpgradeData implements UpgradeDataInterface
{

    protected $eavSetupFactory;
    protected $categorySetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0)
        {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $customAttributes = [
                'middle_description' => [
                    'type' => 'text',
                    'label' => 'Middle Description',
                    'input' => 'textarea',
                    'sort_order' => 5,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'searchable' => true,
                    'comparable' => true,
                    'wysiwyg_enabled' => true,
                    'is_html_allowed_on_front' => true,
                    'visible_in_advanced_search' => true
                ],
                'bottom_description' => [
                    'type' => 'text',
                    'label' => 'Bottom Description',
                    'input' => 'textarea',
                    'sort_order' => 5,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'searchable' => true,
                    'comparable' => true,
                    'wysiwyg_enabled' => true,
                    'is_html_allowed_on_front' => true,
                    'visible_in_advanced_search' => true
                ]
            ];

            foreach($customAttributes as $item => $data) {
                $categorySetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, $item, $data);
            }

            $idg =  $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information');

            foreach($customAttributes as $item => $data) {
                $categorySetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $idg,
                    $item,
                    $data['sort_order']
                );
            }
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0)
        {
            $setup->run("update eav_attribute set is_required = 0 where attribute_code IN ('top_description','middle_description','bottom_description')");
        }

        $setup->endSetup();
    }

}
