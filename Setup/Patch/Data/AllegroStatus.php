<?php

namespace Macopedia\Allegro\Setup\Patch\Data;

use Magento\Catalog\Model\Product\Attribute\Frontend\Image;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Image role patch script
 */
class AllegroStatus implements DataPatchInterface
{
    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /**
     * ImageRoles constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return DataPatchInterface|void
     */
    public function apply()
    {

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $attribute = 'allegro_status';
        $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
        $groupName = 'Allegro';
        $AttributeName = 'Allegro status';
        $type = 'int';
        $input_type = 'boolean';
        $eavSetup->addAttribute($entityTypeId, $attribute, [
            'type' => $type,
            'backend' => '',
            'frontend' => '',
            'label' => $AttributeName,
            'input' => $input_type,
            'class' => '',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '0',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => ''
        ]);
        foreach ($eavSetup->getAllAttributeSetIds($entityTypeId) as $attributeSetId) {
            $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 10);
            $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
            $attributeId = $eavSetup->getAttributeId($entityTypeId, $attribute);
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, null);
        }


    }
}
