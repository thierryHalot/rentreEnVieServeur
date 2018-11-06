<?php

namespace App\Controller;

use App\Entity\TypeUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/", name="api")
     */
    public function index($id)
    {

        dump($id);
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

    /**
     * @Route("api/get/user/{id}", name="apiGetUser")
     */
//fonction qui permet de récupéré un utilisateur par rapport a son id et de le renvoyé au format json
    public function apiGetUser($id)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");


        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);


        return $this->json($user);
    }

    /**
     * @Route("api/put/user/{id}", name="apiPutUser")
     */
//fonction qui permet de mettre à jour un utilisateur selon son id
    public function apiPutUser($id,Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //j'instancie une nouvelle réponse
        $reponse = new Response();


        //je récupère l'uttilisateur suivant son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);

        //je vérifie dans un premier temp que je récupère bien des données et que l'utilisateur existe en bdd
        if(!empty($user) && $request->getContent()) {
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
            $fumeur = $request->get('fumeur');
            $clubFavoris = $request->get('clubFavoris');
            $musiqueFavoris = $request->get('musiqueFavoris');
            $img = $request->get('img');
            $modeSortie = $request->get('modeSortie');
            $perimetre = $request->get('perimetre');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $isDel = $request->get('isDel');

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

            if ($fumeur != null && !empty($fumeur)) {

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
            if ($modeSortie != null && !empty($modeSortie)) {

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
            if ($isDel != null && !empty($isDel)) {

                $user->setIsDel($isDel);
            }

//je met a jour l'utilisateur et j'envoi un statue 200 pour prévenir que l'insertion c'est bien effectuer
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();


            $reponse->setStatusCode('200');

       //sinon j'envoi une erreur
        }else{

            $reponse->setStatusCode('404');

        }

        return $reponse;

    }

    /**
     * @Route("api/post/user/{idRole}", name="apiPostUser")
     */
//fonction qui permet d'inserer un nouvelle utilisateur
    public function apiPostUser($idRole,Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        $reponse = new Response();

        //je récupère le role a affecter à mon nouvelle utilisateur par rapport a son id
        $role = $this->getDoctrine()->getRepository(TypeUser::class)->find($idRole);

        //si mon role existe et que je récupere des données
        if(!empty($role) && $request->getContent()) {

            //j'instancie un nouvelle utilisateur
            $user = new \App\Entity\User();

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
            $fumeur = $request->get('fumeur');
            $clubFavoris = $request->get('clubFavoris');
            $musiqueFavoris = $request->get('musiqueFavoris');
            $img = $request->get('img');
            $modeSortie = $request->get('modeSortie');
            $perimetre = $request->get('perimetre');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $isDel = $request->get('isDel');

//j'affecte les donné a mon nouvelle utilisateur
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setAge($age);
            $user->setSexe($sexe);
            $user->setTel($tel);
            $user->setAdresse($adresse);
            $user->setMail($mail);
            $user->setPseudo($pseudo);
            $user->setMdp($mdp);
            $user->setFumeur($fumeur);
            $user->setClubFavoris($clubFavoris);
            $user->setMusiqueFavoris($musiqueFavoris);
            $user->setImg($img);
            $user->setModeSortie($modeSortie);
            $user->setPerimetre($perimetre);
            $user->setLatitude($latitude);
            $user->setLongitude($longitude);
            $user->setTypeUserId($role);
            $user->setIsDel($isDel);

            //je le persiste en db et j'envoi un code 200
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $reponse->setStatusCode('200');

         //sinon j'envoi un status d'erreur
        }else{

            $reponse->setStatusCode('404');

        }

        return $reponse;
    }


}
