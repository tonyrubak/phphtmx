<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Model\ShiftRequestDto;

class ShiftRequestController extends AbstractController
{
    #[Route('/shiftRequest', methods: ['GET', 'HEAD'])]
    public function generateRequestHtml(
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

    #[Route('/shiftRequest', methods: ['POST'])]
    public function createRequest(
        #[MapRequestPayload] ShiftRequestDTO $shiftRequestDto
    ): Response {
        $db = new \SQLite3('/app/db.dbi', SQLITE3_OPEN_READWRITE);
        $stmt = $db->prepare('insert into requests values (:employee, :date, :requestType, :shift, 0);');
        $stmt->bindValue(':employee', $shiftRequestDto->employee, SQLITE3_TEXT);
        $stmt->bindValue(':date', $shiftRequestDto->formDate, SQLITE3_INTEGER);
        $stmt->bindValue(':requestType', $shiftRequestDto->requestType, SQLITE3_TEXT);
        $stmt->bindValue(':shift', $shiftRequestDto->shift, SQLITE3_TEXT);
        $result = $stmt->execute();
        $message = match ($result) {
            false => 'Something went wrong',
            default => 'The request was entered',
        };
        $response = $this->render('_ResultModal.html.twig', [
            'message' => $message]);
        return $response;
    }
}
