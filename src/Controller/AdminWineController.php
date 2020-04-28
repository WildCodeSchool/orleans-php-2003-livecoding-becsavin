<?php

namespace App\Controller;

use App\Model\WineManager;

class AdminWineController extends AbstractController
{
    public function index()
    {
        $wineManager = new WineManager();
        $wines = $wineManager->selectAll();

        return $this->twig->render('AdminWine/index.html.twig', ['wines' => $wines]);
    }
}
