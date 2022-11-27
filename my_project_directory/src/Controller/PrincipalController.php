<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Club;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PrincipalController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('principal/index.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    #[Route('/mon-compte', name: 'mon_compte')]
    public function monCompte(): Response
    {
        return $this->render('principal/mon-compte.html.twig');
    }

    #[Route('/inscription/{id}', name: 'inscription')]
    public function inscription(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $em=$doctrine->getManager();
        $user=$em->getRepository(User::Class)->find($id);
        $form = $this->createForm(inscriptionType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('accueil');
        }
        return $this->render('principal/inscription.html.twig',array(
            'form'=>$form->createView(),
            'id'=>$user->getId(),
        ));
    }
}
