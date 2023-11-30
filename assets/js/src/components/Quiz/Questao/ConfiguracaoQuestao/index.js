import React from 'react';
import isUUID from 'validator/lib/isUUID'; 
import SwitchCheckbox from "../../../SwitchCheckbox";
import actionsQuiz from '../../../../actions/Quiz';
import { useDispatch } from 'react-redux';
import { FaTrash } from 'react-icons/fa';
import { ConfigQuestao } from './styles';

function ConfiguracaoQuestao({ questao, saveQuestao }) {
    const dispatch = useDispatch();

    function excluirQuestao(){
        if(isUUID(questao.id.toString())){
          saveQuestao(false);
        }
        dispatch(actionsQuiz.setQuizExcluirQuestao());
    }

    return (
        <ConfigQuestao className="navbar navbar-expand-lg navbar-light">
            <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarConfiguracaoQuestao" aria-controls="navbarConfiguracaoQuestao" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="navbarConfiguracaoQuestao">
            <ul className="navbar-nav">
                <li className="nav-item" style={{cursor:'pointer'}} onClick={excluirQuestao} title="Excluir questão">
                    <FaTrash color="#757575" size={24}/>
                    {/* <i className="fas fa-trash" color="#757575" /> */}
                </li>
                <li className="nav-item">
                <label>Valor da questão</label>
                <input
                    style={{ width: 80 }}
                    className="form-control form-default shadow-default"
                    type="number"
                    min="0"
                    value={questao.valor ? questao.valor : 0}
                    onChange={e => dispatch(
                    actionsQuiz.setQuizQuestaoValor(
                        questao.id,
                        e.target.value
                    ))                  
                    }
                    onBlur={()=>saveQuestao(true)}
                />
                </li>
                <li className="nav-item">
                <label>Tempo de resposta<br></br>desta questão</label>
                <select 
                    type="text" 
                    className="form-control form-select form-default"
                    value={questao.tempo}
                    onChange={e => dispatch(
                    actionsQuiz.setQuizQuestaoTempo(
                        questao.id,
                        e.target.value
                    ))}
                    onBlur={()=>saveQuestao(true)}
                >
                    <option value="NONE">Sem limite</option>
                    <option value="10">10 segundos</option>
                    <option value="20">20 segundos</option>
                    <option value="30">30 segundos</option>
                    <option value="60">1 minuto</option>
                    <option value="120">2 minutos</option>
                    <option value="300">5 minutos</option>
                </select>
                </li>
                <li className="nav-item">
                <label>Obrigatória</label>
                <SwitchCheckbox 
                    checked={questao.obrigatoria} 
                    onChange={ e =>{ 
                        dispatch(
                            actionsQuiz.setQuizQuestaoObrigatoria(
                            questao.id,
                            e.target.checked
                        ));
                        saveQuestao(true, {
                            ...questao,
                            obrigatoria: e.target.checked
                        })
                    }}
                />
                </li>
                <li className="nav-item">
                <label>Mostra explicação</label>
                <SwitchCheckbox 
                    checked={questao.mostrar_explicacao}
                    onChange={e => { dispatch(
                        actionsQuiz.setQuizQuestaoMostrarExplicacao(
                            questao.id,
                            e.target.checked
                        ));
                        saveQuestao(true, {
                            ...questao,
                            mostrar_explicacao: e.target.checked
                        })
                    }}
                />
                </li>
            
            </ul>
            </div>
        </ConfigQuestao>
    );
}

export default ConfiguracaoQuestao;