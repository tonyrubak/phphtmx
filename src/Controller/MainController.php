<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        $schedule = [
            '0530' => ['PT', 'HK'],
            '0645' => ['OH', 'CO'],
            '0700' => ['SB', 'AD', 'PL', 'JW', 'CB'],
        ];
        return $this->render('main.html.twig', [
            'schedule' => $schedule,
        ]);
    }
}
