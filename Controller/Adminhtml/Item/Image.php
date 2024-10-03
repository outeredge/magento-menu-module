<?php

namespace OuterEdge\Menu\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Framework\Webapi\Exception as WebApiException;
use Exception;

class Image extends Action
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var ResultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var array
     */
    protected $allowedExtensions = ['jpg', 'gif', 'png', 'svg'];

    /**
     * @var string
     */
    protected $menuFolder = 'menu';

    /**
     * @param Context $context
     * @param Filesystem $fileSystem
     * @param UploaderFactory $uploaderFactory
     * @param ResultJsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory,
        ResultJsonFactory $resultJsonFactory
    ) {
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $destinationPath = $this->getDestinationPath();

        try {
            $uploader = $this->uploaderFactory->create(['fileId' => 'image'])
                ->setAllowCreateFolders(true)
                ->setAllowRenameFiles(true)
                ->setFilesDispersion(true)
                ->setAllowedExtensions($this->allowedExtensions);

            $result = $uploader->save($destinationPath);

            if (!$result) {
                return $this->resultJsonFactory->create()
                    ->setHttpResponseCode(WebApiException::HTTP_INTERNAL_ERROR)
                    ->setData(['message' => sprintf(__('File cannot be saved to path: $1', $destinationPath))]);
            }

            $result['filename'] = '/' . $this->menuFolder . $result['file'];
            return $this->resultJsonFactory->create()
                ->setData($result);
        } catch (Exception $e) {
            return $this->resultJsonFactory->create()
                ->setHttpResponseCode(WebApiException::HTTP_INTERNAL_ERROR)
                ->setData(['message' => $e->getMessage()]);
        }
    }

    public function getDestinationPath()
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath($this->menuFolder . '/');
    }
}
