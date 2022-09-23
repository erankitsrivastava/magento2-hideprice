<?php
/**
 * IsSalable plugin
 */

namespace Anokesh\Hideprice\Plugin;

use Magento\Framework\App\Http\Context;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Product;

/**
 * Class IsSalablePlugin
 * @package Anokesh\Hideprice\Plugin
 */
class IsSalable
{
    const DISABLE_ADD_TO_CART = 'anokeshconfiguration/hideprice/enable';

    protected $scopeConfig;
    protected $context;

    /**
     * IsSalable constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Context $context
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
    }

    /**
     * make product not salable if customer are not logged-in
     * @return bool
     */
    public function afterIsSalable(Product $product, $result)
    {
        $return = $result;
        if ($this->scopeConfig->getValue(self::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)) {
            if (!$this->context->getValue(CustomerContext::CONTEXT_AUTH)) {
                $return = false;
                $product->setData('salable', false);
            }
        }
        return $return;
    }
}