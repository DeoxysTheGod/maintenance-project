<?php

namespace App\Controller;

use App\Service\AccessManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Security $security, AccessManager $accessManager): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $accessData = $accessManager->getAccessibleRegionsAndDepartments($user);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user'            => $user,
            'regions'         => $accessData['regions'],
            'departments'     => $accessData['departments'],
        ]);
    }
}
