<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ConfigVariableRepository;

class ConfigVariableController extends AbstractController
{
    #[Route('/api/config', name: 'app_config_variable')]
    public function getAllVariables(ConfigVariableRepository $repo): Response
    {
        $varList = $repo->findAllVariables();

        return $this->json(['results' => $varList]);
    }

    #[Route('/api/config/section', name: 'app_config_sections')]
    public function getSections(ConfigVariableRepository $repo): Response
    {
        $sectionList = $repo->getAllSections();
        $returnList = [];
        foreach ($sectionList as $section) {
            $sectionValue = $section["section"];
            $returnList[] = $sectionValue;
        }

        return $this->json(['results' => $returnList]);
    }

    #[Route('/api/config/section/{section}', name: 'app_config_variableBySection')]
    public function getVariablesBySection(ConfigVariableRepository $repo, string $section): Response
    {
        if(\is_null($section) || $section == '') {
            return new Response("Invalid section value", 404);
        }

        $varList = $repo->findAllBySection($section);

        return $this->json(['results' => $varList]);
    }

    #[Route('/api/config/var/{key}', name: 'app_config_variable')]
    public function getByKey(ConfigVariableRepository $repo, string $key): Response
    {
        $var = $repo->findVariableByKey($key);

        return $this->json($var);
    }
}
