<?php

namespace App\Controller;

use App\Entity\BlackList;
use App\Entity\HeaderMsg;
use App\Entity\Msg;
use App\Entity\News;
use App\Entity\Recherche;
use App\Entity\TypeUser;
use App\Entity\Vote;
use App\Entity\User;
use App\Repository\MsgRepository;
use function MongoDB\BSON\toJSON;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route("api/get/user/{id}", name="apiGetUser")
     */
//fonction qui permet de récupéré un utilisateur par rapport a son id et de le renvoyé au format json
    public function apiGetUser($id)
    {
        //dans les entete de la requete je permet l'accses a tous les supports

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);

        if(!empty($user)){

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent($user->getJson());
            $reponse->setStatusCode('200');

        }else{


            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas");



        }

        return $reponse;
    }


    /**
     * @Route("api/postImgUser/{id}", name="apiPostImgUser")
     */

    //cette methode permet de stocker les images de profils des utilisateur, crée un repertoire correspondant a l'utilisateur
    //prend en argument l'id de l'utilisateur
    public function postImgUser($id, Request $request){

        //j'instancie une nouvelle réponse
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je récupère l'uttilisateur suivant son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);


        if (!empty($user)) {

            $img = $request->files->get('img');

            $status = array('upload' => false);

            if(!is_null($img)){

                //je génère un nom image unique
                $nomImg = uniqid().".".$img->getClientOriginalExtension();

                //repertoire ou l'image doit etre stocké, j'utilise le pseudo pour crée un repertoire pour chaque utilisateur
                $repertoire = "build/img/".$user->getPseudo();

                //je stocke l'image dans le répertoire
                $img->move($repertoire,$nomImg);

                //je persiste le nom de l'image correspondant a mon utilisateur
                $user->setImg($nomImg);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($user);
                $entityManager->flush();
                $status = array('upload' => true);

            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($status));
            $reponse->setStatusCode('200');


        }else {

                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur : l'utilisateur n'existe pas ou les donné envoyé sont incorecte");

            }

            return $reponse;
    }
    /**
     * @Route("api/put/user/{id}", name="apiPutUser")
     */
