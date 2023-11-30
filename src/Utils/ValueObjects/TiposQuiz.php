<?php


namespace App\Utils\ValueObjects;


class TiposQuiz
{
    private $tiposPorPerfil = [
        'PROFESSOR' => [
            'PROFESSOR_QUIZ',
            'PROFESSOR_NIVELAMENTO',
            'PROFESSOR_PESQUISA'
        ],
        'NEGOCIO' => [
            'NEGOCIO_NPS',
        ]
    ];

    private $tipos = [
        'PROFESSOR_QUIZ',
        'PROFESSOR_NIVELAMENTO',
        'NEGOCIO_NPS',
        'PROFESSOR_PESQUISA'
    ];

}