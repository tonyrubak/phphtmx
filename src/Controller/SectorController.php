<?php
// src/Controller/SectorController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

enum MainworldType : string
{
    case Planet = 'Planet';
    case CloseSatellite = 'Close Satellite';
    case FarSatellite = 'Far Satelltie';
}

function hex_encode($num) {
    return match ($num) {
        10 => 'A',
        11 => 'B',
        12 => 'C',
        13 => 'D',
        14 => 'E',
        15 => 'F',
        16 => 'G',
        17 => 'H',
        18 => 'I',
        19 => 'J',
        20 => 'K',
        21 => 'L',
        22 => 'M',
        23 => 'N',
        24 => 'O',
        default => $num
    };
}

function flux() {
    return \rand(1,6) - \rand(1,6);
}

class SectorController extends AbstractController
{
    #[Route('/sector', methods: ['GET', 'HEAD'])]
    public function show(
    ): Response
    {                        
        $starport = match (\rand(1,6) + \rand(1,6)) {
            2, 3, 4 => 'A',
            5, 6 => 'B',
            7, 8 => 'C',
            9 => 'D',
            10, 11 => 'E',
            12 => 'X',
        };

        $mw_type = match (flux()) {
            -5, -4 => MainworldType::FarSatellite,
            -3 => MainworldType::CloseSatellite,
            default => MainworldType::Planet
        };

        $hz_var = match (flux()) {
            -6 => -2,
            -5, -4, -3 => -1,
            -2, -1, 0, 1, 2 => 0,
            3, 4, 5 => 1,
            6 => 2,
        };

        $climate = match (flux()) {
            -5, -4, -3 => 'Hot Tropical',
            -2, -1, 0, 1, 2 => 'Temperate',
            3, 4, 5 => 'Cold Tundra',
            6 => 'Frozen'
        };

        $population = \rand(1,6) + \rand(1,6) - 2;

        $size = \rand(1,6) + \rand(1,6) - 2;

        if ($size === 10) {
            $size = \rand(1,6) + 9;
        }

        $atm = max(flux() + $size, 0);

        $hyd = flux() + $atm;

        if ($atm < 2 || $atm > 9) {
            $hyd = $hyd + 4;
        }

        if ($size < 2) {
            $hyd = 0;
        }

        $hyd = min(max($hyd, 0), 10);

        $pop = \rand(1,6) + \rand(1,6) - 2;
        if ($pop === 10) {
            $pop = \rand(1,6) + \rand(1,6) + 3;
        }


        $gov = max(min(flux() + $pop, 15), 0);

        $law = max(min(flux() + $gov, 18), 0);

        $tl = \rand(1,6);

        if ($starport === 'A') {
            $tl = $tl + 6;
        } else if ($starport === 'B') {
            $tl = $tl + 4;
        } else if ($starport === 'C') {
            $tl = $tl + 2;
        } else if ($starport === 'F') {
            $tl = $tl + 1;
        } else if ($starport === 'X') {
            $tl = $tl - 4;
        }

        $tl = $tl + match ($size) {
            0, 1 => 2,
            2, 3, 4 => 1,
            default => 0,
        };

        $tl = $tl + match($atm) {
            0, 1, 2, 3 => 1,
            10, 11, 12, 13, 14, 15 => 1,
            default => 0,
        };

        $tl = $tl + match($hyd) {
            9 => 1,
            10 => 2,
            default => 0,
        };

        $tl = $tl + match($pop) {
            1, 2, 3, 4, 5 => 1,
            0, 6, 7, 8 => 0,
            9 => 2,
            default => 4,
        };

        $tl = $tl + match($gov) {
            0, 5 => 1,
            13 => -2,
            default => 0,
        };

        $tl = max($tl, 0);

        $response = $this->render('_SectorModal.html.twig', [
            'uwp' =>
                $starport
                . hex_encode($size)
                . hex_encode($atm)
                . hex_encode($hyd)
                . hex_encode($pop)
                . hex_encode($gov)
                . hex_encode($law)
                . '-'
                . hex_encode($tl),
            'mw_type' => $mw_type->value,
            'climate' => $climate,
        ]);
        $response->headers->set('HX-Trigger-After-Swap', 'modalContentReceived');
        return $response;
    }
}