//fonction qui permet de mettre à jour un utilisateur selon son id
    public function apiPutUser($id, Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Access-Control-Allow-Origin: *");

        //j'instancie une nouvelle réponse
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je récupère l'uttilisateur suivant son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);

        //je vérifie dans un premier temp que je récupère bien des données et que l'utilisateur existe en bdd
        if (!empty($user)) {
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
            $modeSortie = $request->get('modeSortie');
            $perimetre = $request->get('perimetre');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $isDel = $request->get('isDel');


            if ($nom != "null" && !empty($nom)) {

                $user->setNom($nom);

            }

            if ($prenom != "null" && !empty($prenom)) {

                $user->setPrenom($prenom);

            }

            if ($age != "null" && !empty($age)) {

                $user->setAge($age);

                $trancheAge = "";

                if ($age <= 24 && $age >= 18) {

                    $trancheAge = "18-24";

                } else if ($age >= 25 && $age <= 35) {

                    $trancheAge = "25-35";

                } else if ($age > 35) {

                    $trancheAge = "35+";

                }

                $user->setTrancheAge($trancheAge);

            }

            if ($sexe != null && !empty($sexe)) {

                $user->setSexe($sexe);
            }

            if ($tel != null && !empty($tel)) {

                $user->setTel($tel);
            }

            if ($adresse != null && !empty($adresse)) {

                $user->setAdresse($adresse);
            }

            if ($mail != null && !empty($mail)) {

                $user->setMail($mail);
            }
            if ($pseudo != null && !empty($pseudo)) {

                $user->setPseudo($pseudo);
            }

            if ($mdp != null && !empty($mdp)) {

                $user->setMdp($mdp);
            }

            if ($fumeur != null) {

                $user->setFumeur($fumeur);
            }

            if ($clubFavoris != null && !empty($clubFavoris)) {

                $user->setClubFavoris($clubFavoris);
            }
            if ($musiqueFavoris != null && !empty($musiqueFavoris)) {

                $user->setMusiqueFavoris($musiqueFavoris);
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

            $entityManager->merge($user);
            $entityManager->flush();

            $reponse->setStatusCode('200');

            //sinon j'envoi une erreur
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas ou les donné envoyé sont incorecte");

        }

        return $reponse;

    }

    /**
     * @Route("api/post/user", name="apiPostUser")
     */
//fonction qui permet d'inserer un nouvelle utilisateur
    public function apiPostUser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Allow-Control-Allow-Origin: *");
        // header('Access-Control-Allow-Origin: *');
        //header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");
        //je récupère le role a affecter à mon nouvelle utilisateur par rapport a son id
        //$role = $this->getDoctrine()->getRepository(TypeUser::class)->find($idRole);

        //existe et que je récupere des données
        if ($request->getContent()) {

            //j'instancie un nouvelle utilisateur
            $user = new \App\Entity\User();

            //je recupere les valeurs envoyée que je stocke dans des variables
            $nom = $request->get("nom");
            $prenom = $request->get('prenom');
            $age = $request->get('age');
            $sexe = $request->get('sexe');
            $tel = $request->get('tel');
            $adresse = $request->get('adresse');
            $mail = $request->get('mail');
            $pseudo = $request->get('pseudo');
            $mdp = $request->get('mdp');
            $fumeur = json_decode($request->get('fumeur'), true);
            $clubFavoris = $request->get('clubFavoris');
            $musiqueFavoris = $request->get('musiqueFavoris');
            $img = $request->get('img');
            $modeSortie = json_decode($request->get('modeSortie'), true);
            $perimetre = $request->get('perimetre');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $isDel = json_decode($request->get('isDel'), true);

            $trancheAge = "";

            if ($age <= 24 && $age >= 18) {

                $trancheAge = "18-24";

            } else if ($age >= 25 && $age <= 35) {

                $trancheAge = "25-35";

            } else if ($age > 35) {

                $trancheAge = "35+";

            }

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
            //$user->setTypeUserId($role);
            $user->setTrancheAge($trancheAge);
            $user->setIsDel($isDel);

            //avant de persister je hache le mdp
            $hash = $encoder->encodePassword($user,$user->getMdp());

            $user->setMdp($hash);
            //je le persiste en db et j'envoi un code 200
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

//            $reponse->headers->set('Content-Type', 'text/plain');
//            $reponse->setContent('mdp crypter');
            $reponse->setStatusCode('200');


            //sinon j'envoi un status d'erreur
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : le role n'existe pas, ou les donné envoyé sont vide ");

        }

        return $reponse;
    }

    /**
     * @Route("/api/getNews", name="getallNews")
     */

    //Retourne toute les news au format json
    public function getAllNews()
    {
        //dans les entete de la requete je permet l'accses a tous les supports

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je recupère la listes de toutes les news
        $news = $this->getDoctrine()->getRepository(News::class)->findAll();

        $tableauNews = array();

        foreach($news as $new){

            array_push($tableauNews,$new->getTabAsso());

        }
        $reponse->headers->set('Content-Type', 'application/json');
        $reponse->setContent(json_encode($tableauNews));
        $reponse->setStatusCode('200');
        //je les renvoi au format json
        return $reponse;
    }


    /**
     * @Route("/api/getBlacklist/{id}", name="getBlacklist")
     */

    //Retourne tous les utilisateur blacklister d'un utilisateur
    public function getBlacklistUser($id)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        //je recupère les utilisateur blacklister correspondant a un utilisateur
        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->findBy(["bloquand_id" => $id]);


        $tabBlacklist = array();

        foreach($blacklist as $user){

            array_push($tabBlacklist,$user->getTabAssoBlacklist());

    };

        $reponse->headers->set('Content-Type', 'application/json');
        $reponse->setContent(json_encode($tabBlacklist));
        $reponse->setStatusCode('200');
        //je les renvoi au format json
        return $reponse;
    }

    /**
     * @Route("/api/getIfUserIsInBlacklist/{idUserBloquand}/{idUserBloquer}", name="getIfUserIsInBlacklist")
     */

    //cette methode renvoi true si l'utilisateur a été blacklister ou false dans le cas contraire
    function getIfUserIsInBlacklist($idUserBloquand, $idUserBloquer){

        //je tente de récupéré l'utilisateur blacklister par rapport a son id
        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $idUserBloquand, "bloquer_id" => $idUserBloquer]);

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //j'initialise un boolean
        $drapeau = false;
        $tableau = array();

        //si ma blacklist n'est pas vide c'est que l'utilisateur est present dans la blacklist
        if(!empty($blacklist)){

            $drapeau = true;

        };
        //dans tous les autre cas l'utilisateur n'est pas blacklister
        $tableau['verifBlacklist'] = $drapeau;

        //je renvois mon tableau au format json
        $reponse->headers->set('Content-Type', 'application/json');
        $reponse->setContent(json_encode($tableau));
        $reponse->setStatusCode('200');

        return $reponse;
    }

    /**
     * @Route("/api/putBlacklist/{idUserBloquand}/{idUserBloquer}", name="putBlacklist")
     */

    //fonction qui permet de mettre a jour la blacklist d'un utililisateur

    public function putBlacklistUser($idUserBloquand, $idUserBloquer, Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Access-Control-Allow-Origin: *");

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $idUserBloquand, "bloquer_id" => $idUserBloquer]);

        //je vérifie la blacklist existe et que je récupère des données
        if (!empty($blacklist && $request->getContent())) {


            $userBloquer = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquer_id"));


            //je verifie si le nouvelle utilisateur existe en bdd
            if (!empty($userBloquer)) {

                $blacklist->setBloquerId($userBloquer);
                $blacklist->setDate(new \DateTime());

                //j'insere les données
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($blacklist);
                $entityManager->flush();

                //je renvoi un status 200
                $reponse->setStatusCode('200');

                //si l'utilisateur a bloquer n'existe pas, je renvoi une erreurs
            } else {

                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur : l'utilisateur à bloquer n'existe pas.");

            }


            //sinon je renvoi un code d'erreur
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : cette utilisateur n'est pas blacklister ou les données envoyé sont vide.");
        }


        return $reponse;

    }
    /**
     * @Route("/api/getNewMdp", name="getNewMdp")
     */

    public function getNewMdp(Request $request, \Swift_Mailer $mailer){

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


     //je recupere le mail envoyé par l'utilisateur
     $mail = $request->get('mail');

     //je tente de récupéré l'utilisateur correspondant a ce mail


     $moi = 'halotthierry34@gmail.com';

     $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findOneBy(["mail" => $mail]);

//si mon utilisateur n'est pas vide, c'est qu'il existe en bdd
     if(!empty($user)) {

         //je definie une chaine de caractere contenant toutes les lettres de l'alphabet et tous les chiffres
         $string ='abcdefghijklmnopqrstuvwxyz123456789';

         //je les mélanges
         $newMdp = str_shuffle($string);

         //je garde que les 6 premiers caractere qui correspondra a mon nouveau de passe
         $newMdp = substr($newMdp,0,6);

         $user->setMdp($newMdp);

         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($user);
         $entityManager->flush();

         //je definie mon mail contenant le mot de passe généré
         $message = (new \Swift_Message('RentreEnVie : Nouveau mot de passe'))
             ->setFrom($moi)
             ->setTo($mail)
             ->setBody(
                 'Bonjour '.$user->getPseudo().',<br>Voici votre nouveau mot de passe : '.$newMdp. '<br>pensez à le changer une fois connecter sur le site',
                 'text/html'
             );


         $mailer->send($message);

         //si le mail a été retrouver alors je definie mon statue a true
         $status = array('verifMail' => true);




     }else{

         //si l'utilisateur n'a pas été trouver, le mail est incorecte, je definie mon statue a false

         $status = array('verifMail' => false);


     }

        //je retourne le statue de mon mail au format json
        $reponse->headers->set('Content-Type', 'application/json');
        $reponse->setContent(json_encode($status));
        $reponse->setStatusCode('200');
        return $reponse;
    }

    /**
     * @Route("/api/sendMsgContact", name="sendMsgContact")
     */

    //cette methode permet de regevoir le mail correspondant au formulaire de contact
    public function sendMsgContact(Request $request, \Swift_Mailer $mailer){


        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        //je recupere le mail envoyé par l'utilisateur
        $mail = $request->get('mail');

        $msgUser = $request->get('message');

        $nom = $request->get('nom');




        $moi = 'halotthierry34@gmail.com';

        //je tente de récupéré l'utilisateur correspondant a ce mail
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findOneBy(["mail" => $mail]);



//si mon utilisateur n'est pas vide, c'est qu'il existe en bdd,
//si le nom qui la renseigné ne correspond pas avec les données récupérées, c'est que le nom est incorecte
        if(!empty($user) && $nom == $user->getNom()) {

            //si je reçoi bien un msg j'envoi mon mail
            if ($msgUser !== null) {

                //je definie mon mail contenant le mot de passe généré
                $message = (new \Swift_Message('Contact RentreEnVie : ' . $user->getMail()))
                    ->setFrom($moi)
                    ->setTo($moi)
                    ->setBody(

                        "Nom : ".$user->getNom()."<br>".
                        "Prenom : ".$user->getPrenom()."<br>".
                        "Pseudo : ".$user->getPseudo()."<br>".
                        "Tel : 0".$user->getTel()."<br>".
                        "Adresse : ".$user->getAdresse()."<br>".
                        "Mail : ".$user->getMail()."<br>".
                        "<br>Contenue du message : <br>".$msgUser,
                        'text/html'
                    );


                $mailer->send($message);

            }

            //si le mail a été retrouver alors je definie mon statue a true
            $status = array('verifMail' => true);




        }else{

            //si l'utilisateur n'a pas été trouver, le mail est incorecte, je definie mon statue a false

            $status = array('verifMail' => false);


        }

        //je retourne le statue de mon mail au format json
        $reponse->headers->set('Content-Type', 'application/json');
        $reponse->setContent(json_encode($status));
        $reponse->setStatusCode('200');
        return $reponse;
    }


    /**
     * @Route("/api/postBlacklist", name="postBlacklist")
     */

    //Permet d'inserer un utilisateur en blacklist
    public function postBlacklistUser(Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports



        //j'instencie une nouvelle blacklist ainsi qu'une nouvelle reponse
        $blacklist = new BlackList();
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je vérifie si je récupère bien des données
        if ($request->getContent()) {


            //je tente de récupèrer les utiliszteur correspondant aux données envoyé
            $userBloquer = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquerId"));
            $userBloquand = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquandId"));

            //si les deux utilisateur existe et que je recupere bien la date alors je peut persister ma blacklist
            //j'envoi donc un status 200
            if (!empty($userBloquand) && !empty($userBloquer)) {

                $blacklist->setBloquerId($userBloquer);
                $blacklist->setBloquandId($userBloquand);
                $blacklist->setDate(new \DateTime());

                //je verifie si l'utilisateur n'a pas déja été blacklister
                $blacklistVerif = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $userBloquand->getId(), "bloquer_id" => $userBloquer->getId()]);

                if (empty($blacklistVerif)) {


                    //j'insere les données
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($blacklist);
                    $entityManager->flush();

                    //je renvoi un status 200
                    $reponse->setStatusCode('200');


                    //si l'utilisateur est deja blacklister, j'envoi un message d'erreur
                } else {

                    $reponse->setStatusCode('404');
                    $reponse->setContent("l'utilisateur a deja été blacklister");
                }


                //sinon j'envoi une erreur une erreur
            } else {

                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur : un ou plusieurs des utilisateurs selectionner n'existe pas");

            }

            //si je ne recupere aucune donné j'envoi une erreur
        } else {

            $reponse->setStatusCode('404');
            $reponse->setStatusCode("Erreur : je n'ai reçu aucune donné à traiter");

        }

        return $reponse;
    }


    /**
     * @Route("/api/deleteBlacklist/{idblacklist}", name="deleteBlacklist")
     */

    //Retourne tous les utilisateur blacklister d'un utilisateur
    public function deleteBlacklist($idblacklist)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Access-Control-Allow-Origin: *");
        //je recupère les utilisateur blacklister correspondant a un utilisateur

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');

        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->find($idblacklist);

        //si ma blacklist existe alors je la supprime et je renvoi un statut 200


            //j'insere les données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blacklist);
            $entityManager->flush();
            
           
            $reponse->setStatusCode('200');


        return $reponse;

    }




    /**
     * @Route("/api/getConversation/{idEmetteur}/{idRecepteur}", name="getConversation")
     */

    //Retourne la conversation entre deux membres ou juste les message de l'emmetteur dans le cas ou il n'a pas eu de réponse
    public function getConversationUser($idEmetteur, $idRecepteur)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
       // header("Access-Control-Allow-Origin: *");


        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur, "recepteur_id" => $idRecepteur]);

        $headerRecepteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idRecepteur, "recepteur_id" => $idEmetteur]);
        $tableau = array();
