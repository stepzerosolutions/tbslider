<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Stepzerosolutions\Tbslider\Controller\Adminhtml\Slider;

use Stepzerosolutions\Tbslider\Api\Data\SliderInterfaceFactory;
use Stepzerosolutions\Tbslider\Api\Data\SliderInterface;
use Stepzerosolutions\Tbslider\Api\SliderRepositoryInterface;

class Save extends 
\Stepzerosolutions\Tbslider\Controller\Adminhtml\Slider
{
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $_dataObjectProcessor;


    /**
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param SliderRepositoryInterface $sliderRepository
	 * @param SliderInterfaceFactory $sliderDataFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        SliderRepositoryInterface $sliderRepository,
		SliderInterfaceFactory $sliderDataFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
    ) {
        $this->_dataObjectProcessor = $dataObjectProcessor;
        parent::__construct(
            $context,
            $coreRegistry,
            $sliderRepository,
			$sliderDataFactory,
            $resultForwardFactory,
            $resultPageFactory
        );
    }

    /**
     * Store Variation types Data to session
     *
     * @param array $sliderData
     * @return void
     */
    protected function storeSliderDataToSession($sliderData)
    {
        if (array_key_exists('title', $sliderData)) {
            $sliderData['slider_title'] = $sliderData['title'];
            unset($sliderData['title']);
        }
        $this->_getSession()->setSliderData($sliderData);
    }

    /**
     * Create or save Variation type.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {

        /** @var \Stepzerosolutions\Tbslider\Api\Data\SliderInterface $slider */
        $slider = null;
            $id = $this->getRequest()->getParam('id');
            $resultRedirect = $this->resultRedirectFactory->create();
            try {
                if ($id !== null) {
                    $slider = $this->sliderRepository->getById( (int)$id );
                } else {
                    $slider = $this->sliderDataFactory->create();
                }
                $sliderTitle = (string)$this->getRequest()->getParam('slider_title');
                if (empty($sliderTitle)) {
                    $sliderTitle = null;
                }
                $slider->setSliderTitle($sliderTitle);
                $sliderDescription = (string)$this->getRequest()->getParam('slider_description');
                if (empty($sliderDescription)) {
                    $sliderDescription = null;
                }
                $slider->setSliderDescription($sliderDescription);
                $sliderWidth = $this->getRequest()->getParam('slider_width');
                if (empty($sliderWidth)) {
                    $sliderWidth = null;
                }
                $slider->setSliderWidth($sliderWidth);
                $sliderHeight = $this->getRequest()->getParam('slider_height');
                if (empty($sliderHeight)) {
                    $sliderHeight = null;
                }
                $slider->setSliderHeight($sliderHeight);
                $status = (int)$this->getRequest()->getParam('status');
                if (empty($status)) {
                    $status = null;
                }
                $stores = $this->getRequest()->getParam('stores');
                if (empty($stores)) {
                    $stores = null;
                }
                $slider->setStatus($status);
				$sliderClass = (string)$this->getRequest()->getParam('slider_class');
				$sliderBgcolor = (string)$this->getRequest()->getParam('slider_bgcolor');
				$sliderAutoresoponsive = (int)$this->getRequest()->getParam('slider_autoresoponsive');
				$sliderType = (int)$this->getRequest()->getParam('slider_type');
				$pauseonhover = (int)$this->getRequest()->getParam('pauseonhover');
				$wrap = (int)$this->getRequest()->getParam('wrap');
				$keyboard = (int)$this->getRequest()->getParam('keyboard');
				$slidermeta = (string)$this->getRequest()->getParam('slidermeta');
				$sliderHidexs = (int)$this->getRequest()->getParam('slider_hidexs');
				$sliderDuration = (int)$this->getRequest()->getParam('slider_duration');
				$slidermeta = (string)$this->getRequest()->getParam('slidermeta');
				$hidenavigation = (string)$this->getRequest()->getParam('hidenavigation');

				$slider->setSliderClass($sliderClass);
				$slider->setSliderBgcolor($sliderBgcolor);
				$slider->setSliderAutoresponsive($sliderAutoresoponsive);
				$slider->setSliderType($sliderType);
				$slider->setPauseonhover($pauseonhover);
				$slider->setWrap($wrap);
				$slider->setKeyboard($keyboard);
				$slider->setSlidermeta($slidermeta);
				$slider->setSliderHidexs($sliderHidexs);
				$slider->setSliderDuration($sliderDuration);
				$slider->setStores($stores);
				$slider->setHidenavigation($hidenavigation);
                $this->sliderRepository->save($slider);

                $this->messageManager->addSuccess(__('You saved the Slider.'));
                $resultRedirect->setPath('tbslider/slider');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                if ($slider != null) {
                    $this->storeSliderDataToSession(
                        $this->_dataObjectProcessor->buildOutputDataArray(
                            $slider,
                            '\Stepzerosolutions\Tbslider\Api\Data\SliderInterface'
                        )
                    );
                }
                $resultRedirect->setPath('tbslider/slider');
            }
            return $resultRedirect;
    }
}