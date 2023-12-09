<?php
// src/Controller/ShiftDetailsController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class ShiftController extends AbstractController
{
    #[Route('/shiftDetails', methods: ['GET', 'HEAD'])]
    public function show(
        #[MapQueryParameter] string $employee,
        #[MapQueryParameter] int $date
    ): Response
    {                        
        $response = $this->render('_ShiftRequestModal.html.twig', [
            'employee' => $employee,
            'date' => $date,
        ]);
        $response->headers->set('HX-Trigger-After-Swap', 'modalContentReceived');
        return $response;
    }
}
