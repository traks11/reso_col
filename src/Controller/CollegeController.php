<?php

namespace App\Controller;

use App\Entity\College;
use App\Entity\Eleves;
use App\Form\CollegeType;
use App\Repository\CollegeRepository;
use App\Repository\ElevesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use SplFileInfo;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CollegeController extends AbstractController
{
    /**
     * @Route("/college")
     */
    public function index(Request $request,

                         EntityManagerInterface $manager
                           )
    {

          $col= new College();
          $form=$this->createForm(CollegeType::class,$col);
          $form->handleRequest($request);
//          ________________formulaire___________________________________________
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                /** @var    UploadedFile|null $image */
                //$image = $col->getImage();
                $image = $form->get('image')->getData();
                // test si un avatar est saisi dans le formulaire
                if (!is_null($image)) {


                    // nom pour la BDD
                    $imagefilename = uniqid() . '.' . $image->guessExtension();

                    // deplacer le fichier vers le repertoire de stockage
                    $image->move(
                    // repertoire de destination fait dans config/services.yaml
                        $this->getParameter('upload_dir'),
                        // nom du fichier
                        $imagefilename
                    );

                    // on sette l'image' de l'article avec le nom du fichier
                    // pour enregistrement
                    $col->setImage($imagefilename);

                }else{

                    // ici on doit setter l'image par default .... a faire
                    $col->setImage('img_def_col.jpg');
                }

                $col->setUser($this->getUser());
                // insertion dans la BDD
                $manager->persist($col);
                $manager->flush();
                $this->addFlash('success', 'Votre collége est crée');
                return $this->redirectToRoute('index2');

                } else {
                      $this->addFlash('error', 'le formulaire contient des erreurs');
            }

        }
        //_________________________fin formulaire__________________________________________________



        // retour de l'objet userController vers inscription
        return $this->render(
            'college/index.html.twig',
           ['form' => $form->createView()



           ]
        );
    }

    /**
     * @Route("/editcol/{id}",defaults={"id": null })
     */

    public function editcol(Request $request,
                                 $id,
                                 EntityManagerInterface $manager,
                                 CollegeRepository $collegeRepository
                                 )
    {



        //création de la variable pour l'encien nom de l'image
        $oldimage=null;

        //récupére les donnée du colége pour voir si l'utilisateur y est admin .....
        $coll= $collegeRepository->findOneBy(['id'=>$id]);
        if($coll->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }
        // recupére le nom de l'image actuelle

        $imgg=$coll->getImage();
        // créer l'objet college
        $col= new College();
        //remplir l'objet college avec les donnée recuperé dans $coll
        $col=$coll;

        // fréer le formulaire selon le type CollegeType
        $form=$this->createForm(CollegeType::class,$col);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                /** @var    UploadedFile|null $image */
                //$image = $col->getImage();
                $image = $form->get('image')->getData();
                dump($image);
                // test si un avatar est saisi dans le formulaire
                if (!is_null($image)) {
                    dump($image);

                    // nom pour la BDD
                    $imagefilename = uniqid() . '.' . $image->guessExtension();

                    // deplacer le fichier vers le repertoire de stockage
                    $image->move(
                    // repertoire de destination fait dans config/services.yaml
                        $this->getParameter('upload_dir'),
                        // nom du fichier
                        $imagefilename
                    );
                    // on sette l'image' de l'article avec le nom du fichier
                    // pour enregistrement
                    $col->setImage($imagefilename);
                }
               else {
////                    // en modification, sans upload, on sette l'image
////                    // avec le nom de l'ancienne image
////                    if (!is_null($imgg)) {
                    $col->setImage($imgg);
////                        $imagefilename=$imgg;
////
                 }

                // insertion dans la BDD
                $manager->persist($col);
                $manager->flush();
                $this->addFlash('success', 'enregitrement de vos modification');

                // si ya pas de nouvelle image alors la varible "$imagefilename reprend l'encien nom de l'image
                if(isset($imagefilename)){
                    $imgg=$imagefilename;
               }else{

                    $imgg=$col->getImage();
                }

                return $this->redirectToRoute('app_college_editcol',['id'=>$id]);

            } else {
                $this->addFlash('error', 'le formulaire contient des erreurs');
            }
        }
        return $this->render(
            'college/editcol.html.twig',
            ['form' => $form->createView(),
                'image'=>$imgg
            ]
        );
    }

    /**
     * @Route("/upload_eleves/{id}")
     */
    public function upload_eleves(CollegeRepository $collegeRepository, $id , EntityManagerInterface $manager, ElevesRepository $elevesRepository)
    {

        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }



          $file=$_FILES['fichier'];
          dump($file['name']);
        $info = new SplFileInfo($file['name']);
        if ($info->getExtension() != 'csv'){
            $this->addFlash('error', 'le fichier n\'est pas un fichier csv');
        }else {



             $filefilename = uniqid() . '.csv' ;
             move_uploaded_file($_FILES["fichier"]["tmp_name"],
                 $this->getParameter('upload_dir_file') . $filefilename);

                $csv = new SplFileObject('files/' . $filefilename, 'r');
                $csv->setFlags(SplFileObject::READ_CSV);
                $csv->setCsvControl(';', '"', '"');



                $cnt=0;
                foreach($csv as $ligne)
                {
                    $cnt+=1;
                }
                $cnt_c=0;

                foreach($csv as $ligne)
                {
                    if($cnt-1 == $cnt_c){
                        break;
                    }

                    if(is_null($ligne[0])){
                        $this->addFlash('error', 'le champ "Nom" ne doit pas etre nulle ');
                        break;
                    }
                    $eleve= new Eleves();
                    $eleve->setCollege($college);
                    dump($ligne[0]);
                    $eleve->setNom($ligne[0]);
                    $eleve->setPrenom($ligne[1]);
                    $eleve->setEmail($ligne[2]);
                    $eleve->setClasse($ligne[3]);
                  $eleve->setRole('ROLE_USER');

                    $manager->persist($eleve);
                    $manager->flush();
                   $cnt_c +=1;
                }

            unlink('files/' . $filefilename);
            }


        return $this->redirectToRoute('index2',['id'=>$id]);

    }

    /**
     * @Route("/upload_profs/{id}")
     */

    public function upload_profs(CollegeRepository $collegeRepository, $id , EntityManagerInterface $manager, ElevesRepository $elevesRepository)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }



        $file=$_FILES['fichier'];
        dump($file['name']);
        $info = new SplFileInfo($file['name']);
        if ($info->getExtension() != 'csv'){
            $this->addFlash('error', 'le fichier n\'est pas un fichier csv');
        }else {



            $filefilename = uniqid() . '.csv' ;
            move_uploaded_file($_FILES["fichier"]["tmp_name"],
                $this->getParameter('upload_dir_file') . $filefilename);

            $csv = new SplFileObject('files/' . $filefilename, 'r');
            $csv->setFlags(SplFileObject::READ_CSV);
            $csv->setCsvControl(';', '"', '"');



            $cnt=0;
            foreach($csv as $ligne)
            {
                $cnt+=1;
            }
            $cnt_c=0;

            foreach($csv as $ligne)
            {
                if($cnt-1 == $cnt_c){
                    break;
                }

                if(is_null($ligne[0])){
                    $this->addFlash('error', 'le champ "Nom" ne doit pas etre nulle ');
                    break;
                }
                $eleve= new Eleves();
                $eleve->setCollege($college);
                dump($ligne[0]);
                $eleve->setNom($ligne[0]);
                $eleve->setPrenom($ligne[1]);
                $eleve->setEmail($ligne[2]);
                $eleve->setClasse($ligne[3]);
                $eleve->setRole('ROLE_PROF');

                $manager->persist($eleve);
                $manager->flush();
                $cnt_c +=1;
            }

            unlink('files/' . $filefilename);
        }


        return $this->redirectToRoute('index2',['id'=>$id]);
    }





}