//si l'emeteur a envoyer un message mais qu'il n'a pas eu de reponce
        if (empty($headerRecepteur) && !empty($headerEmetteur)) {

            //dans ce cas la conversation est a sens unique, je recupere juste les message de l'emetteur
            $conversation = $this->getDoctrine()->getRepository(Msg::class)->findBy(['msg_id' => $headerEmetteur->getId()]);



            foreach ($conversation as $msg) {

                array_push($tableau, $msg->getTabAssoMsg());
            }


            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($tableau));
            $reponse->setStatusCode('200');

            //dump($conversation);
            //si l'emeteur et le recepteur communique entre eux
        } else if (!empty($headerEmetteur) && !empty($headerRecepteur)) {

            //dans ce cas je récupère leurs messages
            $conversation = $this->getDoctrine()->getRepository(Msg::class)->getConversation($headerEmetteur->getId(), $headerRecepteur->getId());


            foreach ($conversation as $msg) {

                array_push($tableau, $msg->getTabAssoMsg());
            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($tableau));
            $reponse->setStatusCode('200');

            //dans tous les autre cas, la conversation n'existe pas
        } else {

            $reponse->setStatusCode("404");
            $reponse->setContent("Erreur : la conversation n'existe pas");
            $reponse->headers->set('Content-Type', 'text/plain');
        }


        //je retourne la conversation au format json
        //return $this->json($conversation);
        return $reponse;
    }



    /**
     * @Route("/api/postMsg/{idEmetteur}/{idRecepteur}", name="postMsg")
     */

    //cette fonction permet de poster un nouveaux message à un utilisateur
    public function postMsgUser($idEmetteur, $idRecepteur, Request $request)
    {




        //je récupère les deux uttilisateur qui communique entre eux

        $userEmetteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idEmetteur);

        $userRecepteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idRecepteur);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        //je verifie si ils existent bien en bdd
        if (!empty($userEmetteur) && !empty($userRecepteur)) {

            //je tente de récupéré une éventuel conversation en cour entre l'emetteur et le recepteur

            $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur, "recepteur_id" => $idRecepteur]);


            //objet du message correspond aux pseudo du membre
            $objet = $userEmetteur->getPseudo();

            //le contenu lui est récupéré par rapport aux donné envoyé
            $contenu = $request->get("contenu");


            //la date courrante
            $currentDate = new \DateTime();

            //si la conversation existe
            if (!empty($headerEmetteur)) {

                //je crée mon mon message avec les donnée recupéré
                $msg = new Msg();

                $msg->setMsgId($headerEmetteur);

                $msg->setObjet($objet);

                $msg->setContenu($contenu);

                //je lui affecte la date courante

                $msg->setDate($currentDate);

                //je persiste mon message

                $entityManager->persist($msg);

                $entityManager->flush();

                $reponse->setStatusCode('200');

                //si la conversation n'existe pas alors je vais la crée

            } else {

                //je crée l'entete du msg
                $headerEmetteur = new HeaderMsg();

                $headerEmetteur->setDate($currentDate);
                $headerEmetteur->setEmetteurId($userEmetteur);
                $headerEmetteur->setRecepteurId($userRecepteur);

                //par default le msg n'est pas supprimmer donc je lui met une valeur 0
                $headerEmetteur->setIsDel(0);

                $entityManager->persist($headerEmetteur);


                //je crée mon mon message avec les donnée recupéré
                $msg = new Msg();

                $msg->setMsgId($headerEmetteur);

                $msg->setObjet($objet);

                $msg->setContenu($contenu);

                //je lui affecte la date courante

                $msg->setDate($currentDate);

                //je persiste mon message

                $entityManager->persist($msg);

                $entityManager->flush();

                $reponse->setStatusCode('200');
            }

        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("Les utilisateur n'existe pas en base de donné");
        }


        return $reponse;


    }


    /**
     * @Route("/api/GetHeaderMsg/{idUser}", name="getHeaderMsg")
     */

    //cette fonction retourne toutes les entete de message correspondant a un utilisateur dont la valeur de is_del est egal a 0
    public function getAllHeaderMsgUser($idUser)
    {

        //dans les entete de la requete je permet l'accses a tous les supports
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je récupère l'utilisateur
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        //si mon utilisateur existe
        if (!empty($user)) {

            //je récupere toute les entete des conversation de cette utilisateur
            $headerMsgs = $this->getDoctrine()->getRepository(HeaderMsg::class)->findBy(["emetteur_id" => $idUser,"is_del"=> 0]);

            
        $tabHeaderMsg = array();

        foreach($headerMsgs as $headerMsg){

            $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $headerMsg->getRecepteurId()->getId(), "bloquer_id" => $headerMsg->getEmetteurId()->getId()]);
            
            if(empty($blacklist)) {
                array_push($tabHeaderMsg, $headerMsg->getTabAsso());
            }

        }
            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($tabHeaderMsg));
            $reponse->setStatusCode('200');

//si il n'existe pas je retourne null
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("L'utilisateur n'existe pas en base de donné");

        }

