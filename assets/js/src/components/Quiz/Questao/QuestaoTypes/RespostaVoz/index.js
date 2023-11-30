import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import actionsQuiz from '../../../../../actions/Quiz';
import { Wrapper } from '../styles';
import { FaTrash } from 'react-icons/fa';

function Opcao({ saveOpcao, excluirOpcao }) {
  const dispatch = useDispatch();

  const quiz = useSelector(state => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];
  const opcoes = questao.opcoes;
  
  
  return (
    <>
    {opcoes.map((opcao, index) => (
      <Wrapper 
        key={index} 
        className="bg-default mt-3 mb-3 border-radius"
      >
            <input 
              type="text" 
              className="form-control form-default" 
              placeholder="Opção de resposta" 
              value={opcao.texto}
              onChange={e => dispatch(
                actionsQuiz.setQuizOpcaoTexto(
                  quiz.questaoSelected, 
                  index,
                  e.target.value
                ))
              }
              onBlur={()=>saveOpcao(true, index)}
            ></input>
            <button 
              className="btn flex-center-default pr-0" 
              onClick={()=>excluirOpcao(opcao.id, index)}
              title="Excluir opção"
            >
              <FaTrash color="#FC6D5D" size={17}/>
            </button>
      </Wrapper>
    ))}
    </>
  );
}

export default Opcao;