<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GestionUsersController extends AbstractController
{
    /**
     * @Route("/gestion/users", name="gestion_users")
     */
    public function index()
    {
        return $this->render('gestion_users/index.html.twig', [
            'controller_name' => 'GestionUsersController',
        ]);
    }
}
