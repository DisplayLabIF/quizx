<?php

/**
 * Created by PhpStorm.
 * User: josemar
 * Date: 04/11/18
 * Time: 09:35
 */

namespace App\Service;


use Doctrine\ORM\EntityManager;
use mikehaertl\wkhtmlto\Pdf;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BuildConfigPdf
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $orientation
     * @param int $marginTop
     * @param int $marginLeft
     * @param int $marginRight
     * @param int $marginBottom
     * @return Pdf
     */
    public function getConfig($orientation = 'portrait', $marginTop = 5, $marginLeft = 5, $marginRight = 5, $marginBottom = 5)
    {
        $page = 0;
        $pageTitle = "Teste";
        //GERA PDF
        $pdf = new Pdf(array(
            // 'tmpDir' => __DIR__  . '/../../pdf/',
            'encoding' => 'UTF-8',  // option with argument,
            'commandOptions' => array(
                'escapeArgs' => false,
                'procOptions' => array(
                    // This will bypass the cmd.exe which seems to be recommended on Windows
                    'bypass_shell' => true,
                    // Also worth a try if you get unexplainable errors
                    'suppress_errors' => true,
                ),
            ),
            'replace' => array(
                '{page}' => $page++,
                '{title}' => $pageTitle,
            ),
            //'footer-html' => '<div class="page-counter">Page <span class="page"></span> of <span class="topage"></span></div>',
            'orientation' => $orientation,
            'margin-top' => $marginTop,
            'margin-right' => $marginRight,
            'margin-bottom' => $marginBottom,
            'margin-left' => $marginLeft,
            'header-spacing' => 5,
            'footer-spacing' => 2,
            'user-style-sheet' => [__DIR__  . '/../../assets/css/pdf.css'],
            'enable-local-file-access'
        ));
        return $pdf;
    }
}
