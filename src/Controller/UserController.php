<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\RegisterType;
use App\Form\UpdateUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////// ADMIN ROUTING ////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////
    
    
    ///////////////////////////////////// CITIES /////////////////////////////////////
    
    /**
     * @Route("/admin/cities", name="cities")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function cities(EntityManagerInterface $em)
    {
        if (isset($_POST['_nom']) && isset($_POST['_cp']))
        {
            $nom = $_POST['_nom'];
            $codePostal = $_POST['_cp'];
    
            $ville = new Ville();
            $ville->setNom($nom);
            $ville->setCodePostal($codePostal);
            
            $em->persist($ville);
            $em->flush();
    
            $this->addFlash('success', $ville->getNom().' a bien été ajouté!');
        }
        
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $villes = $villeRepo->findAll();
        
        
        
        return $this->render('admin/cities.html.twig', ['villes'=>$villes]);
    }
    
    
    
    /**
     * @Route ("/admin/cities/update/{id}", name="city_update", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function updateCity($id, EntityManagerInterface $em)
    {
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $villeRepo->find($id);
        
        $nom = $_POST['_nom'];
        $codePostal = $_POST['_cp'];
    
        $ville->setNom($nom);
        $ville->setCodePostal($codePostal);
    
        $em->persist($ville);
        $em->flush();
    
        $this->addFlash('success', $ville->getNom().' a bien été modifié!');
        return $this->redirectToRoute('cities');
    }
    
    
    
    /**
     * @Route ("/admin/cities/delete/{id}", name="city_delete", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteCity($id, EntityManagerInterface $em)
    {
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $villeRepo->find($id);
        
        $em->remove($ville);
        $em->flush();
    
        $this->addFlash('success', $ville->getNom().' a bien été supprimé!');
        return $this->redirectToRoute('cities');
    }
    
    
    
    
    ///////////////////////////////////// CAMPUS /////////////////////////////////////
    
    /**
     * @Route("/admin/campus", name="campus")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function campus(EntityManagerInterface $em)
    {
        if (isset($_POST['_nom']))
        {
            $nom = $_POST['_nom'];
            
            $campus = new Campus();
            $campus->setNom($nom);
            
            $em->persist($campus);
            $em->flush();
            
            $this->addFlash('success', $campus->getNom().' a bien été ajouté!');
        }
        
        $campusRepo = $this->getDoctrine()->getRepository(Campus::class);
        $campusS = $campusRepo->findAll();
        
        return $this->render('admin/campus.html.twig', ['campusS'=>$campusS]);
    }
    
    
    
    /**
     * @Route ("/admin/campus/update/{id}", name="campus_update", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function updateCampus($id, EntityManagerInterface $em)
    {
        $campusRepo = $this->getDoctrine()->getRepository(Campus::class);
        $campus = $campusRepo->find($id);
        
        $nom = $_POST['nom'.strval($id)];
        $campus->setNom($nom);
        
        $em->persist($campus);
        $em->flush();
        
        $this->addFlash('success',$campus->getNom().' a bien été modifié');
        return $this->redirectToRoute('campus');
        
    }
    
    
    
    /**
     * @Route ("/admin/campus/delete/{id}", name="campus_delete", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteCampus($id, EntityManagerInterface $em)
    {
        $campusRepo = $this->getDoctrine()->getRepository(Campus::class);
        $campus = $campusRepo->find($id);
        
        $em->remove($campus);
        $em->flush();
        
        $this->addFlash('success',$campus->getNom().' a bien été supprimé');
        return $this->redirectToRoute('campus');
    }
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////// USER ROUTING /////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * @Route("/register", name="register")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setDateCreated(new \DateTime());
        $user->setAdmin(false);
        $registerForm = $this->createForm(RegisterType::class,$user);
        
        $registerForm->handleRequest($request);
        
        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hasher le mot de passe
            $hashed = $encoder->encodePassword($user, $user->getPassword());
    
            $user->setPassword($hashed);
    
            $em->persist($user);
            $em->flush();
    
            $this->addFlash('success','Le profil a bien été créé');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('user/register.html.twig', ["registerForm" => $registerForm->createView()]);
    }
    
    
    
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('user/login.html.twig');
    }
    
    
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        $this->addFlash('success','Vous êtes déconnecté');
        return $this->redirectToRoute('home');
    }
    
    
    
    /**
     * @Route("/profile/{id}", name="user_profile", requirements={"id":"\d+"}, methods={"GET"})
     * @param $id
     * @return Response
     */
    public function profile($id)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        
        $user = $userRepo->find($id);
        
        return $this->render('user/user-profile.html.twig', ["user"=>$user]);
    }
    
    
    
    /**
     * @Route("/profile/update/{id}", name="user_update", requirements={"id":"\d+"}, methods={"GET","POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateProfile(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface
    $encoder, $id)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
    
        $user = $userRepo->find($id);
    
        $updateForm = $this->createForm(UpdateUserType::class,$user);
    
        $updateForm->handleRequest($request);
    
        if ($updateForm->isSubmitted() && $updateForm->isValid())
        {
            //hasher le mot de passe
            $hashed = $encoder->encodePassword($user, $user->getPassword());
        
            $user->setPassword($hashed);
        
            $em->persist($user);
            $em->flush();
        
            $this->addFlash('success','Votre profil a bien été modifié');
            return $this->redirectToRoute('user_profile', ['id'=>$id]);
        }
        
        
        return $this->render('user/user-update.html.twig', ["updateForm"=>$updateForm->createView()]);
    }
    
    
    
    /**
     * @Route ("/profile/deactivation/{id}", name="user_deactivation", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deactivationUser($id, EntityManagerInterface $em)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);
        $user->setActive(false);
        
        $em->persist($user);
        $em->flush();
        
        $this->addFlash('success', 'Le compte a bien été désactivé');
        return $this->redirectToRoute('home');
    }
    
    
    
    /**
     * @Route ("/profile/activation/{id}", name="user_activation", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function activationUser($id, EntityManagerInterface $em)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);
        $user->setActive(true);
        
        $em->persist($user);
        $em->flush();
        
        $this->addFlash('success', 'Le compte a bien été activé');
        return $this->redirectToRoute('home');
    }
    
    
    
    
    /**
     * @Route ("/profile/delete/{id}", name="user_delete", requirements={"id": "\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteUser($id, EntityManagerInterface $em)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);
        
        $em->remove($user);
        $em->flush();
        
        $this->addFlash('success','Le compte a bien été supprimé');
        return $this->redirectToRoute('home');
    }
    
}
