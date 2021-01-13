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
class AllegroSearchKeyword implements DataPatchInterface
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

        $attribute = 'allegro_search_keyword';
        $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
        $groupName = 'Allegro';
        $eavSetup->addAttribute($entityTypeId, $attribute, [
            'label' => __('Allegro search keyword'),
            'user_defined' => 1,
            'searchable' => 0,
            'visible_on_front' => 0,
            'required' => false,
            'visible_in_advanced_search' => 0,
            'group' => $groupName
        ]);
        foreach ($eavSetup->getAllAttributeSetIds($entityTypeId) as $attributeSetId) {
            $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 10);
            $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
            $attributeId = $eavSetup->getAttributeId($entityTypeId, $attribute);
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, null);
        }
    }
}
