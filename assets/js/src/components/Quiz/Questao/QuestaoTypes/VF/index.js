import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import actionsQuiz from '../../../../../actions/Quiz';
import SwitchCheckBox from './../../../../SwitchCheckbox'
import ImagemOpcao from '../imagemOpcao';
import { Wrapper } from '../styles';
import { FaTrash } from 'react-icons/fa';

function Opcao({ saveOpcao, excluirOpcao, upload, saveQuestao }) {
  const dispatch = useDispatch();

  const quiz = useSelector(state => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];
  const opcoes = questao.opcoes;
  
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
  
  return (
    <>
      {opcoes.map((opcao, index) => (
        <Wrapper 
          key={index} 
          className="bg-default mt-3 mb-3 border-radius"
        >
          <div className="text-nowrap">
            <SwitchCheckBox
              id="true_or_false_input"
              name="check-opcao"
              checked={opcao.resposta_correta ? true : false}
              onChange={e =>{
                dispatch(
                  actionsQuiz.setQuizOpcaoRespostaCorreta(
                    quiz.questaoSelected, 
                    index,
                    true
                ));
                saveOpcao(true, index)
              }}
            />
            <span 
              className="mr-2 ml-1"
              data-toggle="tooltip"
              data-placement="top" 
              title="Insira uma letra para sinalizar verdadeiro" 
              data-original-title="Insira uma letra para sinalizar verdadeiro" 
            >
              <input 
                className="true-input"
                type="text"
                value={questao.true_or_false_caracteres ? questao.true_or_false_caracteres[0] : 'V'}
                onChange={e => {
                  dispatch(
                    actionsQuiz.setQuizTrueOrFalseCaracteres(
                      quiz.questaoSelected, 
                      true,
                      e.target.value
                  ));
                  saveQuestao(true, {
                    ...questao,
                    true_or_false_caracteres: [
                      e.target.value,
                      questao.true_or_false_caracteres[1]
                    ]
                  })
                }
                }
                maxLength={1}
              />
            </span>
            <SwitchCheckBox
              id="true_or_false_input"
              name="check-opcao"
              checked={!opcao.resposta_correta ? true : false}
              onChange={e =>{
                dispatch(
                  actionsQuiz.setQuizOpcaoRespostaCorreta(
                    quiz.questaoSelected, 
                    index,
                    false
                ));
                saveOpcao(true, index)
              }}
            />
            <span 
              className="mr-2 ml-1"
              data-toggle="tooltip"
              data-placement="top" 
              title="Insira uma letra para sinalizar falso"
              data-original-title="Insira uma letra para sinalizar falso"
            >
              <input 
                type="text" 
                className="false-input"
                value={questao.true_or_false_caracteres ? questao.true_or_false_caracteres[1] : 'F'} 
                onChange={e => {
                  dispatch(
                    actionsQuiz.setQuizTrueOrFalseCaracteres(
                      quiz.questaoSelected, 
                      false,
                      e.target.value
                  ));
                  saveQuestao(true, {
                    ...questao,
                    true_or_false_caracteres: [
                      questao.true_or_false_caracteres[0],
                      e.target.value
                    ]
                  })
                }
                }
                maxLength={1}
              />
            </span>
          </div>
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
                  display: opcao.imagem !== undefined && opcao.imagem ? 'none' : 'block'
                }}
                onBlur={()=>saveOpcao(true, index)}
              ></input>
              <ImagemOpcao upload={upload} opcao={opcao} index={index} />
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