//j'envoi les données au format json
        return $reponse;

    }

    /**
     * @Route("/api/getAllHeaderMsg", name="getAllHeaderMsg")
     */

    //cette fonction renvoi toutes les entetes de msg de tous les utilisateur au format json
    public function getAllHeaderMsgUsers()
    {

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");


        //je recupere toutes les entetes de message en bdd
        $allHeaderMsg = $this->getDoctrine()->getRepository(HeaderMsg::class)->findAll();

        //je les renvoi au format json
        return $this->json($allHeaderMsg);

    }


    /**
     * @Route("/api/postHeaderMsgUser/{idEmetteur}", name="postHeaderMsgUser")
     */
    public function postHeaderMsgUser($idEmetteur, Request $request){

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        $idRecepteur = $request->get('recepteur_id');

        //je recupere les deux utilisateurs
        $userEmetteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idEmetteur);

        $userRecepteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idRecepteur);


        $entityManager = $this->getDoctrine()->getManager();


        //je vérifie que mes deux utilisateur existe
        if (!empty($userEmetteur) && !empty($userRecepteur)) {

            //j'essai de récupéré une éventuel conversation entre les deux utilisateurs
            $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur, "recepteur_id" => $idRecepteur]);

            $headerRecepteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idRecepteur, "recepteur_id" => $idEmetteur]);

            //si je ne récupère aucune conversation alors je peux faire une insertion
            if (empty($headerEmetteur) && empty($headerRecepteur)) {



                //la date courrante
                $currentDate = new \DateTime();
                //dans ce cas je lui crée une entete de message
                $headerEmetteur = new HeaderMsg();

                $headerEmetteur->setDate($currentDate);
                $headerEmetteur->setEmetteurId($userEmetteur);
                $headerEmetteur->setRecepteurId($userRecepteur);
                //par default le msg n'est pas supprimmer donc je lui met une valeur 0
                $headerEmetteur->setIsDel(0);

                $entityManager->persist($headerEmetteur);

                $entityManager->flush();

                //meme chose en sens inverse, je par du principe qu'il y aura une reponce du recepteur
                // qui deviendra alors un emetteur
                $headerRecepteur = new HeaderMsg();

                $headerRecepteur->setDate($currentDate);
                $headerRecepteur->setRecepteurId($userEmetteur);
                $headerRecepteur->setEmetteurId($userRecepteur);
                $headerRecepteur->setIsDel(0);

                $entityManager->persist($headerRecepteur);

                $entityManager->flush();

                $reponse->setStatusCode("200");


            //si une conversation est deja en cours, dans ce cas j'envoi un msg d'erreur
            }else{

                $reponse->setStatusCode("404");
                $reponse->headers->set('Content-Type', 'text/plain');
                $reponse->setContent("Erreur : Ce membre a déja une conversation avec cette autre membre");



            }



        //si un des deux utilisateur n'existe pas, je renvoi un message d'erreur
        }else{

            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');
            $reponse->setContent("Erreur : Un ou plusieurs des utilisateurs n'existe pas en bdd");

        }


        return $reponse;
    }

    /**
     * @Route("/api/putHeaderMsgUser/{idEmetteur}/{idRecepteur}", name="putHeaderMsgUser")
     */

    //cette fonction permet de mettre a jour la valeur is_del dans l'entete de message d'un utilisateur emetteur
    public function putHeaderMsgUser($idEmetteur, $idRecepteur, Request $request)
    {

        //dans les entete de la requete je permet l'accses a tous les supports


        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        //je recupere les deux utilisateurs
        $userEmetteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idEmetteur);

        $userRecepteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idRecepteur);

        $entityManager = $this->getDoctrine()->getManager();
        //je vérifie si il existe en bdd sinon j'envoi un message d'erreur
        if (!empty($userEmetteur) && !empty($userRecepteur)) {

            $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur, "recepteur_id" => $idRecepteur]);

            //je verifie que l'emmeteur a deja envoyé un message au recepteur sinon j'envoi un message d'erreur
            if (!empty($headerEmetteur)) {

                //je recupere la valeur envoyé
                $isDel = $request->get("is_del");

                //je verifie que les données envoyées sois correcte sinon j'envoi un msg d'erreur
                if ($isDel !== null) {

                    //je met a jour l'entete de l'emetteur que je persiste, j'envoi un statut 200
                    $headerEmetteur->setIsDel($isDel);

                    $entityManager->persist($headerEmetteur);

                    $entityManager->flush();


                }

                $reponse->setStatusCode("200");

            } else {

                $reponse->setStatusCode("404");
                $reponse->setContent("Erreur : l'emmeteur n'a jamais envoyé de message au recepteur");

            }

        } else {

            $reponse->setStatusCode("404");
            $reponse->setContent("Erreur : Un ou plusieurs des utilisateurs n'existe pas en bdd");

        }

        return $reponse;


    }
    /**
     * @Route("/api/getIfHeaderMsgExist/{idEmetteur}/{idRecepteur}", name="getIfHeaderMsgExist")
     */


    //cette methode permet de connaitre si une conversation existe entre deux membres
    public function getIfHeaderMsgExist($idEmetteur, $idRecepteur){




        //je tente de récupéré l'entete de message de l'utilisateur par rapport a l'id de l'emmetteur et du recepteur
        $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur, "recepteur_id" => $idRecepteur]);

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //j'initialise un boolean
        $drapeau = false;
        $tableau = array();

        //si j'arrive a récupérer l'entete corespondant a une conversion entre ces deux utilisateur, c'est quelle existe
        if(!empty($headerEmetteur)){

            //mon boulean vaudra true
            $drapeau = true;

        };
        //dans tous les autre cas il n'y a aucune conversation entre ces deux membres, mon boolean vaudra false

        $tableau['verifHeaderMsg'] = $drapeau;

        //je renvois mon tableau au format json
        $reponse->headers->set('Content-Type', 'application/json');
        $reponse->setContent(json_encode($tableau));
        $reponse->setStatusCode('200');

        return $reponse;
    }



    /**
     * @Route("/api/postPreferenceUser/{idUser}", name="postPreferenceUser")
     */

    //cette fonction permet d'insérer des préferences correspondant a un utilisateur
    public function postPreferenceUser($idUser, Request $request)
    {

        //dans les entete de la requete je permet l'accses a tous les supports
        // header("Access-Control-Allow-Origin: *");

        //je récupère l'utilisateur

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $reponse = new  Response();

        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");
        $reponse->headers->set('Content-Type', 'text/plain');


        //je teste si l'utilisateur existe et que je reçois bien des données

        if (!empty($user) && !empty($request) && $request != null) {

            //je vérifie si il n'a pas deja des préférence enregistrer

            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findOneBy(["recherchant_id" => $idUser]);

            $entityManager = $this->getDoctrine()->getManager();


            //je recupères les imformations envoyé que je stocke dans des variables

            $sexe = $request->get('sexe');
            $trancheAge = $request->get('trancheAge');
            $fumeur = $request->get('fumeur');
            $musique = $request->get('musiqueFavoris');
            $club = $request->get('clubFavoris');
            $statut = $request->get('statut');


            if ($sexe == "indifferent") {

                $sexe = null;
            }

            if ($trancheAge == "indifferent") {

                $trancheAge = null;

            }
            if ($fumeur == "indifferent") {

                $fumeur = null;
            }
            if ($musique == "indifferent") {

                $musique = null;
            }
            if ($club == "indifferent") {

                $club = null;
            }

            if ($statut == "indifferent") {

                $statut = null;
            }
            //je remplie mon tableau suivant ce que l'uttilisateur a rechercher
            $tableauRecherche = array();

            if ($sexe != null) {

                $tableauRecherche['sexe'] = $sexe;
            }

            if ($trancheAge != null) {

                $tableauRecherche['trancheAge'] = $trancheAge;
            }

            if ($fumeur != null) {

                $tableauRecherche['fumeur'] = $fumeur;
            }

            if ($musique != null) {

                $tableauRecherche['musique_favoris'] = $musique;
            }

            if ($club != null) {

                $tableauRecherche['club_favoris'] = $club;
            }

            if ($statut != null) {

                $idStatut = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => $statut]);
                $tableauRecherche['type_user_id'] = $idStatut;
            }


            //je recherche tous les utilisateur qui correspond à la recherche en donnant a manger mon tableau
            $resultatRecherche = $this->getDoctrine()
                ->getRepository(\App\Entity\User::class)
                ->findBy($tableauRecherche);

            //si ces préférences sont vide alors je vais les crées

            $tableauJsonRecherche = array();

            foreach ($resultatRecherche as $userRecherche) {

                array_push($tableauJsonRecherche, $userRecherche->getTabAsso());

            }
            if (empty($preference)) {

                $preference = new Recherche();

                $preference->setSexe($sexe);
                $preference->setTrancheAge($trancheAge);
                $preference->setFumeur($fumeur);
                $preference->setMusiqueFavoris($musique);
                $preference->setClubFavoris($club);
                $preference->setStatut($statut);
                $preference->setRecherchantId($user);

                $preference->setResultatRecherche($tableauJsonRecherche);

                $entityManager->persist($preference);
                $entityManager->flush();
                $reponse->setStatusCode('200');
                //si l'utilisateur a deja des préférence enregistré alors je met à jour ces préférences avec les imformations envoyées
            } else {

                $preference->setSexe($sexe);
                $preference->setTrancheAge($trancheAge);
                $preference->setFumeur($fumeur);
                $preference->setMusiqueFavoris($musique);
                $preference->setClubFavoris($club);
                $preference->setStatut($statut);
                $preference->setRecherchantId($user);
                $preference->setResultatRecherche($tableauJsonRecherche);

                $entityManager->persist($preference);
                $entityManager->flush();
                $reponse->setStatusCode('200');
                $reponse->headers->set('Content-Type', 'application/json');
                $reponse->setContent(json_encode($tableauJsonRecherche));
            }
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas ou je ne reçois aucune données");

        }

        return $reponse;
    }


    /**
     * @Route("/api/getPreference/{idUser}", name="getPreferenceUser")
     */

    //cette fonction renvoi les preference d'un utilisateur
    public function getPreferenceUsers($idUser)
    {

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        //je verifie si l'utilisateur existe
        if (!empty($user)) {

            //je tente de recuperer ces préference, renvera null si elle n'existe pas
            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findBy(["recherchant_id" => $idUser]);

        } else {

            $preference = null;
        }


        //je les renvoi au format json
        return $this->json($preference);

    }


    /**
     * @Route("/api/putPreference/{idUser}", name="putPreferenceUser")
     */

    //cette fonction met a jour les preference d'un utilisateur
    public function putPreferenceUser($idUser, Request $request)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        $reponse = new  Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        //je récupère l'utilisateur

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        //je teste si l'utilisateur existe et que je reçois bien des données

        if (!empty($user) && !empty($request) && $request != null) {

            //je vérifie si il n'a pas deja des préférence enregistrer

            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findOneBy(["recherchant_id" => $idUser]);

            $entityManager = $this->getDoctrine()->getManager();


            //je recupères les imformations envoyé que je stocke dans des variables

            $sexe = $request->get('sexe');
            $trancheAge = $request->get('trancheAge');
            $fumeur = $request->get('fumeur');
            $musique = $request->get('musiqueFavoris');
            $club = $request->get('clubFavoris');
            $statut = $request->get('statut');


            if ($sexe == "indifferent") {

                $sexe = null;
            }

            if ($trancheAge == "indifferent") {

                $trancheAge = null;

            }
            if ($fumeur == "indifferent") {

                $fumeur = null;
            }
            if ($musique == "indifferent") {

                $musique = null;
            }
            if ($club == "indifferent") {

                $club = null;
            }

            if ($statut == "indifferent") {

                $statut = null;
            }


            //je remplie mon tableau suivant ce que l'uttilisateur a rechercher
            $tableauRecherche = array();

            if ($sexe != null) {

                $tableauRecherche['sexe'] = $sexe;
            }

            if ($trancheAge != null) {

                $tableauRecherche['trancheAge'] = $trancheAge;
            }

            if ($fumeur != null) {

                $tableauRecherche['fumeur'] = $fumeur;
            }

            if ($musique != null) {

                $tableauRecherche['musique_favoris'] = $musique;
            }

            if ($club != null) {

                $tableauRecherche['club_favoris'] = $club;
            }

            if ($statut != null) {

                $idStatut = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => $statut]);
                $tableauRecherche['type_user_id'] = $idStatut;
            }


            //je recherche tous les utilisateur qui correspond à la recherche en donnant a manger mon tableau
            $resultatRecherche = $this->getDoctrine()
                ->getRepository(\App\Entity\User::class)
                ->findBy($tableauRecherche);


            //si ces préference ne sont pas vide, c'est quelle existe donc je les mets a jour
            if (!empty($preference)) {

                $tableauJsonRecherche = array();


                foreach ($resultatRecherche as $userRecherche) {

                    array_push($tableauJsonRecherche, $userRecherche->getTabAsso());

                }


                $preference->setSexe($sexe);
                $preference->setTrancheAge($trancheAge);
                $preference->setFumeur($fumeur);
                $preference->setMusiqueFavoris($musique);
                $preference->setClubFavoris($club);
                $preference->setStatut($statut);
                $preference->setRecherchantId($user);
                $preference->setResultatRecherche($tableauJsonRecherche);

                $entityManager->merge($preference);
                $entityManager->flush();
                $reponse->setStatusCode('200');


                //si elle n'existe pas, j'envoi un message d'erreur
            } else {

                $reponse->headers->set('Content-Type', 'text/plain');
                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur :cette utilisateur n'a actuellement aucune préférence, il faut don faire une insertion plutot qu'une mise a jour");

            }


        } else {
            $reponse->headers->set('Content-Type', 'text/plain');
            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas ou je ne reçois aucune données");


        }

        return $reponse;
    }

    /**
     * @Route("/api/getRoleUser/{idUser}", name="getRoleUser")
     */

    //fonction qui retourne le role d'un membre
    public function getRoleUser($idUser)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        //je teste si il existe
        if (!empty($user)) {
            //si il existe je recupere son role
            $role = $user->getTypeUserId();

        } else {
            //sinon il n'y pas de role
            $role = null;

        }

