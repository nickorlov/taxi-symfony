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
            ->findAll();

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
}