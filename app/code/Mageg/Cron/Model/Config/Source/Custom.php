<?php

namespace Mageg\Cron\Model\Config\Source;

class Custom implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {

        return [
            ['value' => 30, 'label' => __('30 minutes')],
            ['value' => 50, 'label' => __('50 minutes')],
            ['value' => 60, 'label' => __('1 hour')],
            ['value' => 1440,'label' => __('1 Day')],
        ];
    }
}