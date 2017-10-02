<?php

namespace OuterEdge\Menu\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Forward;

class NewAction extends Action
{
    /**
     * @var ForwardFactory
     */
    private $forwardFactory;

    /**
     * @param Context $context
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        Context $context,
        ForwardFactory $forwardFactory
    ) {
        $this->forwardFactory = $forwardFactory;
        parent::__construct($context);
    }

    /**
     * @return Forward
     */
    public function execute()
    {
        return $this->forwardFactory->create()->forward('edit');
    }
}
