<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/getusers", name="getallusers")
     */

    //Retourne la listes de tous les uttilisateur au format json
    public function getAllUsers()
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");
        //je recupère la listes de tous les utilisateur
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();


        //je les renvoi au format json
        return $this->json($users);
    }
}
