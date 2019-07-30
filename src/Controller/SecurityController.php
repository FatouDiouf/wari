<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Partenaire;
use App\Entity\Comptebancaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }


    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if (isset($values->username, $values->password)) {
            $user = new User();
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles($values->roles);
            $user->setNom($values->nom);
            $user->setEmail($values->email);
            $user->setTelephone($values->telephone);


            // $repo = $this->getDoctrine()->getRepository(Partenaire::class);
            // $partenaires = $repo->find($values->partenaire);
            // $user->setPartenaire($partenaires);

            $repos = $this->getDoctrine()->getRepository(Comptebancaire::class);
            $compte = $repos->find($values->comptebancaire);
            $user->setComptebancaire($compte);

            $errors = $validator->validate($user);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner tous les champs'
        ];
        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }

    /**
     * @Route("/partenaire", name="add_partenaire", methods={"POST"})
     */

    public function ajoutpartenaire(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $partenaire = $serializer->deserialize($request->getContent(), Partenaire::class, 'json');
        $entityManager->persist($partenaire);
        $entityManager->flush();
        $data = [
            'vue' => 201,

            'affiche' => 'Le partenaire a bien été ajouté'
        ];

        return new JsonResponse($data, 201);
    }



    /**
     * @Route("/comptebancaire", name="add_compte", methods={"POST"})
     */

    public function ajoutcompte(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $compte = $serializer->deserialize($request->getContent(), Comptebancaire::class, 'json');
        $entityManager->persist($compte);
        $entityManager->flush();
        $data = [
            'vue' => 201,

            'afficher' => 'Le compte a bien été ajouté'
        ];

        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/depot", name="add_depot", methods={"POST"})
     */

    public function ajoutdepot(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if (isset($values->montant, $values->datedepot)) {
            $dep = new Depot();

            $depot = $this->getDoctrine()->getRepository(Comptebancaire::class);
            $depots = $depot->find($values->comptebancaire);
            $dep->setComptebancaire($depots);

            $dep->setMontant($values->montant);
            $dep->setDatedepot(new \DateTime());



            $errors = $validator->validate($dep);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $entityManager->persist($dep);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Le a été ajouté avec success'
            ];

            return new JsonResponse($data, 201);
        }
    }
}
