<?php
/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 18/12/18
 * Time: 11:07
 */

namespace App\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
       $data = array(

           'message' => 'Vous devez vous authentifier'
       );
    }


    //permet de savoir si le token est present dans le header retourne true si c'est le cas ou false si ce n'est pas le cas
    public function supports(Request $request)
    {
       return $request->headers->has('X-AUTH-TOKEN');
    }

    //permet de recupéré le token dans les en-tetes sous forme de tableau associatif
    public function getCredentials(Request $request)
    {
        return array(

            'token' => $request->headers->get('X-AUTH-TOKEN')
        );
    }

    //cette fonction retourn un tableau assiosiatif avec pour valeur le token qu
    public function getCredentialsforParam(Request $request)
    {
        return array(

            'token' => $request->get('X-AUTH-TOKEN')
        );
    }

    //retourne l'utilisateur par rapport a son token
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if(null === $apiToken){

            return;
        }

        return $this->em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
    }

    //cette methode permet de verifier si le token de l'utilisateur est valide retour true si tous est bon
    public function checkCredentials($credentials, UserInterface $user)
    {
        $statut = false;
        if($credentials['token'] == $user->getApiToken()){


            $statut = true;
        }


        return $statut;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        $data = array(

            'message'=> strtr($exception->getMessageKey(),$exception->getMessageData())
        );

        return new JsonResponse($data,Response::HTTP_FORBIDDEN);
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }


    public function supportsRememberMe()
    {
        return false;
    }

    //cette fonction permet de généré un token et de de l'affecter a mon utilisateur
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {

        //je génère un token ramdom en base_64
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        //je l'affecte a mon utilisateur
        $user->setApiToken($token);

        //je persiste mon utilisateur et son token
        $this->em->persist($user);

        $this->em->flush();


    }


}