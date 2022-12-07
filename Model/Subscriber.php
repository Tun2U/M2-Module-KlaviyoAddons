<?php

namespace Tun2U\KlaviyoAddons\Model;

class Subscriber
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\SubscriptionManagerInterface
     */
    private $subscriptionManager;

    /**
     * Subscriber constructor.
     * 
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Newsletter\Model\SubscriptionManagerInterface $subscriptionManager
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Newsletter\Model\SubscriptionManagerInterface $subscriptionManager
    ) {
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->subscriberFactory = $subscriberFactory;
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * Load subscriber model by customer
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param int $websiteId
     * @return \Magento\Newsletter\Model\Subscriber
     */
    private function loadSubscriberByCustomer(\Magento\Customer\Api\Data\CustomerInterface $customer, int $websiteId): \Magento\Newsletter\Model\Subscriber
    {
        $subscriber = $this->subscriberFactory->create();
        $subscriber->loadByCustomer((int)$customer->getId(), $websiteId);

        if (!$subscriber->getId()) {
            $subscriber->loadBySubscriberEmail((string)$customer->getEmail(), $websiteId);
        }

        return $subscriber;
    }

    /**
     * Unsubscribes customer by email
     * 
     * @param string $email
     * @return bool
     */
    public function unsubscribeCustomerByEmail(string $email)
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $websiteId = (int)$this->storeManager->getStore($storeId)->getWebsiteId();
        $customer = $this->customerRepository->get($email);
        $subscriber = $this->loadSubscriberByCustomer($customer, $websiteId);
        $currentStatus = (int)$subscriber->getStatus();

        // unsubscribe current user only with status subscribed
        if ($currentStatus === \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED) {
            $customer->setStoreId($storeId);
            $customerId = $customer->getId();
            $this->subscriptionManager->unsubscribeCustomer((int)$customerId, $storeId);
        
            return true;
        }

        return false;
    }
}
