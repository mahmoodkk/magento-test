<?php

namespace Mageg\Cron\Cron;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Email
{
	/**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    protected $_resource;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context
        ,\Magento\Framework\App\Request\Http $request
        ,\Magento\Framework\App\ResourceConnection $resource
        ,\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        ,\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
        ,\Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_request = $request;
        $this->_transportBuilder = $transportBuilder;
        $this->_resource = $resource;
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    
    public function execute()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $date = date('Y-m-d H:i:s');
      
        if(!isset($next_email)){
            $next_email = 0;
        }
    
    /*--- check next email inside db if exists----*/
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
       $table = $resource->getTableName('email_notifications'); 
        $next_emails = $connection->fetchCol('SELECT next_email FROM ' . $table);
        if($next_emails)
        $next_email = $next_emails[0];
        $days        = $this->scopeConfig->getValue('mageg_cron/general/multiple_time', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $admin_email = $this->scopeConfig->getValue('mageg_cron/general/admin_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $copyTo = explode(",",$this->scopeConfig->getValue('mageg_cron/general/addresses', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        //echo 
       
        if($days)
            $startDate = date("Y-m-d h:i:s",strtotime($date .' -'.$days.' minute')); // start date
      

        $endDate =   date("Y-m-d h:i:s"); // end date
        
        //echo "<pre/>";
        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->getCollection()
                ->addAttributeToSelect("*")->addAttributeToFilter('created_at', array('from'=>$startDate, 'to'=>$endDate));
               //->addFieldToFilter('created_at', array('from'=>$prev_date, 'to'=>$current_date));
    
          if(($customerObj->count() < 1) && ($date > $next_email)){
  
             if($next_email == 0){
                $next_email = date("Y-m-d h:i:s",strtotime($date .' +'.$days.' minutes')); 
                $sql = "INSERT INTO " . $table . " (id, next_email) VALUES ('1', '$next_email')";

             }else{
                 $next_email = date("Y-m-d h:i:s",strtotime($date .' +'.$days.' minutes'));
                $sql = "UPDATE " . $table . " SET  next_email='$next_email' where id='1'";
             }
                

        /*---   Update the Db record---------*/

                        
                        
                        $connection->query($sql);

            /*------- Send email to admin and other addresses------------*/
            $store = $this->_storeManager->getStore()->getId();
             foreach ($copyTo as $email) {    
                  
                $this->_transportBuilder->addBcc($email);            
                }
        $transport = $this->_transportBuilder->setTemplateIdentifier('mycron_email_template_template')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
            ->setTemplateVars(
                [
                    'store' => $this->_storeManager->getStore(),
                ]
            )
            ->setFrom('general')
            ->addTo($admin_email, 'Customer Name')
            ->getTransport();
   
           
        //$transport = $this->_transportBuilder->getTransport();       
        $transport->sendMessage();
      

          }

        

    }
}