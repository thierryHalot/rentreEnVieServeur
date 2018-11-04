<?php

namespace App\Controller;

use Doctrine\DBAL\Schema\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestControlleurController extends AbstractController
{
    /**
     * @Route("/", name="test_controlleur")
     */
    public function index()
    {
        return $this->render('test_controlleur/index.html.twig', [
            'controller_name' => 'TestControlleurController',
        ]);
    }

    /**
     * @Route("/test", name="testRequete")
     */

    //test pour retourner du json
    public function test()
    {
        $tableau = array(

            "nom" => "titi"

        );

        return $this->json($tableau);

    }
}
