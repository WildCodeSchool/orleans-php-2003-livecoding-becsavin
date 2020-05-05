<?php

namespace App\Controller;

use App\Model\RegionManager;
use App\Model\WineManager;
use App\Services\Uploader;
use DateTime;

class AdminWineController extends AbstractController
{
    public function index()
    {
        $wineManager = new WineManager();
        $winesWithRegions = $wineManager->selectAll();
        foreach ($winesWithRegions as $winesWithRegion) {
            $winesGroupByRegions[$winesWithRegion['region_name']][] = $winesWithRegion;
        }

        return $this->twig->render('AdminWine/index.html.twig', ['winesGroupByRegions' => $winesGroupByRegions ?? []]);
    }

    public function add()
    {
        $regionManager = new RegionManager();
        $regions = $regionManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wine = array_map('trim', $_POST);

            $dataErrors = $this->validation($wine, $regions);
            $uploader = new Uploader($_FILES['image']);
            $uploader->validate();
            $errors = array_merge($dataErrors, $uploader->getErrors());

            if (empty($errors)) {
                //upload du fichier
                $wine['image'] = $uploader->upload();
                // insertion en BDD
                $wineManager = new WineManager();
                $wineManager->insert($wine);
                header('Location: /adminWine/index');
            }
        }

        return $this->twig->render('AdminWine/add.html.twig', [
            'errors'  => $errors ?? [],
            'wine'    => $wine ?? [],
            'regions' => $regions ?? [],
        ]);
    }

    private function validation(array $wine, array $regions): array
    {
        $startYear = 1900;
        $now = new DateTime();
        $endYear = $now->format('Y');

        $errors[] = $this->checkRequired($wine['name'], 'name');
        $errors = array_merge($errors, $this->checkMaxLength($wine['name'], 'name'));
        $errors[] = $this->checkRequired($wine['price'], 'prix');

        if (isset($wine['price']) && !is_numeric($wine['price'])) {
            $errors[] = 'Le prix du vin doit etre une valeur numérique';
        } elseif ($wine['price'] < 0) {
            $errors[] = 'Le prix du vin doit être positif';
        }

        $errors = array_merge($errors, $this->checkMaxLength($wine['producer'], 'producteur'));

        if (isset($wine['year']) && !is_numeric($wine['year'])) {
            $errors[] = 'Le format de l\'année doit être un nombre entier';
        } elseif ($wine['year'] < $startYear || $wine['year'] > $endYear) {
            $errors[] = 'L\'année doit être une valeur comprise entre ' . $startYear . ' and ' . $endYear;
        }

        if (!in_array($wine['region_id'], array_column($regions, 'id'))) {
            $errors[] = 'La région est inconnue';
        }

        return $errors ?? [];
    }

    private function checkRequired(string $input, string $name): array
    {
        if (empty($input)) {
            $error = ['Le champ ' . $name . ' est requis'];
        }

        return $error ?? [];
    }

    private function checkMaxLength(string $input, string $name, int $max = 255): array
    {
        if (strlen($input) > $max) {
            $error = ['Le champ ' . $name . ' doit être inférieur à ' . $max];
        }

        return $error ?? [];
    }
}
