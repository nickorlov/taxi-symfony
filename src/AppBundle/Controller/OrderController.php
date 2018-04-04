<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class OrderController extends Controller
{
    /**
     * @Route("/admin/add-order", name="add_order")
     */
    public function addAction(Request $request)
    {
        $order = new Order();

        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $drivers = $repository->find(1)->getName();
        var_dump($drivers);

        $form = $this->createFormBuilder($order)
            ->add('route', TextType::class)
            ->add('date', DateType::class)
            ->add('driver', ChoiceType::class, [
                    'choices' => [
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_DRIVER' => 'ROLE_DRIVER',
                        'ROLE_MANAGER' => 'ROLE_MANAGER'
                    ]
                ]
            )
            ->add('client', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Create a order'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            //return $this->redirectToRoute('task_success');
            echo 'Success!';
        }

        return $this->render('order/addOrder.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/orders")
     */
    public function showAction()
    {
        $orders = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->findAll();

        if (!$orders) {
            throw $this->createNotFoundException(
                'No orders found!'
            );
        }

        return $this->render(
            'order/index.html.twig',
            [
                'orders' => $orders
            ]
        );
    }
}