//je renvoi son role au format json
        return $this->json($role);
    }

    /**
     * @Route("/api/postRoleUser/{idUser}", name="postRoleUser")
     */
    //cette fonction permet de d'affecter un role à un membre
    public function postRoleUser($idUser, Request $request)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        $reponse = new  Response();
        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $entityManager = $this->getDoctrine()->getManager();

        //je recupere les donné envoyé et je recherche le role par rapport a ses donées
        $newRole = $request->get('role');
        $role = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => $newRole]);

        //je teste si l'utilisateur et le role existe
        if (!empty($user) && !empty($role)) {

            //je tente de verifier si l'uttilisateur possede deja un role
            $currentRole = $user->getTypeUserId();

            //si l'uttilisateur n'a pas de role je le crée sinon je retourne un message d'erreur
            if ($currentRole === null) {

                //j'affecte le role a l'uttilisateur et je le persiste
                $user->setTypeUserId($role);

                $entityManager->persist($user);

                $entityManager->flush();

                //je renvoi un statut 200
                $reponse->setStatusCode('200');


            } else {

                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur : Un role à deja été attribué à cette utilisateur");

            }
            //si le role ou l'uttilisateur n'existe pas je renvoi un message d'erreur
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur ou le role n'existe pas");
        }

        return $reponse;
    }

    /**
     * @Route("/api/putRoleUser/{idUser}", name="putRoleUser")
     */
    //cette fonction permet de mettre a jour le role d'un membre
    public function putRoleUser($idUser, Request $request)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Access-Control-Allow-Origin: *");

        $reponse = new  Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");
        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $entityManager = $this->getDoctrine()->getManager();

        //je recupere les donné envoyé et je recherche le role par rapport a ses donées
        $newRole = $request->get('role');

        $role = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => $newRole]);

        //je teste si l'utilisateur et le role existe
        if (!empty($user) ) {

            //je tente de verifier si l'uttilisateur possede deja un role
            $currentRole = $user->getTypeUserId();

            //si l'uttilisateur a deja un role alors je peut le mettre a jour
           // if ($currentRole !== null) {
            if(!empty($role)) {
                //je met a jour le role de l'utilisateur et je le persiste
                $user->setTypeUserId($role);

                $entityManager->persist($user);

                $entityManager->flush();
            }
                //je renvoi un statut 200
                $reponse->setStatusCode('200');

                //sinon je retourne un message d'erreur
           // } else {

               // $reponse->setStatusCode('404');
                //$reponse->setContent("l'utilisateur n'a actuellement aucun role, il faut donc d'abord lui crée un role avant de le mettre à jour !!!");

           // }
            //si le role ou l'uttilisateur n'existe pas je renvoi un message d'erreur
        } else {

            $reponse->setStatusCode('404');
            $reponse->setContent("<p>Mise à jour impossible, le role ou l'utilisateur n'existe pas !!!!<br>
            la clé du tableau a renvoyé est : role, la valeur doit etre sam ou consomateur. </p>");
        }

        return $reponse;
    }
    /**
     * @Route("/api/getStatutUser/{idUser}", name="getStatutUser")
     */

    //fonction qui retourne le statut d'un membre
    public function getStatutUser($idUser)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        //je teste si il existe
        if (!empty($user)) {
            //si il existe je recupere son statut


            $statut = array(

                "mode_sortie" => $user->getModeSortie()

            );

        } else {
            //sinon il n'y pas de statut
            $statut = null;

        }

