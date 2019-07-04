<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/auth", name="security_registration")
     */
/*
 public function registration(Request $request, ObjectManager $manger,UserPasswordEncoderInterface $encoder){
  $user = new User();
     $form = $this ->createForm(RegistrationType::class,$user);
     $form ->handleRequest($request);
     if($form->isSubmitted()&& $form->isValid()){
         $hash = $encoder->encodePassword($user,$user->getPassword());
         $user->setPassword($hash);
         $manger->persist($user);
         $manger->flush();


     }
     return $this->render('auth/registration.html.twig',[
         'form' => $form->createView()
     ]);

    }
*/
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();

    return $this->render('auth/login.html.twig', [
        'error'         => $error,
    ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){

    }
}
