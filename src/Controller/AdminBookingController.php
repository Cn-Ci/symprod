<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking", name="admin_booking_index")
     */
    public function index(BookingRepository $repo): Response
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * 
     * @Route ("admin/booking/{id}/edit", name="admin_booking_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$booking->getId()}</strong> a bien été modifiée !"
            );
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking,
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * 
     * @param booking $booking
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(booking $booking, EntityManagerInterface $manager)
    {
            $manager->remove($booking);
            $manager->flush();
            $this->addFlash( 
                'success',
                "L'annonce <strong>{$booking->getBooker()->getFullName()}</strong> a bien été supprimer !"
            );
            return $this->redirectToRoute('admin_booking_index');
       
    }
}
