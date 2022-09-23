<?php

namespace Anokesh\Hideprice\Pricing\Render;

use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{
    const DISABLE_ADD_TO_CART = 'anokeshconfiguration/hideprice/enable';
    protected $httpContext;
     public function __construct(
         Context $context,
         SaleableInterface $saleableItem,
         PriceInterface $price,
         RendererPool $rendererPool,
         array $data = [],
         SalableResolverInterface $salableResolver = null,
         MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
         \Magento\Framework\App\Http\Context $httpContext,
        ScopeConfigInterface $scopeConfig
     ) {
        $this->scopeConfig = $scopeConfig;
         $this->httpContext = $httpContext;
         parent::__construct($context, $saleableItem, $price, $rendererPool, $data, $salableResolver, $minimalPriceCalculator);
     }

    /**
     * Wrap with standard required container
     *
     * @param string $html
     * @return string
     */
    protected function wrapResult($html)
    {
        if ($this->scopeConfig->getValue(self::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)) {
            $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
            if(!$isLoggedIn){
                $txt = __('Please login to see price');
                $currentUrl  = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
                $login_url = $this->getUrl(
                    'customer/account/login', 
                    ['referer' => base64_encode($currentUrl)]
                    );
                $html = '<p><a href="'.$login_url.'">'; 
                $html .= '<span>'. $txt .'</span></a></p>';
                return $html;
            }
        }
        return parent::wrapResult($html);
    }
}