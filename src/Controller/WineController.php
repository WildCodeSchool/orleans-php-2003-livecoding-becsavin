<?php


namespace App\Controller;


use App\Model\WineManager;

class WineController extends AbstractController
{
    public function index()
    {
        $wineManager = new WineManager();
        $wines = $wineManager->selectAll();

        return $this->twig->render('Wine/index.html.twig', ['wines' => $wines]);
    }
}
