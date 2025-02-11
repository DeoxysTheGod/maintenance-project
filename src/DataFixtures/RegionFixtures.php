<?php

namespace App\DataFixtures;

use App\Entity\Departments;
use App\Entity\Regions;
use App\Service\GeoApiService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    private GeoApiService $geoApiService;

    public function __construct(GeoApiService $geoApiService)
    {
        $this->geoApiService = $geoApiService;
    }

    public function load(ObjectManager $manager): void
    {
        $regionsData = $this->geoApiService->getRegionData();
        $regionMap   = [];

        // Récupérer les régions
        foreach ($regionsData as $regionData) {
            // Check if the region already exists
            $region = $manager->getRepository(Regions::class)->findOneBy(['code' => $regionData['code']]);

            if (!$region) {
                $region = new Regions();
                $region->setName($regionData['nom']);
                $region->setCode($regionData['code']);
                $manager->persist($region);
            }

            $this->addReference('region-'.$regionData['code'], $region);

            $regionMap[$regionData['code']] = $region;
        }

        // Récupérer les départements de chaque région
        foreach ($regionMap as $regionCode => $region) {
            $departmentsData = $this->geoApiService->getRegionDepartmentsData($regionCode);
            foreach ($departmentsData as $departmentData) {
                // Check if the department already exists in the region
                $department = $manager->getRepository(Departments::class)->findOneBy([
                    'name'   => $departmentData['nom'],
                    'region' => $region,
                    'code'   => $departmentData['codeRegion'],
                ]);

                if (!$department) {
                    $department = new Departments();
                    $department->setName($departmentData['nom']);
                    $department->setRegion($region);
                    $department->setCode($departmentData['code']);
                    $manager->persist($department);
                    // Adding reference to the department
                    $this->addReference('department-'.$departmentData['code'], $department);
                }
            }
        }

        // Flush all persisted entities at once
        $manager->flush();
    }
}
