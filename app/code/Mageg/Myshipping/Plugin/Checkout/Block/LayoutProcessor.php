<?php
namespace Mageg\Myshipping\Plugin\Checkout\Block;

use Mageg\Myshipping\Model\Config;

class LayoutProcessor
{
    /**
     * @var \Mageg\Myshipping\Model\Config
     */
    protected $config;

    /**
     * LayoutProcessor constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {

        $requiredDeliveryDate =  $this->config->getRequiredDeliveryDate()?: false;
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shippingAdditional'] = [
            'component' => 'uiComponent',
            'displayArea' => 'shippingAdditional',
            'children' => [
                'delivery_time' => [
                    'component' => 'Mageg_Myshipping/js/view/delivery-date-block',
                    'displayArea' => 'delivery-date-block',
                    'deps' => 'checkoutProvider',
                    'dataScopePrefix' => 'delivery_time',
                    'children' => [
                        'form-fields' => [
                            'component' => 'uiComponent',
                            'displayArea' => 'delivery-date-block',
                            'children' => [
                                'delivery_time' => [
                                    'component' => 'Mageg_Myshipping/js/view/delivery-date',
                                    'config' => [
                                        'customScope' => 'delivery_time',
                                        'template' => 'ui/form/field',
                                        'elementTmpl' => 'Mageg_Myshipping/fields/delivery-date',
                                        'options' => [],
                                        'id' => 'delivery_time',
                                        'data-bind' => ['datetimepicker' => true]
                                    ],
                                    'dataScope' => 'delivery_date.delivery_time',
                                    'label' => 'Delivery Date',
                                    'provider' => 'checkoutProvider',
                                    'visible' => true,
                                    'validation' => [
                                        'required-entry' => $requiredDeliveryDate
                                    ],
                                    'sortOrder' => 10,
                                    'id' => 'delivery_time'
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ];

        return $jsLayout;
    }
}