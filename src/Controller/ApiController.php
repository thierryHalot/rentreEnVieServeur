<?php

namespace App\Controller;

use App\Entity\BlackList;
use App\Entity\HeaderMsg;
use App\Entity\Msg;
use App\Entity\News;
use App\Entity\Recherche;
use App\Entity\TypeUser;
use App\Entity\Vote;
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

//je met a jour l'utilisateur et j'envoi un statue 200 pour prévenir que l'insertion c'est bien effectuer
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();


            $reponse->setStatusCode('200');

       //sinon j'envoi une erreur
        }else{

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas ou les donné envoyé sont incorecte");

        }

        return $reponse;

    }

    /**
     * @Route("api/post/user", name="apiPostUser")
     */
//fonction qui permet d'inserer un nouvelle utilisateur
    public function apiPostUser(Request $request)
    {
        //dans les entete de la requete je permet l'accses a tous les supports
        //header("Allow-Control-Allow-Origin: *");
       // header('Access-Control-Allow-Origin: *');
        //header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $reponse = new Response();
        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");
        //je récupère le role a affecter à mon nouvelle utilisateur par rapport a son id
        //$role = $this->getDoctrine()->getRepository(TypeUser::class)->find($idRole);

        //existe et que je récupere des données
        if($request->getContent()) {

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
            $fumeur = json_decode($request->get('fumeur'),true);
            $clubFavoris = $request->get('clubFavoris');
            $musiqueFavoris = $request->get('musiqueFavoris');
            $img = $request->get('img');
            $modeSortie = json_decode($request->get('modeSortie'),true);
            $perimetre = $request->get('perimetre');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $isDel = json_decode($request->get('isDel'),true);

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
            $user->setIsDel($isDel);

            //je le persiste en db et j'envoi un code 200
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $reponse->setStatusCode('200');



         //sinon j'envoi un status d'erreur
        }else{

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


            $userBloquer = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquer_id"));




            //je verifie si le nouvelle utilisateur existe en bdd
            if(!empty($userBloquer)){

                $blacklist->setBloquerId($userBloquer);
                $blacklist->setDate(new \DateTime());

                //j'insere les données
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($blacklist);
                $entityManager->flush();

                //je renvoi un status 200
                $reponse->setStatusCode('200');

                //si l'utilisateur a bloquer n'existe pas, je renvoi une erreurs
            }else{

                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur : l'utilisateur à bloquer n'existe pas.");

            }



            //sinon je renvoi un code d'erreur
        }else{

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : cette utilisateur n'est pas blacklister ou les données envoyé sont vide.");
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




            //je tente de récupèrer les utiliszteur correspondant aux données envoyé
            $userBloquer = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquer_id"));
            $userBloquand = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($request->get("bloquand_id"));

            //si les deux utilisateur existe et que je recupere bien la date alors je peut persister ma blacklist
            //j'envoi donc un status 200
            if(!empty($userBloquand) && !empty($userBloquer) && !empty($date) && $date != null){

                $blacklist->setBloquerId($userBloquer);
                $blacklist->setBloquandId($userBloquand);
                $blacklist->setDate(new \DateTime());

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
                 $reponse->setContent("l'utilisateur a deja été blacklister");
             }


                //sinon j'envoi une erreur une erreur
            }else{

                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur : un ou plusieurs des utilisateurs selectionner n'existe pas");

            }

         //si je ne recupere aucune donné j'envoi une erreur
        }else{

            $reponse->setStatusCode('404');
            $reponse->setStatusCode("Erreur : je n'ai reçu aucune donné à traiter" );

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
    $reponse->setContent("cette utilisateur n'existe pas en blacklist");
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


    $reponse = new Response();

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

    $reponse->setStatusCode("404");
    $reponse->setContent("Erreur : la conversation n'existe pas");
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
            $reponse->setContent("Les utilisateur n'existe pas en base de donné");
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

    /**
     * @Route("/api/putHeaderMsgUser/{idEmetteur}/{idRecepteur}", name="putHeaderMsgUser")
     */

   //cette fonction permet de mettre a jour la valeur is_del dans l'entete de message d'un utilisateur emetteur
    public function putHeaderMsgUser($idEmetteur,$idRecepteur,Request $request){

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        $reponse = new Response();
        //je recupere les deux utilisateurs
        $userEmetteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idEmetteur);

        $userRecepteur = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idRecepteur);

        $entityManager = $this->getDoctrine()->getManager();
        //je vérifie si il existe en bdd sinon j'envoi un message d'erreur
        if(!empty($userEmetteur) && !empty($userRecepteur)){

            $headerEmetteur = $this->getDoctrine()->getRepository(HeaderMsg::class)->findOneBy(["emetteur_id" => $idEmetteur , "recepteur_id" => $idRecepteur ]);

            //je verifie que l'emmeteur a deja envoyé un message au recepteur sinon j'envoi un message d'erreur
            if (!empty($headerEmetteur)){

                //je recupere la valeur envoyé
                $isDel = json_decode($request->get("is_del"),true);

                //je verifie que les données envoyées sois correcte sinon j'envoi un msg d'erreur
                if(!empty($isDel) && $isDel !== null && $isDel === 1 || $isDel === 0) {

                    //je met a jour l'entete de l'emetteur que je persiste, j'envoi un statut 200
                    $headerEmetteur->setIsDel($isDel);

                    $entityManager->persist($headerEmetteur);

                    $entityManager->flush();

                    $reponse->setStatusCode("200");
                }else{

                    $reponse->setStatusCode("404");
                    $reponse->setContent("Erreur : les données envoyé sont incorecte , rappelle la clé est is_del, la valeur est soit 1 si l'utilisateur à supprimmer son message, soit 0");

                }

            }else{

                $reponse->setStatusCode("404");
                $reponse->setContent("Erreur : l'emmeteur n'a jamais envoyé de message au recepteur");

            }

        }else{

            $reponse->setStatusCode("404");
            $reponse->setContent("Erreur : Un ou plusieurs des utilisateurs n'existe pas en bdd");

        }

        return $reponse;




    }

    /**
     * @Route("/api/postPreferenceUser/{idUser}", name="postPreferenceUser")
     */

    //cette fonction permet d'insérer des préferences correspondant a un utilisateur
    public function postPreferenceUser($idUser,Request $request){

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je récupère l'utilisateur

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $reponse = new  Response();

        //je teste si l'utilisateur existe et que je reçois bien des données

        if(!empty($user) && !empty($request) && $request != null){

            //je vérifie si il n'a pas deja des préférence enregistrer

            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findOneBy(["recherchant_id" => $idUser]);

            $entityManager = $this->getDoctrine()->getManager();


            //je recupères les imformations envoyé que je stocke dans des variables

            $sexe = $request->get('sexe');
            $age = $request->get('age');
            $fumeur = $request->get('fumeur');
            $musique = $request->get('musique_favoris');
            $club = $request->get('club_favoris');
            $statut = $request->get('statut');

            //je remplie mon tableau suivant ce que l'uttilisateur a rechercher
            $tableauRecherche = array();

            if($sexe != null){

                $tableauRecherche['sexe'] = $sexe;
            }

            if($age != null){

                $tableauRecherche['age'] = $age;
            }

            if($fumeur != null){

                $tableauRecherche['fumeur'] = $fumeur;
            }

            if($musique != null){

                $tableauRecherche['musique_favoris'] = $musique;
            }

            if($club != null){

                $tableauRecherche['club_favoris'] = $club;
            }

            if($statut != null){

                $idStatut = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => $statut]);
                $tableauRecherche['type_user_id'] = $idStatut;
            }


            //je recherche tous les utilisateur qui correspond à la recherche en donnant a manger mon tableau
            $resultatRecherche = $this->getDoctrine()
                ->getRepository(\App\Entity\User::class)
                ->findBy($tableauRecherche);

            //si ces préférences sont vide alors je vais les crées
            if(empty($preference)){

                $preference = new Recherche();

                $preference->setSexe($sexe);
                $preference->setAge($age);
                $preference->setFumeur($fumeur);
                $preference->setMusiqueFavoris($musique);
                $preference->setClubFavoris($club);
                $preference->setStatut($statut);
                $preference->setRecherchantId($user);
                $preference->setResultatRecherche(json_encode($resultatRecherche));

                $entityManager->persist($preference);
                $entityManager->flush();
                $reponse->setStatusCode('200');
                //si l'utilisateur a deja des préférence enregistré alors je met à jour ces préférences avec les imformations envoyées
            }else{

                $preference->setSexe($sexe);
                $preference->setAge($age);
                $preference->setFumeur($fumeur);
                $preference->setMusiqueFavoris($musique);
                $preference->setClubFavoris($club);
                $preference->setStatut($statut);
                $preference->setRecherchantId($user);
                $preference->setResultatRecherche($resultatRecherche);

                $entityManager->persist($preference);
                $entityManager->flush();
                $reponse->setStatusCode('200');
            }
        }else{

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas ou je ne reçois aucune données");

        }

