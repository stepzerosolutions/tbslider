<?php
/**
 *
 * Copyright © 2015 Stepzero.Solutions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Stepzerosolutions\Tbslider\Controller\Adminhtml\Slider\Widget;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\Controller\Result\RawFactory;

class Chooser extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * @var RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     * @param RawFactory $resultRawFactory
     */
    public function __construct(Context $context, LayoutFactory $layoutFactory, RawFactory $resultRawFactory)
    {
        $this->_layoutFactory = $layoutFactory;
        $this->_resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * Chooser Source action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->_layoutFactory->create();

        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $layout->createBlock(
            'Stepzerosolutions\Tbslider\Block\Adminhtml\Block\Widget\Chooser',
            '',
            ['data' => ['id' => $uniqId]]
        );

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->_resultRawFactory->create();
        $resultRaw->setContents($pagesGrid->toHtml());
        return $resultRaw;
    }
}
