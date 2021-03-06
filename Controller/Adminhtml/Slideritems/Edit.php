<?php
/**
 *
 * Copyright © 2015 Stepzero.Solutions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Stepzerosolutions\Tbslider\Controller\Adminhtml\Slideritems;

class Edit extends \Stepzerosolutions\Tbslider\Controller\Adminhtml\Slideritems
{
    /**
     * Edit Slider action.Forward to new action.
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        return $this->resultForwardFactory->create()->forward('new');
    }
}
