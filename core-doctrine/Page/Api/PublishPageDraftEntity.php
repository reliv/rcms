<?php

namespace Zrcms\CoreDoctrine\Page\Api;

use Doctrine\ORM\EntityManager;
use Zrcms\Core\Page\Api\PublishPageDraft;
use Zrcms\Core\Page\Model\PageDraft;
use Zrcms\Core\Page\Model\PagePublished;
use Zrcms\CoreDoctrine\Page\Entity\PageHistory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PublishPageDraftEntity implements PublishPageDraft
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param PageDraft $page
     * @param string    $modifiedByUserId
     * @param string    $modifiedReason
     * @param array     $options
     *
     * @return PagePublished
     */
    public function __invoke(
        PageDraft $page,
        string $modifiedByUserId,
        string $modifiedReason,
        array $options = []
    ): PagePublished
    {
        $pagePublishedRepository = $this->entityManager->getRepository(
            PagePublished::class
        );

        /** @var PagePublished $existingPage */
        $existingPage = $pagePublishedRepository->find($page->getUri());

        if ($existingPage) {
            $pageHistory = new PageHistory(
                $existingPage->getUri(),
                $existingPage->getProperties(),
                $existingPage->getBlockInstances(),
                $modifiedByUserId,
                $modifiedReason,
                $existingPage->getTrackingId()
            );

            $this->entityManager->persist($pageHistory);
            $this->entityManager->flush($pageHistory);

            $this->entityManager->remove($existingPage);
            $this->entityManager->flush($existingPage);
        }

        $newPage = new \Zrcms\CoreDoctrine\Page\Entity\PagePublished(
            $page->getUri(),
            $page->getProperties(),
            $page->getBlockInstances(),
            $modifiedByUserId,
            $modifiedReason,
            $page->getTrackingId()
        );

        $this->entityManager->persist($newPage);
        $this->entityManager->flush($newPage);

        $this->entityManager->remove($page);
        $this->entityManager->flush($page);

        return $newPage;
    }
}