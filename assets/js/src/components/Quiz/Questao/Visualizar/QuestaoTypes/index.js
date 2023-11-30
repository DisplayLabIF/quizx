import React from 'react';
import { useSelector } from 'react-redux';
import Aberta from './Aberta';
import MultiplaEscolha from './MultiplaEscolha';
import Ordenar from './Ordenar';
import RespostaVoz from './RespostaVoz';
import VF from './VF';
import { Wrapper } from './styles';


function Questao() {
  const quiz = useSelector(state => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];
  const verificarResposta = useSelector(state => state.VisualizarReducer.verificarResposta);

  const layouts = {
    MULTIPLA_ESCOLHA: (
      <MultiplaEscolha
        opcoes={questao.opcoes}
      />
    ),
    V_F: (
      <VF
        opcoes={questao.opcoes}
        trueOrFalseCaracteres={questao.true_or_false_caracteres}
      />
    ),
    RESPOSTA_VOZ: (
      <RespostaVoz questaoId={questao.id}/>
    ),
    ABERTA: (
      <Aberta />
    ),
    ORDENAR: (
      <Ordenar />
    ),
  };

  if (layouts[questao.tipo]) return <Wrapper disabled={ verificarResposta && questao.tipo !== 'RESPOSTA_VOZ' ? true : false}>{layouts[questao.tipo]}</Wrapper>;

  return '';
}

export default Questao;
