<?php

namespace App\Entity\User;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 */
class AdmQuiz extends User
{
    /**
     * AdmQuiz constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->tipo = self::ADM_QUIZ;
    }


    public function toArray()
    {
        // TODO: Implement toArray() method.
        return array_merge(
            $this->toArrayEntityTrait(),
            [
                'tipo' => $this->tipo
            ]
        );
    }


    /**
     * @return string
     */
    public function getTipo()
    {
        return self::ADM_QUIZ;
    }
}
