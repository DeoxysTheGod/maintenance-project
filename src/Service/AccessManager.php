<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\DepartmentsRepository;
use App\Repository\RegionsRepository;

class AccessManager
{
    public function __construct(
        private RegionsRepository $regionRepo,
        private DepartmentsRepository $departmentRepo,
    ) {
    }

    /**
     * Retrieves the regions and departments accessible to a given user.
     *
     * @param User $user the user for whom we want to retrieve access
     *
     * @return array An associative array containing:
     *               - 'regions': an array of accessible regions.
     *               - 'departments': an array of accessible departments.
     *
     * Behavior explanation:
     * - If the user has **no assigned region** (`null`), they have access to **all regions and all departments**.
     * - If the user has **a region but no department**, they have access **only to their region and all departments within it**.
     * - If the user has **both a region and a department**, they have access **only to their specific region and department**.
     */
    public function getAccessibleRegionsAndDepartments(User $user): array
    {
        if (null === $user->getRegion()) {
            return [
                'regions'     => $this->regionRepo->findAll(),
                'departments' => $this->departmentRepo->findAll(),
            ];
        } elseif (null === $user->getDepartment()) {
            return [
                'regions'     => [$user->getRegion()],
                'departments' => $this->departmentRepo->findBy(['region' => $user->getRegion()]),
            ];
        } else {
            return [
                'regions'     => [$user->getRegion()],
                'departments' => [$user->getDepartment()],
            ];
        }
    }
}
