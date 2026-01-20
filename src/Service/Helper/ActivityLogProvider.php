<?php

namespace App\Service\Helper;

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
        $projectId = method_exists($capture, 'getProject') && $capture->getProject()
            ? $capture->getProject()->getId()
            : null;

        return $this->repo->findForContext(
            projectId: $projectId,
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
        $capture = method_exists($captureElement, 'getCapture') ? $captureElement->getCapture() : null;
        $projectId = ($capture && method_exists($capture, 'getProject') && $capture->getProject())
            ? $capture->getProject()->getId()
            : null;

        return $this->repo->findForContext(
            projectId: $projectId,
            captureId: $capture?->getId(),
            captureElementId: $captureElement->getId(),
            action: null,
            limit: $limit,
        );
    }
}
