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
class AllegroCompetitionPrice implements DataPatchInterface
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

        $attribute = 'allegro_competition_minimal_price';
        $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
        $groupName = 'Allegro';
        $eavSetup->addAttribute($entityTypeId, $attribute, [
            'type'          => 'decimal',
            'backend'       => 'Magento\Catalog\Model\Product\Attribute\Backend\Price',
            'label'         => 'Allegro minimal price',
            'input'         => 'price',
            'required'      => false,
            'global'        => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
            'group'         => 'General'
        ]);
        foreach ($eavSetup->getAllAttributeSetIds($entityTypeId) as $attributeSetId) {
            $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 10);
            $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
            $attributeId = $eavSetup->getAttributeId($entityTypeId, $attribute);
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, null);
        }


    }
}
