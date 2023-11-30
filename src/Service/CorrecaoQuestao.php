<?php

namespace App\Service;

class CorrecaoQuestao
{
    public function corrigirMultiplaEscolha($opcoes, $resposta)
    {
        $qtdRespostasCorretas = 0;
        $qtdRespostasErradas = 0;

        if (is_array($resposta)) {

            $qtdOpcoesCorretas = 0;
            foreach ($opcoes as $opcao) {
                if ($opcao->getRespostaCorreta())
                    $qtdOpcoesCorretas++;
            }

            foreach ($opcoes as $opcao) {
                if ($opcao->getRespostaCorreta()) {
                    if (in_array($opcao->getId(), $resposta)) {
                        $qtdRespostasCorretas++;
                    }
                } else {
                    if (in_array($opcao->getId(), $resposta)) {
                        $qtdRespostasErradas++;
                    }
                }
            }

            if ($qtdRespostasCorretas === 0) {
                return false;
            } else if ($qtdRespostasCorretas === $qtdOpcoesCorretas) {
                $qtdRespostasCorretas -= $qtdRespostasErradas;
            }

            return $qtdRespostasCorretas;
        } else {
            foreach ($opcoes as $opcao) {
                if ($opcao->getRespostaCorreta()) {
                    if ($opcao->getId() === $resposta) {
                        return true;
                    }
                }
            }
            return false;
        }
    }

    public function corrigirVF($respostas, $respostasCorretas)
    {

        $qtdRespostasCorretas = 0;


        foreach($respostas as $key => $value) {
            if($respostasCorretas[$key] == $value) $qtdRespostasCorretas++;
        }

        return $qtdRespostasCorretas;
    }

    
}
