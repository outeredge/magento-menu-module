<?php

namespace OuterEdge\Menu\Block\Adminhtml\Menu\Edit\Tab;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Integration\Model\Oauth\TokenFactory;

class Items extends Template
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var TokenFactory
     */
    protected $tokenFactory;

    /**
     * @var Session
     */
    protected $authSession;

    /**
     * @param Registry $registry
     * @param TokenFactory $tokenFactory
     * @param Session $authSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        TokenFactory $tokenFactory,
        Session $authSession,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->tokenFactory = $tokenFactory;
        $this->authSession = $authSession;
        parent::__construct($context, $data);
    }

    /**
     * Return current menu id
     *
     * @return int
     */
    public function getMenuId()
    {
        return $this->coreRegistry->registry('menu')->getId();
    }

    /**
     * return admin api token straight from logged in user id
     * @see \Magento\Integration\Model\AdminTokenService::createAdminAccessToken
     * @todo Remove inplace of session based authentication http://devdocs.magento.com/guides/v2.2/get-started/authentication/gs-authentication-session.html
     *
     * @return string
     */
    public function getToken()
    {
        return $this->tokenFactory->create()->createAdminToken($this->authSession->getUser()->getId())->getToken();
    }
}
