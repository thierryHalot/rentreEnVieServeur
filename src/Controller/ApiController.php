<?php

namespace App\Controller;

use App\Entity\BlackList;
use App\Entity\HeaderMsg;
use App\Entity\Msg;
use App\Entity\News;
use App\Entity\TypeUser;
use App\Repository\MsgRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Validator\Constraints\DateTime;

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

    /**
     * @Route("/api/getNews", name="getallNews")
     */

    //Retourne toute les news au format json
    public function getAllNews()
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");
        //je recupère la listes de toutes les news
        $news = $this->getDoctrine()->getRepository(News::class)->findAll();


        //je les renvoi au format json
        return $this->json($news);
    }


    /**
     * @Route("/api/getBlacklist/{id}", name="getBlacklist")
     */

    //Retourne tous les utilisateur blacklister d'un utilisateur
    public function getBlacklistUser($id)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");
        //je recupère les utilisateur blacklister correspondant a un utilisateur
        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $id]);


        //je les renvoi au format json
        return $this->json($blacklist);
    }


    /**
     * @Route("/api/putBlacklist/{idUserBloquand}/{idUserBloquer}", name="putBlacklist")
     */

    //fonction qui permet de mettre a jour la blacklist d'un utililisateur

    public function putBlacklistUser($idUserBloquand,$idUserBloquer,Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        $reponse = new Response();

        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $idUserBloquand, "bloquer_id" => $idUserBloquer ]);

        //je vérifie la blacklist existe et que je récupère des données
        if (!empty($blacklist && $request->getContent())){

            $date = new \DateTime($request->get('date'));
            $userBloquer = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquer_id"));

            //je vérifie si la date envoyé n'est pas null ou vide
            if ($date != null && !empty($date)) {
                $blacklist->setDate($date);
            }

            //je verifie si le nouvelle utilisateur existe en bdd
            if(!empty($userBloquer)){

                $blacklist->setBloquerId($userBloquer);

            }

            //j'insere les données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blacklist);
            $entityManager->flush();

            //je renvoi un status 200
            $reponse->setStatusCode('200');

            //sinon je renvoi un code d'erreur
        }else{

            $reponse->setStatusCode('404');
        }


        return $reponse;

    }


    /**
     * @Route("/api/postBlacklist", name="postBlacklist")
     */

    //Permet d'inserer un utilisateur en blacklist
    public function postBlacklistUser(Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");


        //j'instencie une nouvelle blacklist ainsi qu'une nouvelle reponse
        $blacklist = new BlackList();
        $reponse = new Response();

        //je vérifie si je récupère bien des données
        if($request->getContent()){


            $date = new \DateTime($request->get('date'));

            //je tente de récupèrer les utiliszteur correspondant aux données envoyé
            $userBloquer = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquer_id"));
            $userBloquand = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquand_id"));

            //si les deux utilisateur existe et que je recupere bien la date alors je peut persister ma blacklist
            //j'envoi donc un status 200
            if(!empty($userBloquand) && !empty($userBloquer) && !empty($date) && $date != null){

                $blacklist->setBloquerId($userBloquer);
                $blacklist->setBloquandId($userBloquand);
                $blacklist->setDate($date);

                //je verifie si l'utilisateur n'a pas déja été blacklister
                $blacklistVerif = $this->getDoctrine()->getRepository(BlackList::class)->findOneBy(["bloquand_id" => $userBloquand->getId(), "bloquer_id" => $userBloquer->getId() ]);

             if(empty($blacklistVerif)){


                 //j'insere les données
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($blacklist);
                 $entityManager->flush();

                 //je renvoi un status 200
                 $reponse->setStatusCode('200');


                 //si l'utilisateur est deja blacklister, j'envoi un message d'erreur
             }else{

                 $reponse->setStatusCode('404');
             }


                //sinon j'envoi une erreur une erreur
            }else{

                $reponse->setStatusCode('404');

            }

         //si je ne recupere aucune donné j'envoi une erreur
        }else{

            $reponse->setStatusCode('404');

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
        header("Access-Control-Allow-Origin: *");
        //je recupère les utilisateur blacklister correspondant a un utilisateur

        $reponse = new Response();
        $blacklist = $this->getDoctrine()->getRepository(BlackList::class)->find($idblacklist);

        //si ma blacklist existe alors je la supprime et je renvoi un statut 200
        if (!empty($blacklist)){

    //j'insere les données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($blacklist);
    $entityManager->flush();

    $reponse->setStatusCode('200');

        }else{

    $reponse->setStatusCode('404');
        }

        return $reponse;
    }




    /**
     * @Route("/api/getConversation/{idEmetteur}/{idRecepteur}", name="getConversation")
     */

    //Retourne la conversation entre deux membres ou juste les message de l'emmetteur dans le cas ou il n'a pas eu de réponse
    public function getConversationUser($idEmetteur,$idRecepteur)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");




        $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur , "recepteur_id" => $idRecepteur ]);

        $headerRecepteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idRecepteur , "recepteur_id" => $idEmetteur ]);

