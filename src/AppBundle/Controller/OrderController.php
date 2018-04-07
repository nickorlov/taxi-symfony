<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Order;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class OrderController extends Controller
{
    /**
     * @Route("/admin/add-order", name="add_order")
     */
    public function addAction(Request $request)
    {
        $order = new Order();
        $manager = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createFormBuilder($order)
            ->add('route', TextType::class)
            ->add('date', DateTimeType::class)
            ->add('driver', EntityType::class, [
                    'class' => 'AppBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.roles LIKE :role')
                            ->setParameter('role', '%"ROLE_DRIVER"%');
                    },
                    'choice_label' => function ($driver) {
                        return $driver->getName() . ' ' . $driver->getSurname();
                    },
                    'required' => false
                ]
            )
            ->add('client', EntityType::class, [
                    'class' => 'AppBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.roles LIKE :role')
                            ->setParameter('role', '%"ROLE_USER"%');
                    },
                    'choice_label' => function ($driver) {
                        return $driver->getName() . ' ' . $driver->getSurname();
                    }
                ]
            )
            ->add('submit', SubmitType::class, array('label' => 'Create a order'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $order->setManager($manager);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            //return $this->redirectToRoute('task_success');
            echo 'Success!';
        }

        return $this->render('order/addOrder.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/orders")
     */
    public function showAction()
    {
        if (isset($_POST['finish'])) {
            $finish = $_POST['finish'];

            $em = $this->getDoctrine()->getEntityManager();
            $item = $em->getRepository('AppBundle:Order')->find($finish);
            $item->setFinished('YES');
            $em->flush();
        }

        $orders = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->findJoinedToUser();

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

    /**
     * @Route("/admin/free-orders")
     */
    public function freeOrdersAction()
    {
        $orders = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->findFreeJoinedToUser();

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