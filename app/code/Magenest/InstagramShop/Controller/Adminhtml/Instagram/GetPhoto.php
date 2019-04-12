<?php

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

/**
 * Class GetPhoto
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class GetPhoto extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenest\InstagramShop\Model\PhotoFactory
     */
    protected $photoFactory;

    /**
     * @var \Magenest\InstagramShop\Model\Client
     */
    protected $client;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magenest\InstagramShop\Model\Client $client
    )
    {
        $this->photoFactory = $photoFactory;
        $this->client = $client;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        try {
            $this->getPhotos();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->_redirect('instagram/instagram');

    }

    /**
     * get photos in instagram to save on server
     */
    protected function getPhotos()
    {
        $endpoint = '/users/self/media/recent';
        $param = ['count' => 100000];
        $photo = $this->client->api($endpoint, 'GET', $param);
        if (isset($photo['data'])) {
            $photoInfo = $photo['data'];
            if (isset($photoInfo[0])) {
                foreach ($photoInfo as $info) {
                    if ($info['type'] == 'image' || $info['type'] == 'carousel') {
                        $this->savePhoto($info);
                    }
                }
            }
        }
    }


    /**
     * @param $info
     * @throws \Exception
     */
    protected function savePhoto($info)
    {
        if (isset($info['images'])) {
            $photo = $this->photoFactory->create()->load($info['id'], 'photo_id');
            if ($photo->getId()) {
                $photo->setCaption($info['caption']['text']);
                $photo->setLikes($info['likes']['count']);
                $photo->setComments($info['comments']['count']);
            } else {
                $data = [
                    'photo_id' => $info['id'],
                    'url' => $info['link'],
                    'source' => $info['images']['standard_resolution']['url'],
                    'caption' => $info['caption']['text'],
                    'likes' => $info['likes']['count'],
                    'comments' => $info['comments']['count'],
                    'created_at' => date('Y-m-d', $info['created_time'])
                ];
                $photo->setData($data);
            }
            $photo->save();
        }
    }

    /**
     * check whether the photo is exited
     *
     * @param $photoId
     * @return \Magento\Framework\DataObject
     */
    protected function photoExist($photoId)
    {
        return $this->photoFactory->create()
            ->getCollection()
            ->addFieldtoFilter('photo_id', $photoId)
            ->getFirstItem();
    }
}
