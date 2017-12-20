<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Controller;

use CorahnRin\Entity\Characters;
use CorahnRin\SheetsManagers\PdfManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CharacterExportPDFController extends Controller
{
    private $pdfManager;

    public function __construct(PdfManager $pdfManager)
    {
        $this->pdfManager = $pdfManager;
    }

    /**
     * @Route(
     *     "/characters/export/{id}-{nameSlug}.{_format}",
     *     name="corahnrin_character_export_pdf",
     *     requirements={"_format": "pdf"},
     *     methods={"GET"}
     * )
     *
     * @param Characters $character
     * @param Request    $request
     *
     * @return Response
     */
    public function exportAction(Characters $character, Request $request)
    {
        $response = new Response();

        $printer_friendly = $request->query->get('printer_friendly') === 'true';
        $sheet_type       = $request->query->get('sheet_type') ?: 'original';

        $output_dir = $this->get('service_container')->getParameter('kernel.cache_dir').'/characters_output';
        if (!is_dir($output_dir)) {
            $this->get('filesystem')->mkdir($output_dir, 0777);
        }

        $file_name = ucfirst($character->getNameSlug()).
            ($printer_friendly ? '-pf' : '').
            '-'.$sheet_type.
            '-'.$this->get('translator')->getLocale().
            '-'.$character->getId().
            '.pdf'
        ;

        // Generate the PDF if the file doesn't exist yet,
        // or if we're in debug mode.
        if (!file_exists($output_dir.$file_name) || $this->getParameter('kernel.debug')) {
            $this->pdfManager
                ->generateSheet($character, $printer_friendly)
                ->Output($output_dir.$file_name, 'F')
            ;
        }

        $response->setContent(file_get_contents($output_dir.$file_name));
        $response->headers->add(['Content-type' => 'application/pdf']);

        return $response;
    }
}
