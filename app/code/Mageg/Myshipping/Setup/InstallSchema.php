<?php
namespace Mageg\Myshipping\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'delivery_time',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'delivery_time',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'delivery_time',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        $setup->endSetup();
    }
}