return $reponse;
    }


    /**
     * @Route("/api/getPreference/{idUser}", name="getPreferenceUser")
     */

    //cette fonction renvoi les preference d'un utilisateur
    public function getPreferenceUsers($idUser){

        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        //je verifie si l'utilisateur existe
        if(!empty($user)){

            //je tente de recuperer ces préference, renvera null si elle n'existe pas
            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findBy(["recherchant_id" => $idUser]);

        }else{

            $preference = null;
        }



        //je les renvoi au format json
        return $this->json($preference);

    }


    /**
     * @Route("/api/putPreference/{idUser}", name="putPreferenceUser")
     */

    //cette fonction met a jour les preference d'un utilisateur
    public function putPreferenceUser($idUser, Request $request){



        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je récupère l'utilisateur

        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $reponse = new  Response();

        //je teste si l'utilisateur existe et que je reçois bien des données

        if(!empty($user) && !empty($request) && $request != null) {

            //je vérifie si il n'a pas deja des préférence enregistrer

            $preference = $this->getDoctrine()->getRepository(Recherche::class)->findOneBy(["recherchant_id" => $idUser]);

            $entityManager = $this->getDoctrine()->getManager();


            //je recupères les imformations envoyé que je stocke dans des variables

            $sexe = $request->get('sexe');
            $age = $request->get('age');
            $fumeur = json_decode($request->get('fumeur'),true);
            $musique = $request->get('musique_favoris');
            $club = $request->get('club_favoris');
            $statut = $request->get('statut');

            //je remplie mon tableau suivant ce que l'uttilisateur a rechercher
            $tableauRecherche = array();

            if ($sexe != null) {

                $tableauRecherche['sexe'] = $sexe;
            }

            if ($age != null) {

                $tableauRecherche['age'] = $age;
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
            if(!empty($preference)){


                $preference->setSexe($sexe);
                $preference->setAge($age);
                $preference->setFumeur($fumeur);
                $preference->setMusiqueFavoris($musique);
                $preference->setClubFavoris($club);
                $preference->setStatut($statut);
                $preference->setRecherchantId($user);
                $preference->setResultatRecherche($resultatRecherche);

                $entityManager->persist($preference);
                $entityManager->flush();
                $reponse->setStatusCode('200');


            //si elle n'existe pas, j'envoi un message d'erreur
            }else{


                $reponse->setStatusCode('404');
                $reponse->setContent("Erreur :cette utilisateur n'a actuellement aucune préférence, il faut don faire une insertion plutot qu'une mise a jour");

            }


        }else{

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur n'existe pas ou je ne reçois aucune données");


        }

            return $reponse;
        }

    /**
     * @Route("/api/getRoleUser/{idUser}", name="getRoleUser")
     */

    //fonction qui retourne le role d'un membre
    public function getRoleUser($idUser){


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        //je teste si il existe
         if(!empty($user)){
            //si il existe je recupere son role
            $role = $user->getTypeUserId();

            }else{
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
    public function postRoleUser($idUser, Request $request){


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
        if(!empty($user) && !empty($role)){

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


           }else{

               $reponse->setStatusCode('404');
               $reponse->setContent("Erreur : Un role à deja été attribué à cette utilisateur");

           }
           //si le role ou l'uttilisateur n'existe pas je renvoi un message d'erreur
        }else{

            $reponse->setStatusCode('404');
            $reponse->setContent("Erreur : l'utilisateur ou le role n'existe pas");
        }

        return $reponse;
    }

    /**
     * @Route("/api/putRoleUser/{idUser}", name="putRoleUser")
     */
    //cette fonction permet de mettre a jour le role d'un membre
    public function putRoleUser($idUser, Request $request){


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
        if(!empty($user) && !empty($role)){

            //je tente de verifier si l'uttilisateur possede deja un role
            $currentRole = $user->getTypeUserId();

            //si l'uttilisateur a deja un role alors je peut le mettre a jour
            if ($currentRole !== null) {

                //je met a jour le role de l'utilisateur et je le persiste
                $user->setTypeUserId($role);

                $entityManager->persist($user);

                $entityManager->flush();

                //je renvoi un statut 200
                $reponse->setStatusCode('200');

            //sinon je retourne un message d'erreur
            }else{

                $reponse->setStatusCode('404');
                $reponse->setContent("l'utilisateur n'a actuellement aucun role, il faut donc d'abord lui crée un role avant de le mettre à jour !!!");

            }
            //si le role ou l'uttilisateur n'existe pas je renvoi un message d'erreur
        }else{

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
    public function getStatutUser($idUser){


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        //je teste si il existe
        if(!empty($user)){
            //si il existe je recupere son statut


            $statut = array(

               "mode_sortie" => $user->getModeSortie()

            );

        }else{
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
    public function putStatutUser($idUser, Request $request){


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");


        //je recupere l'utilisateur par rapport a l'id
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();

        //je recupère les données
        $statut = json_decode($request->get('mode_sortie'),true);

        //je teste si l'uttilisateur existe et si je reçois des données correcte, si tous en bon
        if(!empty($user) && $statut !== null && $statut === 1 || $statut === 0){


//je met a jour le status de l'uttilisateur, je le persiste et j'envois un statue 200
        $user->setModeSortie($statut);

        $entityManager->persist($user);

        $entityManager->flush();

            $reponse->setStatusCode('200');

            //sinon j'envoi un message d'erreur
        }else{
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
    public function getNoteUser($idUser){


        //dans les entete de la requete je permet l'accses a tous les supports
        header("Access-Control-Allow-Origin: *");

        //je recupere l'utilisateur
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUser);


        $response = new Response();

        //je teste si il existe
        if(!empty($user)){

            $note = $this->getDoctrine()->getRepository(Vote::class)->findBy(["voter_id" => $idUser]);


            //si l'utilisateur n'a aucune note, alors je retourne null
            if (empty($note)){


                $note = null;

            }

            //si il n'existe pas, je retourne un message d'erreur;
        }else{

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
    public function postNoteUser($idUserVotant,$idUserVoter,Request $request){

        //je recupere les utilisateurs par rapport a leurs id
        $userVotant = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVotant);

        $userVoter = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVoter);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();

        //je verifie si les deux utilisateur existe
        if(!empty($userVotant) && !empty($userVoter))
        {
            //je tente de savoir si l'utilisateur a déjé voter
            $vote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(["voter_id" => $idUserVoter, "votant_id" => $idUserVotant ]);

            //si je ne récupère aucune imformation c'est que l'utilisateur n'a jamais voter
            if (empty($vote)){

                $note = json_decode($request->get("note"),true);

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

                }else{


                    $reponse->setStatusCode("404");
                    $reponse->setContent("erreur : les données envoyé sont incorecte ");

                }



                //sinon l'utilisateur a déja voter, je renvoi donc un message d'erreur
            }else{

                $reponse->setStatusCode("404");
                $reponse->setContent("l'utilisateur à déja voter ce membres, il ne faut donc pas faire une insertion, il faut faire une mise a jour");


            }


        //si ils existent pas je renvoi un message d'erreur
        }else{

            $reponse->setStatusCode("404");
            $reponse->setContent("Information incorecte :Un ou plusieurs des uttilisateurs recherchés, n'existent pas en base de donnée");

        }




        return $reponse;
    }


    /**
     * @Route("/api/putNoteUser/{idUserVotant}/{idUserVoter}", name="putNoteUser")
     */

//fonction qui permet de mettre a jour la note d'un membre
    public function putNoteUser($idUserVotant,$idUserVoter,Request $request){

        //je recupere les utilisateurs par rapport a leurs id
        $userVotant = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVotant);

        $userVoter = $this->getDoctrine()->getRepository(\App\Entity\User::class)->find($idUserVoter);

        $entityManager = $this->getDoctrine()->getManager();

        $reponse = new Response();

        //je verifie si les deux utilisateur existe
        if(!empty($userVotant) && !empty($userVoter))
        {
            //je tente de savoir si l'utilisateur a déjé voter
            $vote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(["voter_id" => $idUserVoter, "votant_id" => $idUserVotant ]);

            //si je récupère des imformations c'est que l'utilisateur a deja voter ce membre
            if (!empty($vote) && $vote !== null){

                $note = json_decode($request->get("note"),true);

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

                }else{


                    $reponse->setStatusCode("404");
                    $reponse->setContent("erreur : les données envoyé sont incorecte ");

                }



                //sinon l'utilisateur n'a jamais voter, je renvoi donc un message d'erreur
            }else{

                $reponse->setStatusCode("404");
                $reponse->setContent("cette utilisateur n'a jamais voter ce membre , il faut faire une insertion plutot qu'un mise a jour");


            }


            //si ils existent pas je renvoi un message d'erreur
        }else{

            $reponse->setStatusCode("404");
            $reponse->setContent("Information incorecte :Un ou plusieurs des uttilisateurs recherchés, n'existent pas en base de donnée");

        }




        return $reponse;
    }


    /**
     * @Route("/api/getAllPositions", name="getAllpositionsUser")
     */

    //cette fonction retourne toutes les position des utilisateur au format json
    public function getAllpositions(){

        //je recupere tous les uttilsateur
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();

        //si il y a des utilisateur en bdd
        if (!empty($users)){
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




        }else{
            //si j'ai rien bdd je retourne null
            $positionUsers = null;
        }

//je renvoi mon tableau aux format json
        return $this->json($positionUsers);


    }

    /**
     * @Route("/api/verifInscUser", name="verifInscUser")
     */

    //cette methode permet de vérifier si le pseudo et le mail d'un utilisateur existe en bdd, si il existe il renvoi un tableau
    //le tableau contient de cle verifPseudo et verifMail qui ont toutes deux pour valeur un boolean
    //true pour indiquer que l'imformation a été trouver, false pour le cas contraire

    public function verifInscUser(Request $request){





        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $mailUser = $request->get('mail');
        $pseudoUser = $request->get('pseudo');


        //je verifie que mes donné envoyé ne sois pas vide
        if($request->getContent() && $mailUser !== null && $pseudoUser !== null){
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
                foreach ($users as $user){



                    $mailBdd = $user->getMail();

//                si le mail existe en bdd
                    if ($mailBdd == $mailUser){

                        //je renvoi ma clé a false
                        $reponseVerifUser['validMail'] = false;

                        //je sors de ma boucle
                        break;

                        //sinon la valeur de ma clé vaut true

                    }else{

                        $reponseVerifUser['validMail'] = true;

                    }



            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($reponseVerifUser));
            $reponse->setStatusCode('200');

         //si je ne récupère aucunne donné j'envoi un message d'erreur
        }else{


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("les donné envoyé sont vide");

        }

        return $reponse;

    }
    /**
     * @Route("/api/verifConUser", name="verifConUser")
     */

    //cette methode permet de tester si un utilisateur existe en bdd par rapport a son pseudo et mdp,
    // si il existe il renvoi un tableau json contenant les info de l'utilisateur
    public function verifConUser(Request $request){



        $pseudo = $request->get('pseudo');

        $mdp = $request-> get('mdp');

        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");


        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findOneBy(["pseudo" => $pseudo, "mdp" => $mdp ]);


        if($request->getContent() && $user != null){

        $userTrouver = array(

            "nom" => $user->getNom()

        );

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent($user->getJson());
            $reponse->setStatusCode('200');



        }else{

            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("cette uttilisateur n'existe pas !");
        }

        return $reponse;

    }

    /**
     * @Route("/api/checkInfoConnexion", name="checkInfoConnexion")
     */

    public function checkInfoConnexion(Request $request){



        $reponse = new Response();

        $reponse->headers->set("Access-Control-Allow-Origin",'*');
        $reponse->headers->set('Access-Control-Allow-Credentials',true);
        $reponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,DELETE,PUT,OPTION');
        $reponse->headers->set("Access-Control-Allow-Headers", "Content-Type,Origin,Accept,Authorization,X-Request-With");

        $mdpUser = $request->get('mdp');
        $pseudoUser = $request->get('pseudo');


        //je verifie que mes donné envoyé ne sois pas vide
        if($request->getContent() && $mdpUser !== null && $pseudoUser !== null){
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
            foreach ($users as $user){


                $pseudoBdd = $user->getPseudo();
                $mdpBdd = $user->getMdp();

//                si le mail existe en bdd
                if ($mdpBdd == $mdpUser && $pseudoUser == $pseudoBdd ){

                    //je renvoi ma clé a false
                    $reponseVerifUser['validMdp'] = true;

                    //je sors de ma boucle
                    break;

                    //sinon la valeur de ma clé vaut true

                }else{

                    $reponseVerifUser['validMdp'] = false;

                }



            }

            $reponse->headers->set('Content-Type', 'application/json');
            $reponse->setContent(json_encode($reponseVerifUser));
            $reponse->setStatusCode('200');

            //si je ne récupère aucunne donné j'envoi un message d'erreur
        }else{


            $reponse->setStatusCode("404");
            $reponse->headers->set('Content-Type', 'text/plain');

            $reponse->setContent("les donné envoyé sont vide");

        }

        return $reponse;






    }
}
