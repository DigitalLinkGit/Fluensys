<?php

namespace App\Service\Helper;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement;
use App\Entity\Project;
use App\Repository\ActivityLogRepository;

final readonly class ActivityLogProvider
{
    public function __construct(private ActivityLogRepository $repo)
    {
    }

    /**
     * @return array<\App\Entity\ActivityLog>
     */
    public function forProject(Project $project, int $limit = 200): array
    {
        return $this->repo->findForContext(
            accountId: null,
            projectId: $project->getId(),
            captureId: null,
            captureElementId: null,
            action: null,
            limit: $limit,
        );
    }

    /**
     * @return array<\App\Entity\ActivityLog>
     */
    public function forCapture(Capture $capture, int $limit = 200): array
    {
        $project = $capture->getOwnerProject();

        return $this->repo->findForContext(
            accountId: null,
            projectId: null,
            captureId: $capture->getId(),
            captureElementId: null,
            action: null,
            limit: $limit,
        );
    }

    /**
     * @return array<\App\Entity\ActivityLog>
     */
    public function forCaptureElement(CaptureElement $captureElement, int $limit = 200): array
    {
        $capture = $captureElement->getCapture();
        $project = $capture->getOwnerProject();

        return $this->repo->findForContext(
            accountId: null,
            projectId: null,
            captureId: null,
            captureElementId: $captureElement->getId(),
            action: null,
            limit: $limit,
        );
    }

    /**
     * @return array<\App\Entity\ActivityLog>
     */
    public function forAccount(Account $account, int $limit = 200): array
    {

        return $this->repo->findForContext(
            accountId: $account->getId(),
            projectId: null,
            captureId: null,
            captureElementId: null,
            action: null,
            limit: $limit,
        );
    }
}
