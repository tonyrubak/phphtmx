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
        $date = '20231103';
        $db = new \SQLite3('/app/db.dbi', SQLITE3_OPEN_READONLY);
        $stmt = $db->prepare('select * from schedule;');
        $returned_set = $stmt->execute();

        $schedule = [];

        while($result = $returned_set->fetchArray())
        {
            $shift = $result['shift'];
            if (!array_key_exists($shift, $schedule))
            {
                $schedule[$shift] = [];
            }
            $schedule[$shift][] = $result['empID'];
        }
                        
        return $this->render('main.html.twig', [
            'schedule' => $schedule,
            'date' => $date,
        ]);
    }
}
