import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import actionsQuiz from '../../../../../actions/Quiz';
import Checkbox from './../../../../Checkbox'
import ImagemOpcao from '../imagemOpcao';
import { Wrapper } from '../styles';
import { FaTrash } from 'react-icons/fa';

function Opcao({ saveOpcao, excluirOpcao, upload }) {
  const dispatch = useDispatch();

  const quiz = useSelector(state => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];
  const opcoes = questao.opcoes;
  
  return (
    <>
      {opcoes.map((opcao, index) => (
        <Wrapper 
          key={index} 
          className={`${opcao.resposta_correta ? 'bg-default' : 'bg-gray'} mt-3 mb-3 border-radius`}
        >
          <Checkbox 
            checked={opcao.resposta_correta}
            onChange={e => {
              dispatch(
                actionsQuiz.setQuizOpcaoRespostaCorreta(
                  quiz.questaoSelected, 
                  index,
                  e.target.checked
              ));
              if(opcao.id){
                saveOpcao(true, index);
              }
          }}
          />
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
            style={{
              display: opcao.imagem !== undefined && opcao.imagem && 'none',
            }}
            onBlur={()=>saveOpcao(true, index)}
          />
          <ImagemOpcao upload={upload} opcao={opcao} index={index} />
          <button 
            className="btn pr-0 flex-center-default" 
            onClick={()=>excluirOpcao(opcao.id, index)}
            title="Excluir opção"
          >
            <FaTrash color="#FC6D5D" size={17}/>
            {/* <i className="fas fa-trash fa-lg"  /> */}
          </button>
        </Wrapper>
      ))}
    </>
  );
}

export default Opcao;