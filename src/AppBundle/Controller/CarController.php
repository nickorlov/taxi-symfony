<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Car;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class CarController extends Controller
{
    /**
     * @Route("/admin/add-car", name="add_car")
     */
    public function addAction(Request $request)
    {
        $car = new Car();

        $form = $this->createFormBuilder($car)
            ->add('name', TextType::class)
            ->add('numbers', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Create a car'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            //return $this->redirectToRoute('task_success');
            echo 'Success!';
        }

        return $this->render('car/addCar.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/cars")
     */
    public function showAction()
    {
        $cars = $this->getDoctrine()
            ->getRepository('AppBundle:Car')
            ->findJoinedToUser();

        if (!$cars) {
            throw $this->createNotFoundException(
                'No cars found!'
            );
        }

        return $this->render(
            'car/index.html.twig',
            [
                'cars' => $cars
            ]
        );
    }

    /**
     * @Route("/admin/set-car")
     */
    public function changeAction(Request $request)
    {

        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        $form = $this->createFormBuilder($users)
            ->add('driver_name', EntityType::class, [
                    'class' => 'AppBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.roles LIKE :role')
                            ->setParameter('role', '%"ROLE_DRIVER"%');
                    },
                    'choice_label' => function ($driver) {
                        return $driver->getName() . ' ' . $driver->getSurname();
                    }
                ]
            )
            ->add('car', EntityType::class, [
                    'class' => 'AppBundle:Car',
                    'choice_label' => 'name'
                ]
            )
            ->add('submit', SubmitType::class, array('label' => 'Set car'))
            ->getForm();

        $form->handleRequest($request);

        if (!$users) {
            throw $this->createNotFoundException(
                'No items found!'
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $car = $em->getRepository('AppBundle:Car')
                ->find($result['car']->getId());

            $user = $em->getRepository('AppBundle:User')
                ->find($result['driver_name']->getId());

            $user->addCar($car);

            $em->persist($user);
            $em->persist($car);
            $em->flush();
            echo 'Success!';


        }

        return $this->render(
            'car/setCar.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}