<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\UpdateSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/list", name="sortie_list")
     */
    public function list()
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $currentUser = $this->getUser()->getUsername();
        $user = $userRepo->findOneByTheUsername($currentUser);
        
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAll();
        
        $campusRepo = $this->getDoctrine()->getRepository(Campus::class);
        $campusS = $campusRepo->findAll();
        
        foreach ($sorties as $sortie)
        {
            if ($sortie->getParticipants()->contains($user))
            {
                $isRegistered = true;
            }
            else
            {
                $isRegistered = false;
            }
        }
        
        return $this->render('sortie/list.html.twig', ['sorties'=>$sorties, 'campusS'=>$campusS, 'isRegistered'=>$isRegistered]);
    }
    
    
    /**
     * @Route("/sortie/{id}", name="sortie_detail", requirements={"id":"\d+"}, methods={"GET"})
     * @param $id
     * @return Response
     */
    public function detail($id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        
        $participants = $sortie->getParticipants();
        
        return $this->render('sortie/detail.html.twig', ["sortie"=>$sortie, "participants"=>$participants]);
    }
    
    
    /**
     * @Route("/sortie/add", name="sortie_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $currentUser = $this->getUser()->getUsername();
        $user = $userRepo->findOneByTheUsername($currentUser);
        
        
        $sortie = new Sortie();
        $sortie->setOrganisateur($user);
        $sortie->setCampus($user->getCampus());
    
        
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        
        
        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre sortie a bien été enregistrée');
        }
        
        //LieuForm
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class,$lieu);
        $lieuForm->handleRequest($request);
    
        if ($lieuForm->isSubmitted() && $lieuForm->isValid())
        {
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Votre lieu a bien été enregistrée');
        }
        
        return $this->render('sortie/add.html.twig',["sortieForm"=>$sortieForm->createView(), "sortie"=>$sortie, "lieuForm"=>$lieuForm->createView()]);
    }
    
    
    /**
     * @Route("/sortie/update/{id}", name="sortie_update", requirements={"id":"\d+"}, methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(EntityManagerInterface $em, Request $request, $id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
    
        $sortie = $sortieRepo->find($id);
        
        $updateSortieForm = $this->createForm(UpdateSortieType::class, $sortie);
        $updateSortieForm->handleRequest($request);
    
    
        if ($updateSortieForm->isSubmitted() && $updateSortieForm->isValid())
        {
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre sortie a bien été modifiée');
            return $this->redirectToRoute('sortie_detail', ['id'=>$id]);
        }
    
        //LieuForm
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class,$lieu);
        $lieuForm->handleRequest($request);
    
        if ($lieuForm->isSubmitted() && $lieuForm->isValid())
        {
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Votre lieu a bien été enregistrée');
        }
    
    
        return $this->render('sortie/update.html.twig',["updateSortieForm"=>$updateSortieForm->createView(),
            "sortie"=>$sortie,
            "lieuForm"=>$lieuForm->createView()]);
    }
    
    
    /**
     * @Route("/sortie/cancel/{id}", name="sortie_cancel", requirements={"id":"\d+"}, methods={"GET"})
     * @param EntityManagerInterface $em
     * @param $id
     * @return Response
     */
    public function cancel(EntityManagerInterface $em, $id)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatCancel = $etatRepo->findOneByLibelle('Annulée');
        
        $sortie = $sortieRepo->find($id);
        $sortie->setEtat($etatCancel);
        
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', $sortie->getNom().' a été annulée');
        
        return $this->render('sortie/cancel.html.twig');
    }
    
    
    /**
     * @Route ("/sortie/registration/{id}", name="sortie_registration", requirements={"id":"\d+"}, methods={"GET",
     *     "POST"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function addParticipant($id, EntityManagerInterface $em)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $currentUser = $this->getUser()->getUsername();
        $user = $userRepo->findOneByTheUsername($currentUser);
        
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        
        $sortie->addParticipant($user);
        
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Super vous êtes inscrit!');
        
        
        
        return $this->redirectToRoute('sortie_detail',['id'=> $id]);
    }
    
    
    /**
     * @Route ("/sortie/withdraw/{id}", name="sortie_withdraw", requirements={"id":"\d+"}, methods={"GET", "POST"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function removeParticipant($id, EntityManagerInterface $em)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $currentUser = $this->getUser()->getUsername();
        $user = $userRepo->findOneByTheUsername($currentUser);
        
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        
        $sortie->removeParticipant($user);
        
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Dommage, vous ne participez plus à '.$sortie->getNom());
        
        return $this->redirectToRoute('sortie_detail',['id'=> $id]);
    }
    
    
    
}