//je renvoi son statut au format json
        return $this->json($statut);
    }


    /**
     * @Route("/api/putStatutUser/{idUser}", name="putStatutUser")
     */

    //fonction qui met a jour le statut d'un membre
    public function putStatutUser($idUser, Request $request)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Access-Control-Allow-Origin: *");



        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");
        //je recupère les données
        $statut = $request->get('mode_sortie');

        //je teste si l'uttilisateur existe et si je reçois des données correcte, si tous en bon
        if (!empty($user)) {


//je met a jour le status de l'uttilisateur, je le persiste et j'envois un statue 200
            $user->setModeSortie($statut);

            $entityManager->persist($user);

            $entityManager->flush();

            $reponse->setStatusCode('200');

            //sinon j'envoi un message d'erreur
        } else {
            //sinon il n'y pas de statut
            $reponse->setStatusCode('404');
            $reponse->setContent("l'utilisateur n'existe pas ou les donné indiqué sont incorrecte");

        }

//je renvoi son statut au format json
        return $reponse;
    }


    /**
     * @Route("/api/getNoteUser/{idUser}", name="getNoteUser")
     */

//fonction qui permet de renvoyé toutes les notes d'un membre au format json
    public function getNoteUser($idUser)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
       // header("Access-Control-Allow-Origin: *");

        //je recupere l'utilisateur
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        $response = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je teste si il existe
        if (!empty($user)) {

            $note = $this->getDoctrine()->getRepository(Vote::class)->findBy(["voter_id" => $idUser]);


            //si l'utilisateur n'a aucune note, alors je retourne null
            if (empty($note)) {


                $note = null;

            }

            //si il n'existe pas, je retourne un message d'erreur;
        } else {

            $response->setStatusCode('404');

            $response->setContent("erreur : l'utilisateur n'existe pas");

            return $response;
        }

        return $this->json($note);

    }

    /**
     * @Route("/api/postNoteUser/{idUserVotant}/{idUserVoter}", name="postNoteUser")
     */

//fonction qui permet d'insérer une note a un membre
    public function postNoteUser($idUserVotant, $idUserVoter, Request $request)
    {

        //je recupere les utilisateurs par rapport a leurs id
        $userVotant = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVotant);

        $userVoter = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVoter);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();

        //je verifie si les deux utilisateur existe
        if (!empty($userVotant) && !empty($userVoter)) {
            //je tente de savoir si l'utilisateur a déjé voter
            $vote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(["voter_id" => $idUserVoter, "votant_id" => $idUserVotant]);

            //si je ne récupère aucune imformation c'est que l'utilisateur n'a jamais voter
            if (empty($vote)) {

                $note = json_decode($request->get("note"), true);

                //si je recupere bien des données et que c'est bien un integer
                if ($note !== null && is_int($note)) {

                    //je recherche tous les votes de cette uttilisateur
                    $nombreVote = count($this->getDoctrine()->getRepository(Vote::class)->findBy(["voter_id" => $idUserVotant]));

                    //je crée un vote
                    $vote = new Vote();

                    //je lui affecte la date courante ainsi que les imformation concernant le vote
                    $vote->setDate(new \DateTime());
                    $vote->setVoterId($userVoter);
                    $vote->setVotantId($userVotant);
                    $vote->setNote($note);
                    $vote->setNbrVote($nombreVote);

                    //je persiste le vote et retourne un statut 200

                    $entityManager->persist($vote);

                    $entityManager->flush();

                    $reponse->setStatusCode('200');

                } else {


                    $reponse->setStatusCode("404");
                    $reponse->setContent("erreur : les données envoyé sont incorecte ");

                }


                //sinon l'utilisateur a déja voter, je renvoi donc un message d'erreur
            } else {

                $reponse->setStatusCode("404");
                $reponse->setContent("l'utilisateur à déja voter ce membres, il ne faut donc pas faire une insertion, il faut faire une mise a jour");


            }


            //si ils existent pas je renvoi un message d'erreur
        } else {

            $reponse->setStatusCode("404");
            $reponse->setContent("Information incorecte :Un ou plusieurs des uttilisateurs recherchés, n'existent pas en base de donnée");

        }


        return $reponse;
    }


    /**
     * @Route("/api/putNoteUser/{idUserVotant}/{idUserVoter}", name="putNoteUser")
     */