//si l'emeteur a envoyer un message mais qu'il n'a pas eu de reponce
        if (empty($headerRecepteur) && !empty($headerEmetteur)){

            //dans ce cas la conversation est a sens unique, je recupere juste les message de l'emetteur
    $conversation = $this->getDoctrine()->getRepository(Msg::class)->findBy(['msg_id' => $headerEmetteur->getId()]);

    //si l'emeteur et le recepteur communique entre eux
        }else if (!empty($headerEmetteur) && !empty($headerRecepteur)){

            //dans ce cas je récupère leurs messages
            $conversation = $this->getDoctrine()->getRepository(Msg::class)->getConversation($headerEmetteur->getId(), $headerRecepteur->getId());

            //dans tous les autre cas, la conversation n'existe pas
        }else{

    $conversation = null;
        }




       //je retourne la conversation au format json
        return $this->json($conversation);
    }



    /**
     * @Route("/api/postMsg/{idEmetteur}/{idRecepteur}", name="postMsg")
     */

    //cette fonction permet de poster un nouveaux message à un utilisateur
    public function postMsgUser($idEmetteur,$idRecepteur, Request $request){

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je récupère les deux uttilisateur qui communique entre eux

        $userEmetteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idEmetteur);

        $userRecepteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idRecepteur);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();




        //je verifie si ils existent bien en bdd
        if(!empty($userEmetteur) && !empty($userRecepteur)){

            //je tente de récupéré une éventuel conversation en cour entre l'emetteur et le recepteur

            $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur , "recepteur_id" => $idRecepteur ]);


            //objet du message correspond aux pseudo du membre
            $objet = $userEmetteur->getPseudo();

            //le contenu lui est récupéré par rapport aux donné envoyé
            $contenu = $request->get("contenu");


            //la date courrante
            $currentDate = new \DateTime();

            //si la conversation existe
            if(!empty($headerEmetteur)){



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

            }else{

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

        }else{

            $reponse->setStatusCode('404');
        }




        return $reponse;


    }


    /**
     * @Route("/api/GetHeaderMsg/{idUser}", name="getHeaderMsg")
     */

    //cette fonction retourne toutes les entete de message correspondant a un utilisateur
    public function getAllHeaderMsgUser($idUser){

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je récupère l'utilisateur
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        //si mon utilisateur existe
        if(!empty($user)){

            //je récupere toute les entete des conversation de cette utilisateur
            $headerMsg = $this->getDoctrine()->getRepository(HeaderMsg::class)->findBy(["emetteur_id" => $idUser]);



//si il n'existe pas je retourne null
        }else{

            $headerMsg = null;

        }

//j'envoi les données au format json
        return $this->json($headerMsg);

}

    /**
     * @Route("/api/getAllHeaderMsg", name="getAllHeaderMsg")
     */

    //cette fonction renvoi toutes les entetes de msg de tous les utilisateur au format json
    public function getAllHeaderMsgUsers(){

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");


        //je recupere toutes les entetes de message en bdd
        $allHeaderMsg = $this->getDoctrine()->getRepository(HeaderMsg::class)->findAll();

        //je les renvoi au format json
        return $this->json($allHeaderMsg);

    }

    
}
