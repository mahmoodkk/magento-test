<?php
namespace Mageg\Myshipping\Plugin\Checkout\Model;

use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Api\Data\ShippingInformationInterface;

class ShippingInformationManagement
{
    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * ShippingInformationManagement constructor.
     *
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getExtensionAttributes();
        $deliveryDate = $extAttributes->getDeliveryTime();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setDeliveryTime($deliveryDate);
    }
}