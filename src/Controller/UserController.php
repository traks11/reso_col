<?php

namespace App\Controller;

use App\Entity\Eleves;
use App\Entity\User;
use App\Form\EleveType;
use App\Form\RegistrationType;
use App\Form\UsersType;
use App\Repository\CollegeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {

        // creation variable pour le nom de l'ancienne image
        // pour le user à modifier
        $oldAvatar=null;

        if(is_null($this->getUser())){
            // nouveau user
            $user = new User();
        }else{
            // user à modifier (le connecté)
            $user =$this->getUser();


        }

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                // cryptage du mot de passe
                $encodedPassword = $passwordEncoder->encodePassword(
                    $user,
                    $user->getPlainpassword()
                );
                $user->setPassword($encodedPassword);

                    // insertion dans la BDD
                    $manager->persist($user);
                    $manager->flush();


                    $this->addFlash('success', 'Votre compte est opérationnel');

                    // dump($user);
                    // retour de l'objet userController vers index
                    return $this->redirectToRoute('app_user_login');
            }

            else {
                $this->addFlash('error', 'le formulaire contient des erreurs');
            }
        }

        // retour de l'objet userController vers inscription
        return $this->render(
            'user/register.html.twig',
            ['form' => $form->createView(), 'oldAvatar' => $oldAvatar]
        );
    }


    /**
     * @Route("/login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        // utilisation de la classe authentification
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        dump($error);
        dump($lastUsername);
        if(!empty($error)){
            $this->addFlash('error', 'Identifiants incorrects');
        }

        return $this->render(
            'user/login.html.twig',
            ['last_username' => $lastUsername]
        );
    }


    /**
     * @Route("/logout")
     */
    public function logout()
    {
        return $this->render(
            'user/login.html.twig'

        );


    }

    /**
     * function pour ajouter un éléve manuellement
     * @Route("/addeleve/{id}")
     *
     */

    public function addEleve(Request $request, CollegeRepository $collegeRepository, EntityManagerInterface $manager, $id)
    {
        // verifier que l'user qui inser la liste et bien l'admin du collége si non redirection vers index2
        $college=$collegeRepository->findOneBy(['id'=>$id]);
        if($college->getUser()!= $this->getUser()){
            return $this->redirectToRoute('index2');
        }
        // on crée un objet user
        $eleve = new Eleves();
        // on crée le formulaire d'apres le type de "UserType.php"
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $eleve->setAvatar('avatar_def');
                $eleve->setRole('ROLE_USER');
                $eleve->setCollege($college);
                // insertion dans la BDD
                $manager->persist($eleve);
                $manager->flush();


                $this->addFlash('success', 'L\'Utilisateur a été ajouté ');

                // dump($user);
                // retour de l'objet userController vers index

                return $this->redirectToRoute('app_liste_index',['id'=>$id]);
            }

            else {
                $this->addFlash('error', 'le formulaire contient des erreurs');
            }
          // return $this->redirectToRoute('app_liste_index',['id'=>$id]);
        }
        // retour de l'objet userController vers inscription
        return $this->render(
            'user/addeleve.html.twig',
            ['form' => $form->createView()]
        );

    }

}
