<?php
/**
* Copyright © 2018 Porto. All rights reserved.
*/

namespace Mage4\ExtendMagentoCatalog\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var $eavSetupFactory
     */
    private $eavSetupFactory;
    private $categorySetupFactory;
 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        $customAttributes = [
            'top_description' => [
                'type' => 'text',
                'label' => 'Top Description',
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

        $setup->endSetup();
    }
}