//fonction qui permet de mettre a jour la note d'un membre
    public function putNoteUser($idUserVotant, $idUserVoter, Request $request)
    {

        //je recupere les utilisateurs par rapport a leurs id
        $userVotant = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVotant);

        $userVoter = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVoter);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();

        //je verifie si les deux utilisateur existe
        if (!empty($userVotant) && !empty($userVoter)) {
            //je tente de savoir si l'utilisateur a déjé voter
            $vote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(["voter_id" => $idUserVoter, "votant_id" => $idUserVotant]);

            //si je récupère des imformations c'est que l'utilisateur a deja voter ce membre
            if (!empty($vote) && $vote !== null) {

                $note = json_decode($request->get("note"), true);

                //si je recupere bien des données et que c'est bien un integer
                if ($note !== null && is_int($note)) {

                    //je recherche tous les votes de cette uttilisateur
                    $nombreVote = count($this->getDoctrine()->getRepository(Vote::class)->findBy(["voter_id" => $idUserVotant]));

                    //je lui affecte la date courante ainsi que les imformation concernant le vote
                    $vote->setDate(new \DateTime());
                    $vote->setVoterId($userVoter);
                    $vote->setVotantId($userVotant);
                    $vote->setNote($note);
                    $vote->setNbrVote($nombreVote);

                    //je met a jour le vote du membre et je retourne un statut 200

                    $entityManager->persist($vote);

                    $entityManager->flush();

                    $reponse->setStatusCode('200');

                } else {


                    $reponse->setStatusCode("404");
                    $reponse->setContent("erreur : les données envoyé sont incorecte ");

                }


                //sinon l'utilisateur n'a jamais voter, je renvoi donc un message d'erreur
            } else {

                $reponse->setStatusCode("404");
                $reponse->setContent("cette utilisateur n'a jamais voter ce membre , il faut faire une insertion plutot qu'un mise a jour");


            }


            //si ils existent pas je renvoi un message d'erreur
        } else {

            $reponse->setStatusCode("404");
            $reponse->setContent("Information incorecte :Un ou plusieurs des uttilisateurs recherchés, n'existent pas en base de donnée");

        }


        return $reponse;
    }


    /**
     * @Route("/api/getAllPositions", name="getAllpositionsUser")
     */

    //cette fonction retourne toutes les position des utilisateur au format json
    public function getAllpositions()
    {

        //je recupere tous les uttilsateur
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();

        //si il y a des utilisateur en bdd
        if (!empty($users)) {
            //je declare un tableau d'utilisateurs
            $positionUsers = array();

            //je boucle pour récupéré les infos qui m'intéresse
            foreach ($users as $user) {

                $userPosition = array(

                    "id" => $user->getId(),
                    "pseudo" => $user->getPseudo(),
                    "longitude" => $user->getLongitude(),
                    "latitude" => $user->getLatitude(),
                    "perimetre" => $user->getPerimetre()

                );

                //jinsere mes utilisateurs
                array_push($positionUsers, $userPosition);
            }


        } else {
            //si j'ai rien bdd je retourne null
            $positionUsers = null;
        }

//je renvoi mon tableau aux format json
        return $this->json($positionUsers);


    }
    /**
     * @Route("/api/verifUpdateProfilUser/{id}", name="verifUpdateUser")
     */
    public function verifUpdateProfil($id,Request $request){

        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $mailUser = $request->get('mail');
        $pseudoUser = $request->get('pseudo');

    $currentUser = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($id);


        if(!empty($currentUser)) {
            //je verifie que mes donné envoyé ne sois pas vide
            if ( $mailUser !== null && $pseudoUser !== null) {
//je recupere les donnée envoyé

                //j'inisialise un tableau
                $reponseVerifUser = array();

                $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();



                //dans un un premier temp je boucle dans mes utilisateur pour rechercher si le pseudo a deja été utilisé
                foreach ($users as $user) {

                    $pseudoBdd = $user->getPseudo();

                    //si mon pseudo a deja été utiliser
                    if ($pseudoBdd == $pseudoUser && $pseudoBdd !== $currentUser->getPseudo()) {

                        //je rajoute une clé a false
                        $reponseVerifUser['validPseudo'] = false;

                        //je sors de ma boucle
                        break;


                        //si mon pseudo n'a jamais été utilisé ma clé vaut true
                    } else {

                        $reponseVerifUser['validPseudo'] = true;

                    }
                }

                //deuxieme boucle pour savoir si le mail a deja été utiliser
                foreach ($users as $user) {


                    $mailBdd = $user->getMail();

//                si le mail existe en bdd
                    if ($mailBdd == $mailUser && $mailBdd !== $currentUser->getMail()) {

                        //je renvoi ma clé a false
                        $reponseVerifUser['validMail'] = false;

                        //je sors de ma boucle
                        break;

                        //sinon la valeur de ma clé vaut true

                    } else {

                        $reponseVerifUser['validMail'] = true;

                    }


                }
                dump($users);
                $reponse->headers->set('Content-Type', 'application/json');
                $reponse->setContent(json_encode($reponseVerifUser));
                $reponse->setStatusCode('200');

                //si je ne récupère aucunne donné j'envoi un message d'erreur
            } else {


                $reponse->setStatusCode("404");
                $reponse->headers->set('Content-Type', 'text/plain');

                $reponse->setContent("les donné envoyé sont vide");

            }
        }else{
            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("cette utilisateur n'existe pas");


        }

        return $reponse;

    }
    /**
     * @Route("/api/verifInscUser", name="verifInscUser")
     */

    //cette methode permet de vérifier si le pseudo et le mail d'un utilisateur existe en bdd, si il existe il renvoi un tableau
    //le tableau contient de cle verifPseudo et verifMail qui ont toutes deux pour valeur un boolean
    //true pour indiquer que l'imformation a été trouver, false pour le cas contraire

    public function verifInscUser(Request $request)
    {


        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $mailUser = $request->get('mail');
        $pseudoUser = $request->get('pseudo');


        //je verifie que mes donné envoyé ne sois pas vide
        if ($request->getContent() && $mailUser !== null && $pseudoUser !== null) {
//je recupere les donnée envoyé

            //j'inisialise un tableau
            $reponseVerifUser = array();

            $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();


            //dans un un premier temp je boucle dans mes utilisateur pour rechercher si le pseudo a deja été utilisé
            foreach ($users as $user) {

                $pseudoBdd = $user->getPseudo();

                //si mon pseudo a deja été utiliser
                if ($pseudoBdd == $pseudoUser) {

                    //je rajoute une clé a false
                    $reponseVerifUser['validPseudo'] = false;

                    //je sors de ma boucle
                    break;


                    //si mon pseudo n'a jamais été utilisé ma clé vaut true
                } else {

                    $reponseVerifUser['validPseudo'] = true;

                }
            }

            //deuxieme boucle pour savoir si le mail a deja été utiliser
            foreach ($users as $user) {


                $mailBdd = $user->getMail();

//                si le mail existe en bdd
                if ($mailBdd == $mailUser) {

                    //je renvoi ma clé a false
                    $reponseVerifUser['validMail'] = false;

                    //je sors de ma boucle
                    break;

                    //sinon la valeur de ma clé vaut true

                } else {

                    $reponseVerifUser['validMail'] = true;

                }


            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($reponseVerifUser));
            $reponse->setStatusCode('200');

            //si je ne récupère aucunne donné j'envoi un message d'erreur
        } else {


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("les donné envoyé sont vide");

        }

        return $reponse;

    }
    /**
     * @Route("/api/verifConUser", name="verifConUser")
     */

    //cette methode permet de tester si un utilisateur existe en bdd par rapport a son pseudo,
    // si il existe il renvoi un tableau json contenant les info de l'utilisateur
    public function verifConUser(Request $request)
    {


        $pseudo = $request->get('pseudo');

        $mdp = $request->get('mdp');

        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findOneBy(["pseudo" => $pseudo]);



        if ($request->getContent() && $user != null) {

            $userTrouver = array(

                "nom" => $user->getNom()

            );

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent($user->getJson());
            $reponse->setStatusCode('200');


        } else {

            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("cette uttilisateur n'existe pas !");
        }

        return $reponse;

    }

    /**
     * @Route("/api/checkInfoConnexion", name="checkInfoConnexion")
     */

    public function checkInfoConnexion(Request $request)
    {


        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $mdpUser = $request->get('mdp');
        $pseudoUser = $request->get('pseudo');


        //je verifie que mes donné envoyé ne sois pas vide
        if ($request->getContent() && $mdpUser !== null && $pseudoUser !== null) {
//je recupere les donnée envoyé

            //j'inisialise un tableau
            $reponseVerifUser = array();

            $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();


            //dans un un premier temp je boucle dans mes utilisateur pour rechercher si le pseudo a deja été utilisé
            foreach ($users as $user) {

                $pseudoBdd = $user->getPseudo();

                //si mon pseudo a deja été utiliser
                if ($pseudoBdd == $pseudoUser) {

                    //je rajoute une clé a true
                    $reponseVerifUser['validPseudo'] = true;

                    //je sors de ma boucle
                    break;


                    //si mon pseudo n'a jamais été utilisé ma clé vaut false
                } else {

                    $reponseVerifUser['validPseudo'] = false;

                }
            }

            //deuxieme boucle pour savoir si le pseudo et mot de passe correspondent
            foreach ($users as $user) {


                $pseudoBdd = $user->getPseudo();
                //je tente de décrypter le mot de passe ,
                // password_verify return true si le mot de passe en clair correspond avec mon mot de passe crypter
                $mdpBdd = password_verify($mdpUser,$user->getMdp());

//                si mon mot de passe et mon nom d'utilisateur correspondent je renvoi true
                if ($mdpBdd && $pseudoUser == $pseudoBdd) {

                    //je renvoi ma clé a tru
                    $reponseVerifUser['validMdp'] = true;

                    //je sors de ma boucle
                    break;

                    //sinon la valeur de ma clé vaut false

                } else {

                    $reponseVerifUser['validMdp'] = false;

                }


            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($reponseVerifUser));
            $reponse->setStatusCode('200');

            //si je ne récupère aucunne donné j'envoi un message d'erreur
        } else {


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("les donné envoyé sont vide");

        }

        return $reponse;

    }


    /**
     * @Route("/api/checkIfPreference/{idUser}", name="checkIfPreference")
     */

    public function checkIfPreference($idUser)
    {


        //dans les entete de la requete je permet l'accses a tous les supports
        // header("Access-Control-Allow-Origin: *");

        //je récupère l'utilisateur

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $reponse = new  Response();

        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        //je teste si l'utilisateur existe et que je reçois bien des données

        if (!empty($user)) {

            //je vérifie si il n'a pas deja des préférence enregistrer

            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findOneBy(["recherchant_id" => $idUser]);

            $tableau = array();

            if ($preference == null) {

                $tableau['verifPref'] = false;

            } else {


                $tableau['verifPref'] = true;


            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($tableau));
            $reponse->setStatusCode('200');
        } else {

            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("l'Utilisateur n'existe pas");

        }

        return $reponse;

    }

    /**
     * @Route("/api/getRechercheUser/{idUser}", name="getRechercheUser")
     */

    //cette methode permet de renvoyer au format json le resultat de la recherche
    //d'un utilisateur par rapport a ses préférence ou bien si l'uttilisateur n'a pas de préférence,
    //la méthode renvoi tous les utilisateur en ligne du site au format json
    public function getRechercheUser($idUser)
    {


        $reponse = new  Response();
        $reponse->headers->set("Access-Control-Allow-Origin", '*');
        $reponse->headers->set('Access-Control-Allow-Credentials', true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je tente de récupérer l'utilisateur par rapport a son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        //je verifie si l'utilisateur existe en bdd
        if (!empty($user)) {

            //je tente de vérifier si l'utilisateur possede des préférence de recherche

            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findOneBy(["recherchant_id" => $idUser]);

            //si l'utilisateur a des préférences enregistrer
            if (!empty($preference)) {


                $entityManager = $this->getDoctrine()->getManager();


                //je recupères ces préference
                $sexe = $preference->getSexe();
                $trancheAge = $preference->getTrancheAge();
                $fumeur = $preference->getFumeur();
                $musique = $preference->getMusiqueFavoris();
                $club = $preference->getClubFavoris();
                $statut = $preference->getStatut();


                //je remplie mon tableau suivant ce que l'uttilisateur a rechercher
                $tableauRecherche = array();

                if ($sexe != null) {

                    $tableauRecherche['sexe'] = $sexe;
                }

                if ($trancheAge != null) {

                    $tableauRecherche['trancheAge'] = $trancheAge;
                }

                if ($fumeur != null) {

                    $tableauRecherche['fumeur'] = $fumeur;
                }

                if ($musique != null) {

                    $tableauRecherche['musique_favoris'] = $musique;
                }

                if ($club != null) {

                    $tableauRecherche['club_favoris'] = $club;
                }

                if ($statut != null) {

                    $idStatut = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => $statut]);
                    $tableauRecherche['type_user_id'] = $idStatut;
                }


                //je recherche tous les utilisateur qui correspond à la recherche en donnant a manger mon tableau
                $resultatRecherche = $this->getDoctrine()
                    ->getRepository(\App\Entity\User::class)
                    ->findBy($tableauRecherche);


                    $tableauJsonRecherche = array();


                    foreach ($resultatRecherche as $userRecherche) {

                        array_push($tableauJsonRecherche, $userRecherche->getTabAsso());

                    }


                    $preference->setSexe($sexe);
                    $preference->setTrancheAge($trancheAge);
                    $preference->setFumeur($fumeur);
                    $preference->setMusiqueFavoris($musique);
                    $preference->setClubFavoris($club);
                    $preference->setStatut($statut);
                    $preference->setRecherchantId($user);
                    $preference->setResultatRecherche($tableauJsonRecherche);

                    $entityManager->merge($preference);
                    $entityManager->flush();

                    //je recherche le resultat de sa recherche par rapport au préférence qu'il a renseigné
                    $resultaRecherche = $tableauJsonRecherche;


                    //sinon l'utilisateur n'a aucune préférence enregistrer, dans ce cas
                } else {

                    //je recherche tous les uttilisateurs qui sont en ligne

                    $resultatRecherche = $this->getDoctrine()->getRepository(User::class)->findBy(["mode_sortie" => 1]);


                    $tableauJsonRecherche = array();


                     foreach ($resultatRecherche as $userRecherche) {

                        array_push($tableauJsonRecherche, $userRecherche->getTabAsso());

                    }

                $resultaRecherche = $tableauJsonRecherche;

                }


                //je renvoi l'imformation au format json
                $reponse->headers->set('Content-Type', 'application/json');
                $reponse->setContent(json_encode($resultaRecherche));
                $reponse->setStatusCode('200');

                //si il n'existe pas je renvoi un message d'erreur
            } else {

                $reponse->setStatusCode("404");
                $reponse->headers->set('Content-Type', 'text/plain');
                $reponse->setContent("l'Utilisateur n'existe pas");

            }

            return $reponse;
        }

    /**
     * @Route("/api/getIfPositionExist/{idUser}", name="getIfPositionExist")
     */

    //cette methode permet de d'indiqué si la position de l'utilisateur a deja été inséré en bdd
    public function getIfPositionExist($idUser){

        $reponse = new  Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je tente de récupérer l'utilisateur par rapport a son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        if(!empty($user)){


            //je tente de récupérer sa lattitude et sa longitude
            $latitudeUser = $user->getLatitude();
            $longitudeUser = $user->getLongitude();

            //je declare un tableau
            $tableauJson = array();

            //si sa latitude et sa longitude son différent de null, alors sa position existe
            if ($latitudeUser !== null && $longitudeUser !== null){

                //je rajoute une clé avec la valeur true pour prévenir de son existence
                $tableauJson['verifPosition'] = true;

                //sinon ma clé vaut false pour prévenir de sa non-existence
            }else{


                $tableauJson['verifPosition'] = false;

            }


            //je renvoi l'information au format json
            $reponse->headers->set('Content-Type','application/json');
            $reponse->setContent(json_encode($tableauJson));
            $reponse->setStatusCode('200');


         //si l'uttilisateur n'existe pas je renvoi un message d'erreur
        }else{


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');
            $reponse->setContent("l'Utilisateur n'existe pas");

        }

        return $reponse;
    }

    /**
     * @Route("/api/postPositionUser/{idUser}", name="postPositionUser")
     */

    public function postPositionUser($idUser, Request $request){

        $reponse = new  Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je tente de récupérer l'utilisateur par rapport a son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);
        $entityManager = $this->getDoctrine()->getManager();

        if(!empty($user)){


            //je tente de récupérer les imformations envoyé
            $latitudeUser = $request->get('latitude');
            $longitudeUser = $request->get('longitude');

            //si l'uttilisateur n'a aucune position d'insérer
            if($user->getLongitude() == null && $user->getLatitude() == null) {

                //si sa latitude et sa longitude son différent de null, alors je peut faire mon insertion
                if ($latitudeUser !== null && $longitudeUser !== null) {


                    $user->setLatitude($latitudeUser);
                    $user->setLongitude($longitudeUser);

                    //je persiste la position de mon utilisateur
                    $entityManager->persist($user);
                    $entityManager->flush();


                    //je renvoi un statue 200
                    $reponse->setStatusCode('200');

                    //sinon j'nvoi un message d'erreur
                } else {

                    $reponse->setStatusCode("404");
                    $reponse->headers->set('Content-Type', 'text/plain');
                    $reponse->setContent("les donné envoyé sont vide");


                }


             //si l'utilisateur a deja une position d'insérer je renvoi un message d'erreur
            }else{


                $reponse->setStatusCode("404");
                $reponse->headers->set('Content-Type', 'text/plain');
                $reponse->setContent("l'Utilisateur a deja une position d'insérér");




            }
            //si l'uttilisateur n'existe pas je renvoi un message d'erreur
        }else{


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');
            $reponse->setContent("l'Utilisateur n'existe pas");

        }

        return $reponse;


    }

    /**
     * @Route("/api/putPositionUser/{idUser}", name="putPositionUser")
     */

    //cette methode permet de mettre a jour la positions d'un utilisateur
    public function putPositionUser($idUser, Request $request){

        $reponse = new  Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        //je tente de récupérer l'utilisateur par rapport a son id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);
        $entityManager = $this->getDoctrine()->getManager();


        $latitudeUser = $request->get("latitude");
        $longitudeUser = $request->get("longitude");

        if(!empty($user)){




                //je tente de récupérer les imformations envoyé


                //si sa latitude et sa longitude son différent de null, alors je peut faire mon insertion

            if($latitudeUser !== null){

                $user->setLatitude($latitudeUser);
            }
            if($longitudeUser !== null){

                $user->setLongitude($longitudeUser);
            }




                    //je persiste la position de mon utilisateur
                    $entityManager->merge($user);
                    $entityManager->flush();



                    $reponse->setStatusCode('200');

                    //sinon j'nvoi un message d'erreur


                //si sa position n' existe pas je renvoi un message d'erreur

            //si l'uttilisateur n'existe pas je renvoi un message d'erreur
        }else{


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');
            $reponse->setContent("l'Utilisateur n'existe pas");

        }

        return $reponse;


    }


}
