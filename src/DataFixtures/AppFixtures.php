<?php
/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 02/11/18
 * Time: 20:04
 */

namespace App\DataFixtures;


use App\Entity\BlackList;
use App\Entity\HeaderMsg;
use App\Entity\Msg;
use App\Entity\News;
use App\Entity\Recherche;
use App\Entity\TypeUser;
use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        //je commence par générer le jeu de donné de mes rôles

        $roleAdmin = $this->generateRole("admin");
        $roleSam = $this->generateRole("sam");
        $roleConso = $this->generateRole("consomateur");

        //je persiste mes rôles

        $manager->persist($roleAdmin);
        $manager->persist($roleSam);
        $manager->persist($roleConso);

        //je genere ensuite un jeu de donné mes uttilisateur auquelle j'affecte mes roles précédement générer

        $user1 = $this->generateUser("Halot",
            "thierry",
            27,
            "homme",
            658070668,
            "451 g chemin de sainte catherine",
            "thierry.halot@laposte.net",
            "thierry",
            "thierry",
            1,
            "rockstore",
            "rock",
            "imgThierry",
            1,
            25,
            2.6,
            2.8,
            $roleConso,
            0);

        $user2 = $this->generateUser("derrieux",
            "loic",
            35,
            "homme",
            652413625,
            "rue de loic",
            "loic@mail.net",
            "loic",
            "loic",
            1,
            "munster",
            "salsa",
            "imgloic",
            1,
            30,
            7.6,
            2.8,
            $roleConso,
            0);

        $user3 = $this->generateUser("delaru",
            "benoit",
            56,
            "homme",
            668745821,
            "rue de benoit",
            "benois@mail.fr",
            "benoit",
            "benoit",
            0,
            "milk",
            "zook",
            "imgBenoit",
            0,
            56,
            9.6,
            2.5,
            $roleSam,
            0);

        $user4 = $this->generateUser("monaco",
            "stephanie",
            24,
            "femme",
            645285414,
            "rue de stephanie",
            "stephanie@mail.fr",
            "stephanie",
            "stephanie",
            0,
            "villa rouge",
            "rock",
            "imgStephanie",
            1,
            60,
            7.8,
            6.8,
            $roleSam,
            0);

        $user5 = $this->generateUser("dadou",
            "veronica",
            29,
            "femme",
            625402658,
            "rue de veronica",
            "veronica@mail.fr",
            "veronica",
            "veronica",
            0,
            "rockstore",
            "rock",
            "imgVeronica",
            1,
            75,
            4.6,
            9.8,
            $roleConso,
            0);

        $user6 = $this->generateUser("durant",
            "cedric",
            56,
            "homme",
            624365807,
            "rue de cedric durant",
            "durant@mail.fr",
            "durant",
            "durant",
            1,
            "milk",
            "salsa",
            "imgDurant",
            0,
            51,
            65.52,
            26.2,
            $roleSam,
            1);

        $userAdmin = $this->generateUser("dieu",
            "loic",
            45,
            "homme",
            625684521,
            "rue du dieu loic",
            "LoicDieu@mail.fr",
            "dieu",
            "dieu",
            0,
            "rockstore",
            "rock",
            "imgAdmin",
            0,
            12,
            45.52,
            24.2,
            $roleAdmin,
            0);

        //je persiste mes utilisateurs
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->persist($user6);
        $manager->persist($userAdmin);


        //je genere un jeu de donné pour simuler l'envoi de news de l'admin

        $post1 = $this->generateNew("news 1 ","img news 1","la premiere news",new \DateTime('2010-01-20 1:00:00'),$userAdmin);
        $post2 = $this->generateNew("news 2 ","img news 2","la deuxieme news",new \DateTime('2010-02-20 2:00:00'),$userAdmin);
        $post3 = $this->generateNew("news 3 ","img news 3","la troisieme news",new \DateTime('2010-03-20 3:00:00'),$userAdmin);
        $post4 = $this->generateNew("news 4 ","img news 4","la quatriem news",new \DateTime('2010-04-20 4:00:00'),$userAdmin);

        //je persiste mes news

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->persist($post3);
        $manager->persist($post4);


        //je genere un jeu de donné pour simuler des recherche d'utilisateur
        $rechercheUser1 = $this->generateRecherche("homme",20,1,"rock","rockstore","consommateur",$user1);
        $rechercheUser2 = $this->generateRecherche("homme",20,0,"rock","rockstore","sam",$user2);
        $rechercheUser3 = $this->generateRecherche("femme",20,1,"salsa","rockstore","consommateur",$user3);
        $rechercheUser4 = $this->generateRecherche("femme",20,0,"rock","monster","sam",$user4);
        $rechercheUser5 = $this->generateRecherche("homme",20,1,"salsa","milk","consommateur",$user5);
        $rechercheUser6 = $this->generateRecherche("femme",20,0,"rock","rockstore","consommateur",$user6);

        //je persiste les recherches correspondant à chaques utilisateurs

        $manager->persist($rechercheUser1);
        $manager->persist($rechercheUser2);
        $manager->persist($rechercheUser3);
        $manager->persist($rechercheUser4);
        $manager->persist($rechercheUser5);
        $manager->persist($rechercheUser6);

        //je simule un jeu donné pour la blacklist

        $blacklist1 = $this->generateBlackListe($user1,$user2,new \DateTime('2018-06-20 1:00:00'));
        $blacklist2 = $this->generateBlackListe($user2,$user3,new \DateTime('2018-01-20 1:00:00'));

        //je persiste les blacklists des utilisateurs

        $manager->persist($blacklist1);
        $manager->persist($blacklist2);

        //je simule le vote d'un utilisateur

        $vote1 = $this->generateVote(new \DateTime("2018-08-25 3:00:00"),1,5,$user1,$user2);
        $vote2 = $this->generateVote(new \DateTime('2018-08-26 4:00:00'),1, 4,$user1,$user4);

        //je persiste ses votes
        $manager->persist($vote1);
        $manager->persist($vote2);

        //je simule le tchat entre les utilisateurs que je persiste

