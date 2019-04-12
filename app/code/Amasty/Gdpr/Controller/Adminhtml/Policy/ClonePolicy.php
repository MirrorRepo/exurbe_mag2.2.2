<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Controller\Adminhtml\Policy;

use Amasty\Gdpr\Api\PolicyRepositoryInterface;
use Amasty\Gdpr\Controller\Adminhtml\AbstractPolicy;
use Amasty\Gdpr\Model\Policy;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Amasty\Gdpr\Model\PolicyFactory;

class ClonePolicy extends AbstractPolicy
{
    /**
     * @var PolicyRepositoryInterface
     */
    private $policyRepository;

    /**
     * @var PolicyFactory
     */
    private $policyFactory;

    /**
     * ClonePolicy constructor.
     *
     * @param Context $context
     * @param PolicyRepositoryInterface $policyRepository
     * @param PolicyFactory $policyFactory
     */
    public function __construct(
        Context $context,
        PolicyRepositoryInterface $policyRepository,
        PolicyFactory $policyFactory
    ) {
        parent::__construct($context);
        $this->policyRepository = $policyRepository;
        $this->policyFactory = $policyFactory;
    }

    /**
     * Clone Action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->policyRepository->getById($id);
                $policy = $this->policyFactory->create();
                $policy->setComment($model->getComment());
                $policy->setContent($model->getContent());
                $policy->setStatus(Policy::STATUS_DRAFT);
                $policy = $this->policyRepository->save($policy);

                return $this->_redirect('*/*/edit', ['id' => $policy->getId()]);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This policy no longer exists.'));
            } catch (CouldNotSaveException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
}
