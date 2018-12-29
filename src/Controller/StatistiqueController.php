<?php

namespace App\Controller;

use App\Entity\TypeUser;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/statistique", name="statistique")
     */
    public function index()
    {
        //je compte le nombre d'utilisateur enregistré en bdd
        $nbUser = count($this->getDoctrine()->getRepository(User::class)->findAll());

        //je récupere mes deux role role
        $roleSam = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(['role'=> 'sam']);
        $roleConso = $this->getDoctrine()->getRepository(TypeUser::class)->findOneBy(['role'=> 'consomateur']);

        //je compte le nombre d'utilisateur en mde sortie qui ont les role précédement récupéré
        $nbSam = count($this->getDoctrine()->getRepository(User::class)->findBy(['type_user_id' => $roleSam->getId(), 'mode_sortie' => 1]));
        $nbConsomateur = count($this->getDoctrine()->getRepository(User::class)->findBy(['type_user_id' => $roleConso->getId(), 'mode_sortie' => 1]));

        $nbUserBloquer = count($this->getDoctrine()->getRepository(User::class)->findBy([ 'is_del' => 1]));


        //Calcul du nombre d'utilisateur selon leur musique préféré

        $nbUserMusGeneraliste = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Généraliste']));

        $nbUserMusRnb = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'RnB']));

        $nbUserMusHipHop = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Hip Hop']));

        $nbUserMusLatino = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Latino']));

        $nbUserMusHouse = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'House']));

        $nbUserMusRock = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Rock']));

        $nbUserMusDisco = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Disco']));

        $nbUserMusTechno = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Techno']));

        $nbUserMusAfro = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Afro']));

        $nbUserMusElectro = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Electro']));

        $nbUserMusSalsa = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Salsa']));

        $nbUserMusRagga = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Ragga']));

        $nbUserMusFunk = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Funk']));

        $nbUserMusReggae = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Reggae']));

        $nbUserMusStyleVarie = count($this->getDoctrine()->getRepository(User::class)->findBy(['musique_favoris'=> 'Styles varies']));


        //Cacul du nombre d'utilisateur suivant leur club préféré


        $nbUserClubMilk = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> 'Le Milk']));

        $nbUserClubHeat = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> 'Le Heat']));

        $nbUserClubPulp = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> 'Le Pulp']));

        $nbUserClubOsmose = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "L'Osmose"]));

        $nbUserClubCononuts = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Coconuts"]));

        $nbUserClubFizz = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Fizz"]));

        $nbUserClubPointZero = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Point Zero"]));

        $nbUserClubOpera = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "L'Opéra"]));

        $nbUserClubDune = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "La Dune"]));

        $nbUserClubPanama = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Panama"]));

        $nbUserClubDream = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Dream"]));

        $nbUserClubLOT = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "L'OT"]));

        $nbUserClubCoulisse = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Les Coulisses"]));

        $nbUserClubDomaineVerchant = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Domaine de verchant"]));

        $nbUserClubClot = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le clos"]));

        $nbUserClubProse = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Prose"]));

        $nbUserClubMasMinistre = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Mas du Ministre"]));

        $nbUserClubMasCourant = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Mas du courant"]));

        $nbUserClubMasCheval = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Mas du cheval"]));

        $nbUserClubZebreBleu = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Zèbre bleu"]));

        $nbUserClubVoileBleu = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "La Voile bleue"]));

        $nbUserClubEffetMer = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "L'Effet Mer"]));

        $nbUserClubPailloteBambou = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "La Paillotte bambou"]));

        $nbUserClubCarreMer = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Carré Mer"]));

        $nbUserClubBeach = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Beach"]));

        $nbUserClubLatzarro = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Latzarro"]));

        $nbUserClubPiedNus = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Les Pieds nus"]));

        $nbUserClubLezard = count($this->getDoctrine()->getRepository(User::class)->findBy(['club_favoris'=> "Le Lezard"]));


        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
            'nbUser' => $nbUser,
            'nbSam' => $nbSam,
            'nbConsomateur' => $nbConsomateur,
            'nbUserBloquer' => $nbUserBloquer,
            'nbUserMusGeneraliste'=> $nbUserMusGeneraliste,
            'nbUserMusRnb' => $nbUserMusRnb,
            'nbUserMusHipHop' => $nbUserMusHipHop,
            'nbUserMusLatino' => $nbUserMusLatino,
            'nbUserMusHouse' => $nbUserMusHouse,
            'nbUserMusRock' => $nbUserMusRock,
            'nbUserMusDisco' => $nbUserMusDisco,
            'nbUserMusTechno' => $nbUserMusTechno,
            'nbUserMusAfro' => $nbUserMusAfro,
            'nbUserMusElectro' => $nbUserMusElectro,
            'nbUserMusSalsa' => $nbUserMusSalsa,
            'nbUserMusRagga' => $nbUserMusRagga,
            'nbUserMusFunk' => $nbUserMusFunk,
            'nbUserMusReggae' => $nbUserMusReggae,
            'nbUserMusStyleVarie' => $nbUserMusStyleVarie,
            'nbUserClubMilk' => $nbUserClubMilk,
            'nbUserClubHeat' => $nbUserClubHeat,
            'nbUserClubPulp' => $nbUserClubPulp,
            'nbUserClubOsmose' => $nbUserClubOsmose,
            'nbUserClubCononuts' => $nbUserClubCononuts,
            'nbUserClubFizz' => $nbUserClubFizz,
            'nbUserClubPointZero' => $nbUserClubPointZero,
            'nbUserClubOpera' => $nbUserClubOpera,
            'nbUserClubDune' => $nbUserClubDune,
            'nbUserClubPanama' => $nbUserClubPanama,
            'nbUserClubDream' => $nbUserClubDream,
            'nbUserClubLOT' => $nbUserClubLOT,
            'nbUserClubCoulisse' => $nbUserClubCoulisse,
            'nbUserClubDomaineVerchant' => $nbUserClubDomaineVerchant,
            'nbUserClubClot' => $nbUserClubClot,
            'nbUserClubProse' => $nbUserClubProse,
            'nbUserClubMasMinistre' => $nbUserClubMasMinistre,
            'nbUserClubMasCourant' => $nbUserClubMasCourant,
            'nbUserClubMasCheval' => $nbUserClubMasCheval,
            'nbUserClubZebreBleu' => $nbUserClubZebreBleu,
            'nbUserClubVoileBleu' => $nbUserClubVoileBleu,
            'nbUserClubEffetMer' => $nbUserClubEffetMer,
            'nbUserClubPailloteBambou' => $nbUserClubPailloteBambou,
            'nbUserClubCarreMer' => $nbUserClubCarreMer,
            'nbUserClubBeach' => $nbUserClubBeach,
            'nbUserClubLatzarro' => $nbUserClubLatzarro,
            'nbUserClubPiedNus' => $nbUserClubPiedNus,
            'nbUserClubLezard' => $nbUserClubLezard







        ]);
    }
}
