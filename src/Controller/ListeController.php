<?php

namespace App\Controller;

use App\Repository\CollegeRepository;
use App\Repository\ElevesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ListeController extends AbstractController
{
    /**
     * function qui renvoi le tableau des eleves
     * @Route("/liste/{id}")
     */
    public function index(ElevesRepository $elevesRepository, $id, CollegeRepository $collegeRepository)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }
        // rcupére la liste de tous les eleves du college
        $liste_eleves=$elevesRepository->findBy(['college'=>$college , 'role'=>'ROLE_USER']);

        // recupére la liste de classe du college
        $liste_class=$elevesRepository->groupedClass($id);
        dump($liste_class);

        // récupére la liste des niveau
        $list_lvl=$elevesRepository->groupedlvl($id);


        return $this->render('liste/index.html.twig', [
            'liste_eleves' => $liste_eleves,
            'liste_class'  => $liste_class,
            'id_college'=>$college->getId(),
            'liste_lvl' =>$list_lvl
        ]);
    }

    /**
     * function qui renvoi le tableau des profs
     * @Route("/liste2/{id}")
     */
    public function index2(ElevesRepository $elevesRepository, $id, CollegeRepository $collegeRepository)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }

        $liste_eleves=$elevesRepository->findBy(['college'=>$college , 'role'=>'ROLE_PROF']);
        dump($liste_eleves);

        // recupére la liste des filiere des prof du college
        $liste_class=$elevesRepository->groupedfiliere($id);
        dump($liste_class);



        return $this->render('liste/profs.html.twig', [
            'liste_eleves' => $liste_eleves,
            'liste_class'  => $liste_class,
            'id_college'=>$college->getId()
        ]);
    }

    /**
     * @Route("/listeparclass/{id}/{class}")
     */
    public function listeParClass(ElevesRepository $elevesRepository, $id, CollegeRepository $collegeRepository, $class)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        dump($college->getUser());
        dump($this->getUser());
        dump($id);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }
        // rcupére la liste de tous les eleves du college
        $liste_eleves=$elevesRepository->findBy(['college'=>$college ,'classe'=>$class, 'role'=>'ROLE_USER']);
        dump($liste_eleves);
        // recupére la liste de classe du college
        $liste_class=$elevesRepository->groupedClass($id);
        dump($liste_class);
        // récupére la liste des niveau
        $list_lvl=$elevesRepository->groupedlvl($id);

        return $this->render('liste/index.html.twig', [
            'liste_eleves' => $liste_eleves,
            'liste_class'  => $liste_class,
            'id_college'=>$college->getId(),
            'liste_lvl' =>$list_lvl

        ]);
    }

    /**
     * @Route("/listeparlvl/{id}/{lvl}")
     */
    public function listeParlvl(ElevesRepository $elevesRepository, $id, CollegeRepository $collegeRepository, $lvl)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        dump($college->getUser());
        dump($this->getUser());
        dump($id);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }
        // rcupére la liste de tous les eleves du college
        $liste_eleves=$elevesRepository->findBy(['college'=>$college ,'niveau'=>$lvl, 'role'=>'ROLE_USER']);
        dump($liste_eleves);
        // recupére la liste de classe du college
        $liste_class=$elevesRepository->groupedClass($id);
        dump($liste_class);
        // récupére la liste des niveau
        $list_lvl=$elevesRepository->groupedlvl($id);

        return $this->render('liste/index.html.twig', [
            'liste_eleves' => $liste_eleves,
            'liste_class'  => $liste_class,
            'id_college'=>$college->getId(),
            'liste_lvl' =>$list_lvl

        ]);
    }

    /**
     * @Route("/listeparfiliere/{id}/{filiere}")
     */
    public function listeParfiliere(ElevesRepository $elevesRepository, $id, CollegeRepository $collegeRepository, $filiere)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college = $collegeRepository->findOneBy(['id' => $id]);
        dump($college->getUser());
        dump($this->getUser());
        dump($id);
        if ($college->getUser() != $this->getUser()) {
            return $this->redirectToRoute('index2');
        }
        // rcupére la liste dees profs du college filtré par $filiere sur la colonne "filiere"
        $liste_eleves = $elevesRepository->findBy(['college' => $college, 'classe' => $filiere, 'role' => 'ROLE_PROF']);
        dump($liste_eleves);
        // recupére la liste de classe du college
        $liste_class = $elevesRepository->groupedfiliere($id);
        dump($liste_class);


        return $this->render('liste/profs.html.twig', [
            'liste_eleves' => $liste_eleves,
            'liste_class' => $liste_class,
            'id_college' => $college->getId()


        ]);
    }
}
