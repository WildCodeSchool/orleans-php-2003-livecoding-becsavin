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

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wine = array_map('trim', $_POST);

            $errors = $this->validation($wine);
            if(empty($errors)) {
                $wineManager = new WineManager();
                $wineManager->insert($wine);
                header('Location: /adminWine/index');
            }
        }

        return $this->twig->render('AdminWine/add.html.twig', ['errors'=>$errors ?? [], 'wine'=> $wine ?? []]);
    }

    private function validation(array $wine): array
    {
        $wineNameLength = $producerLength = 255;
        $startYear = 1900;
        $now = new \DateTime();
        $endYear = $now->format('Y');

        if (empty($wine['name'])) {
            $errors[] = 'Le nom du vin est requis';
        } elseif (strlen($wine['name']) > $wineNameLength) {
            $errors[] = 'Le nom du vin doit faire moins de ' . $wineNameLength;
        }

        if (isset($wine['producer']) && strlen($wine['producer']) > $producerLength) {
            $errors[] = 'Le nom du producteur doit faire moins de ' . $producerLength;
        }

        if (isset($wine['year']) && !is_numeric($wine['year'])) {
            $errors[] = 'Le format de l\'année doit être un nombre entier';
        } elseif ($wine['year'] < $startYear || $wine['year'] > $endYear) {
            $errors[] = 'L\'année doit être une valeur comprise entre ' . $startYear . ' and ' . $endYear;
        }

        return $errors ?? [];
    }

}
