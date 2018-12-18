<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/login", name="connexion")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $messageErreur = $authenticationUtils->getLastAuthenticationError();


        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'messageErreur' => $messageErreur
        ]);
    }

    /**
     * @Route("/VerifConnexion", name="verifconnexion")
     */

    //fonction qui permet de se connecter si l'on est admin
    public function verifConnexion(Request $request){




$messageErreur = '';

//je recupère les valeur de mon formulaire
$pseudo = $request->get("_username");
$mdp = $request->get("_password");

//je tente de recupérer l'uttilisateur en base de donné
$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['pseudo' => $pseudo, 'mdp' => $mdp ]);


//si il n'est pas vide
if (!empty($user)){

    //je vérifie son role
    $role = $user->getTypeUserId()->getRole();

    //si il n'est pas administrateur alors je lui envoi un message d'erreur
    if ($role === "admin"){

        return $this->redirectToRoute('api');

    }else{

        $messageErreur = "Désolé, cette espace est réservé à l'administrateur du site";

    }




}else{


    $messageErreur = "Mot de passe ou pseudo incorecte";


}

        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'messageErreur' => $messageErreur
        ]);

    }


    /**
     * @Route("/logout", name="deconexion")
     */

    public function deconnexion(){}
}
