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
                'statut' => 201,
                'messag' => 'L\'utilisateur a été créé'
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

    public function ajoutpartenaire(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager,ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if (isset($values->ninea, $values->raisonsociale)) {
            $part = new Partenaire();

            $uers = $this->getDoctrine()->getRepository(User::class)->find($values->adminsuper);
            $part->setAdminsuper($uers);
            $part->setNinea($values->ninea);
            $part->setRaisonsociale($values->raisonsociale);
            $part->setAdresse($values->adresse);
            $part->setStatut($values->statut);



            $errors = $validator->validate($part);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $entityManager->persist($part);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Le  partenaire a été ajouté avec success'
            ];

            return new JsonResponse($data, 201);
        }
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
        /**
        * @Route("/partenaires/{id}", name="update_partenaire", methods={"PUT"})
        */
        public function updatepartenaire(Request $request, SerializerInterface $serializer, Partenaire $partenaire, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    
        {
            $partenaireUpdate = $entityManager->getRepository(Partenaire::class)->find($partenaire->getId());
            $data = json_decode($request->getContent());
            foreach ($data as $key => $value){
                if($key && !empty($value)) {
                    $name = ucfirst($key);
                    $setter = 'set'.$name;
                    $partenaireUpdate->$setter($value);
                }
            }
            $errors = $validator->validate($partenaireUpdate);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                    ]);
                }
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Le partenaire a bien été mis à jour'
                ];
                return new JsonResponse($data);
            }
}