$headerMsg1 = $this->generateHeaderMsg(new \DateTime("2018-08-25 3:00:00"),0,$user1,$user2);

$headerMsg2 = $this->generateHeaderMsg(new \DateTime("2018-09-25 5:00:00"),0,$user2,$user1);


        $manager->persist($headerMsg1);
        $manager->persist($headerMsg2);

        $premierMsgUser1 = $this->generateMsg($user1->getNom(),"salut ".$user2->getNom(),new \DateTime("2018-08-25 3:00:00"),$headerMsg1);
        $manager->persist($premierMsgUser1);

        $premiereRepMsgUser2 = $this->generateMsg($user2->getNom(),"salut ".$user1->getNom(), new \DateTime("2018-08-25 5:00:00"),$headerMsg2);
        $manager->persist($premiereRepMsgUser2);

        $deusiemeMsgUser1 = $this->generateMsg($user1->getNom(),"j'ai bien recu ton premier message",new \DateTime("2018-08-25 6:00:00"),$headerMsg1);
        $manager->persist($deusiemeMsgUser1);

        $deuxiemeRepMsgUser2 = $this->generateMsg($user2->getNom(),"cool ",new \DateTime("2018-08-25 7:00:00"),$headerMsg2);
        $manager->persist($deuxiemeRepMsgUser2);


        $manager->flush();

    }

    //fonction qui permet de generer un nouvelle utilisateur
    //retourne un objet de type User
    public function generateUser($nom,$prenom,$age,$sexe,$tel,$adresse,$mail,$pseudo,$mdp,$fumeur,$clubFavoris,$musiqueFavoris,$img,$modeSortie,$perimetre,$latitude,$longitude,$role,$isDel){

        $user = new User();

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

        return $user;


    }

    //fonction qui permet de generer un nouveau role
    //retourne un objet de type Role
    public function generateRole($nom){

        $role = new TypeUser();

        $role->setRole($nom);


        return $role;

    }

    //fonction qui permet de générer une nouvelle news
    //retourn un objet de type News
    public function generateNew($titre,$img,$description,$date,$user){

        $news = new News();

        $news->setTitre($titre);
        $news->setImg($img);
        $news->setDescription($description);
        $news->setDate($date);
        $news->setUserId($user);

        return $news;
    }

    //fonction qui permet de générer une nouvelle recherche
    //retourne un objet de type Recherche
    public function generateRecherche($sexe,$age,$fumeur,$musique,$club,$statut,$user){

        $recherche = new Recherche();

        $recherche->setSexe($sexe);
        $recherche->setAge($age);
        $recherche->setFumeur($fumeur);
        $recherche->setMusiqueFavoris($musique);
        $recherche->setClubFavoris($club);
        $recherche->setStatut($statut);
        $recherche->setRecherchantId($user);

        return $recherche;
    }

    //fonction qui permet de générer une nouvelle blackliste
    //retourne un objet de type Blacklist
    public function generateBlackListe($userBloquand,$userBloquer,$date){

    $blacklist = new BlackList();

    $blacklist->setBloquandId($userBloquand);
    $blacklist->setBloquerId($userBloquer);
    $blacklist->setDate($date);

    return $blacklist;
    }

    //fonction qui permet de generer un nouveau vote
    //retourne un objet de type Vote
    public function generateVote($date,$nbrVote,$note,$userVotant,$userVoter){


        $vote = new Vote();

        $vote->setDate($date);
        $vote->setNbrVote($nbrVote);
        $vote->setNote($note);
        $vote->setVotantId($userVotant);
        $vote->setVoterId($userVoter);

        return $vote;
    }

    //fonction qui permet de générer une nouvelle entete de msg
    //retourne un objet de type HeaderMsg
    public function generateHeaderMsg($date,$isDel,$emetteur,$recepteur){

    $headerMsg = new HeaderMsg();

    $headerMsg->setDate($date);
    $headerMsg->setIsDel($isDel);
    $headerMsg->setEmetteurId($emetteur);
    $headerMsg->setRecepteurId($recepteur);

    return $headerMsg;

    }

    //fonction qui permet de générer un nouveau message
    //retourne un objet de type Msg
    public function generateMsg($objet,$contenu,$date,$headerMsg){
    $msg = new Msg();

    $msg->setObjet($objet);
    $msg->setContenu($contenu);
    $msg->setDate($date);
    $msg->setMsgId($headerMsg);

    return $msg;

    }
}