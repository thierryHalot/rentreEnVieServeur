<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\TypeUser;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     */
    public function index()
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
        ]);
    }


    /**
     * @Route("/postNew", name="postNews")
     */

    //cette fonction permet l'insertion d'une nouvelle News en base de donné
    public function postNew(Request $request){

        //je recupère les imformations du formulaire

        $titre = $request->get('titre');
       $img = $request->get('img');
        $description = $request->get('description');

        $entityManager = $this->getDoctrine()->getManager();
        //je crée une nouvelle new



        //dans un premier temp je test que je reçois bien des donné
        if ($request->getContent()){

            $new = new News();
            //je verifie que le titre et la description envoyé ne sois pas vide
            if ($titre != "null" && !empty($titre) && $description != "null" && !empty($description)){

            $roleAdmin = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(["role" => "admin"]);
            $admin = $this->getDoctrine()->getRepository(User::class)->findOneBy(["type_user_id" => $roleAdmin->getId()]);

            $new->setTitre($titre);
            $new->setDescription($description);
            $new->setDate(new \DateTime());
            $new->setUserId($admin);
            $message = "Création de la New effectuer avec succès";

                if ($img != "null" && !empty($img)){

                    $new->setImg($img);
                    $message.=", l'image à bien été pris en compte";
                }else{

                    $message.=", en revanche vous n'avez inséré aucune image";
                }

                //je persiste la new
                $entityManager->persist($new);
                $entityManager->flush();

                //je retourne un message comme quoi l'insertion c'est bien éffectué

                $this->addFlash(
                    'messageCreateNew',
                    $message
                );

                //je redirige l'utilisateur sur la page courante
                return $this->redirectToRoute("news");

                //si je ne reçois aucune donnée j'envoi un message d'erreur.
            }else{

                $erreurNew = 'Erreur : les données envoyées sont vides';

                return $this->render('news/index.html.twig', [
                    'controller_name' => 'NewsController',
                    'erreurNew' => $erreurNew,
                ]);



            }



        }

    }
}
