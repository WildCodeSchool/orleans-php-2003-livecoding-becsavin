<?php

namespace App\Controller;

use App\Model\WineManager;

class WineController extends AbstractController
{
    public function index()
    {
        $search = trim($_GET['search'] ?? '');

        $wineManager = new WineManager();
        $winesWithRegions = $wineManager->selectAll($search);
        foreach ($winesWithRegions as $winesWithRegion) {
            $winesGroupByRegions[$winesWithRegion['region_name']][] = $winesWithRegion;
        }

        return $this->twig->render('Wine/index.html.twig', ['winesGroupByRegions' => $winesGroupByRegions ?? []]);
    }
}
