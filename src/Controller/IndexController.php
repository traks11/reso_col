<?php




namespace App\Controller;



use App\Entity\Eleves;
use App\Entity\User;


use App\Repository\CollegeRepository;
use App\Repository\ElevesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index2{id}", name="index2",defaults={"id": null })
     */
    public function index2(UserRepository $userRepository, CollegeRepository $collegeRepository, $id,ElevesRepository $elevesRepository)


    {
        $col='';
        $list_col='';
        $user = $this->getUser();
        if (!is_null($user))
        {
            $utilisateur =$userRepository->findOneBy(['email'=>$user->getUsername()]);
            $list_col=$collegeRepository->findBy(['user'=>$this->getUser()]);
        }

            dump($this->getUser());
            dump($list_col);
       // }
        if(!is_null($id)){
            $col=$collegeRepository->findOneBy(['id'=>$id]);
        }else{
            $col=$collegeRepository->findOneBy(['id'=>$this->getUser()]);
        }
          // ___ voir si la liste des éléves a déja était crée
        $eleves= $elevesRepository->findBy(['college'=>$col,'role'=>'ROLE_USER']);
        $profs= $elevesRepository->findBy(['college'=>$col,'role'=>'ROLE_PROF']);



        return $this->render('index/page1.html.twig', [
            'user'=>$utilisateur,
            'cols'=>$list_col,
            'college'=>$col,
            'eleves'=>$eleves,
            'profs'=>$profs
        ]);
    }








    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}


