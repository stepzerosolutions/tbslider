<?php
/**
 * Copyright © 2015 Stepzero.solutions. All rights reserved.
 * See COPYING.txt for license details.
 */
 namespace Stepzerosolutions\Tbslider\Block;

use Stepzerosolutions\Tbslider\Model\Slider as SliderModel;
use Stepzerosolutions\Tbslider\Model\Templates\Fullwidth as Fullwidth;
/**
 * Slider blocks content block
 */
class Slider extends \Magento\Framework\View\Element\Template
 implements \Magento\Widget\Block\BlockInterface
{
	
	protected $_template = 'Stepzerosolutions_Tbslider::slider.phtml';
	
	
    /**
     * Registry object.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * slider collecion factory.
     *
     * @var \Stepzerosolutions\Tbslider\Model\ResourceModel\Slider\CollectionFactory
     */
    protected $_sliderFactory;
	
	
    /**
     * slider items collecion factory.
     *
     * @var \Stepzerosolutions\Tbslider\Model\Slider\Items\CollectionFactory
     */
    protected $slideritemsCollection;

    /**
     * scope config.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;


    /**
     * @var \\Stepzerosolutions\Tbslider\Helper\Data
     */
    protected $_sliderHelper;
    /**
     * [__construct description].
     *
     * @param \Magento\Framework\View\Element\Template\Context                $context
     * @param \Magento\Framework\Registry                                     $coreRegistry
     * @param \Stepzerosolutions\Tbslider\Model\SliderFactory				  $sliderFactory
     * @param \Magento\Catalog\Model\CategoryFactory                          $categoryFactory
     * @param \Magento\Store\Model\StoreManagerInterface                      $storeManager
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Stepzerosolutions\Tbslider\Model\SliderFactory $sliderFactory,
        \Stepzerosolutions\Tbslider\Model\ResourceModel\Slider\Items\CollectionFactory $slideritemsCollection,
        \Stepzerosolutions\Tbslider\Helper\Data $sliderHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_sliderFactory = $sliderFactory;
        $this->_slideritemsCollection = $slideritemsCollection;

        $this->_scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $context->getStoreManager();
		$this->_sliderHelper = $sliderHelper;
    }
	
	
    /**
     * Prepare block text and determine whether block output enabled or not
     * Prevent blocks recursion if needed
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $store = $this->_storeManager->getStore()->getId();

        if ($this->_scopeConfig->getValue(
            SliderModel::XMLSLIDERSTATUS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store
        ) ) {
			parent::_beforeToHtml();
			$sliderId = $this->getSliderId();
			if ($sliderId) {
				$storeId = $this->_storeManager->getStore()->getId();
				$slider = $this->_sliderFactory->create();
				$sliderData = $slider->load($sliderId);
				$sliderItemsData = $this->_slideritemsCollection->create()
				->addFieldToFilter( 'slideritem_slider', ['eq' => $sliderData->getId()] )
				->addFieldToFilter( 'is_active', ['eq' => 1] )
				->setOrder( 'slider_sort', 'desc' );
				$responsive = implode( ",", $slider->getResponsiveWidth() );
	
				if( $sliderData->getSliderType()==0 ) {
					$sliderTemplate = new Fullwidth($this->_storeManager);
				}
				$sliderTemplate->setSliderResponsiveData($responsive);
				$sliderTemplate->setSliderData($sliderData);
				$sliderTemplate->setSlideritems($sliderItemsData);
				
				if( $sliderData->getStatus()) {
					$output = $sliderTemplate->renderSliderLayout();
					$output .= '<div class="slidercontainer';
					$output .= ( $sliderData->getSliderHidexs() )?' hidden-xs':'';
					$output .= '">';
					$output .= '<div class="slider_'.$sliderData->getID().'">';
					$output .= $sliderTemplate->renderSlider();
					$output .= '</div></div>';
					$output .= '
							<script type="text/javascript">
							require([\'jquery\',\'sz/tbslider\'], function($) {
							});
							</script>';
					$this->setText($output);
				}
			}
			return $this;
		}
    }
	
	
    /**
     * @return
     */
    protected function _toHtml()
    {
        $store = $this->_storeManager->getStore()->getId();

        if ($this->_scopeConfig->getValue(
            SliderModel::XMLSLIDERSTATUS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store
        ) ) {
			//return parent::_toHtml();
            return $this->getText();
        }
        return '';
    }
	
	
}