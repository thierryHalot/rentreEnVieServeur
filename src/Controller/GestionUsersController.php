<?php

namespace App\Controller;

use App\Entity\TypeUser;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Response;

class GestionUsersController extends AbstractController
{
    /**
     * @Route("/gestionUsers", name="gestion_users")
     */

    public function index()
    {
        //je recupères tous mes utilisateur, je les injecte dans ma vue
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('gestion_users/index.html.twig', [
            'controller_name' => 'GestionUsersController',
            'users' => $users
        ]);
    }

    /**
     * @Route("/form/update/User/{id}", name="formUpdateUser")
     */
    public function updateFormUser($id, Request $request){

        //je récupère l'uttilisateur suivant son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);

            //je recupere les valeurs envoyée que je stocke dans des variables
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $age = $request->get('age');
            $sexe = $request->get('sexe');
            $tel = $request->get('tel');
            $adresse = $request->get('adresse');
            $mail = $request->get('mail');
            $pseudo = $request->get('pseudo');
            $mdp = $request->get('mdp');
            $fumeur = json_decode($request->get('fumeur'),true);
            $clubFavoris = $request->get('clubFavoris');
            $musiqueFavoris = $request->get('musiqueFavoris');
            $img = $request->get('img');
            $modeSortie = json_decode($request->get('modeSortie'),true);
            $perimetre = $request->get('perimetre');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $isDel = json_decode($request->get('isDel'),true);

            //je verifie les valeurs envoyé une a une, si elle ne sont pas vide et null, si elle rentre dans ces conditions
            // alors je peut les affectées à mon entité


            if ($nom != "null" && !empty($nom)) {

                $user->setNom($nom);

            }

            if ($prenom != "null" && !empty($prenom)) {

                $user->setPrenom($prenom);

            }

            if ($age != "null" && !empty($age)) {

                $user->setAge($age);
            }

            if ($sexe != "null" && !empty($sexe)) {

                $user->setSexe($sexe);
            }

            if ($tel != "null" && !empty($tel)) {

                $user->setTel($tel);
            }

            if ($adresse != "null" && !empty($sexe)) {

                $user->setAdresse($adresse);
            }

            if ($mail != "null" && !empty($mail)) {

                $user->setMail($mail);
            }
            if ($pseudo != null && !empty($pseudo)) {

                $user->setPseudo($pseudo);
            }

            if ($mdp != null && !empty($mdp)) {

                $user->setMdp($mdp);
            }

            if ($fumeur != null && $fumeur === 1 || $fumeur === 0) {

                $user->setFumeur($fumeur);
            }

            if ($clubFavoris != null && !empty($fumeur)) {

                $user->setClubFavoris($clubFavoris);
            }
            if ($musiqueFavoris != null && !empty($musiqueFavoris)) {

                $user->setMusiqueFavoris($musiqueFavoris);
            }
            if ($img != null && !empty($img)) {

                $user->setImg($img);
            }
            if ($modeSortie != null && $modeSortie === 1 || $modeSortie === 0) {

                $user->setModeSortie($modeSortie);
            }
            if ($perimetre != null && !empty($perimetre)) {
                $user->setPerimetre($perimetre);

            }
            if ($latitude != null && !empty($latitude)) {

                $user->setLatitude($latitude);
            }
            if ($longitude != null && !empty($longitude)) {

                $user->setLongitude($longitude);
            }
            if ($isDel != null && $isDel == 1 || $isDel == 0) {

                $user->setIsDel($isDel);
            }


            //permet d'envoyé un message sur la vue en meme temp que la redirection pour prévenir de l'insertion
            $this->addFlash(
              'messageUpdateUser',
              "l'utilisateur à bien été mis à jour"
            );
//je met a jour l'utilisateur et j'envoi un statue 200 pour prévenir que l'insertion c'est bien effectuer
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();


         //je redirige l'utilisateur sur la page courante
        return $this->redirectToRoute("gestion_users");

    }
}
