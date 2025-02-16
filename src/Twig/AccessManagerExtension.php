<?php

namespace App\Twig;

use App\Entity\User;
use App\Service\AccessManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AccessManagerExtension extends AbstractExtension
{
    private $accessManager;

    public function __construct(AccessManager $accessManager)
    {
        $this->accessManager = $accessManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_accessible_regions_and_departments', [$this, 'getAccessibleRegionsAndDepartments']),
        ];
    }

    public function getAccessibleRegionsAndDepartments(User $user): array
    {
        return $this->accessManager->getAccessibleRegionsAndDepartments($user);
    }
}
