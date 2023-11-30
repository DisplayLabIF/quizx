import React, { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { Container, CorrecaoQuestao, ButtonsPularVerificar } from "./styles";
import TempoResposta from "./TempoResposta";
import { MdCancel, MdCheckCircle } from "react-icons/md";
import actionsQuiz from '../../../../../actions/Quiz';
import actionsVisualizar from '../../../../../actions/Visualizar';

function Footer() {
  const dispatch = useDispatch();
  const quiz = useSelector(state => state.QuizReducer);
  const visualizar = useSelector(state => state.VisualizarReducer);
  const questao = quiz.questoes[quiz.questaoSelected];

  const [ correta, setCorreta ] = useState();

  function proximaQuestao(){
    if((quiz.questoes.length-1) !== quiz.questaoSelected)
      dispatch(actionsQuiz.setQuizQuestaoSelected(quiz.questaoSelected+1));
    }

  function verificar(){
    setCorreta(false);
    if(questao.tipo === 'MULTIPLA_ESCOLHA'){
      verificarRespostaMultiplaEscolha();
    }else if(questao.tipo === 'V_F'){
      verificarRespostaTrueOrFalse();
    }else if(questao.tipo === 'ABERTA'){
      verificarRespostaAberta();
    }else if(questao.tipo === 'ORDENAR'){
      verificarRespostaOrdenar();
    }
    else if(questao.tipo === 'RESPOSTA_VOZ'){
      verificarRespostaVoz();
    }
  }

  function verificarRespostaMultiplaEscolha(){
    if(Array.isArray(visualizar.multiplaEscolhaSelected)){
      for(let index = 0; index < questao.opcoes.length ; index++){
        if(questao.opcoes[index].resposta_correta){
          if(visualizar.multiplaEscolhaSelected.includes(questao.opcoes[index].id)){
            setCorreta(true);
          }else{
            setCorreta(false);
            break;
          }
        }else{
          if(visualizar.multiplaEscolhaSelected.includes(questao.opcoes[index].id)){
            setCorreta(false);
            break;
          }
        }
      }

    }else{
      for(let index = 0; index < questao.opcoes.length ; index++){
        if(visualizar.multiplaEscolhaSelected === questao.opcoes[index].id){
          if(questao.opcoes[index].resposta_correta){
            setCorreta(true);
          }else{
            setCorreta(false);
          }
          break;
        }
      }
    }
    
    dispatch(actionsVisualizar.visualizarSetVerificarResposta(true));
  }

  function verificarRespostaTrueOrFalse(){
    var correcao = false;
    
    for(let index = 0; index < questao.opcoes.length ; index++){
      if(visualizar.trueOrFalseSelected[index].resposta_correta){
        if(visualizar.trueOrFalseSelected[index].selectedTrue){
          correcao = true;
        }else{
          correcao = false;
          break;
        }
      }else{
        if(visualizar.trueOrFalseSelected[index].selectedFalse){
          correcao = true;
        }else{
          correcao = false;
          break;
        }
      }
    }
    setCorreta(correcao);
    dispatch(actionsVisualizar.visualizarSetVerificarResposta(true));

  }
  
  function verificarRespostaAberta(){
    var correcao = false;

    for(let index = 0; index < questao.opcoes.length ; index++){
      if((visualizar.respostaAberta === questao.opcoes[index].texto) || (visualizar.respostaAberta.includes(questao.opcoes[index].texto))){
        correcao = true;
        break;
      }else{
        correcao = false;
      }
    }
    setCorreta(correcao);
    dispatch(actionsVisualizar.visualizarSetVerificarResposta(true));
  }

  function verificarRespostaOrdenar(){
    const resposta = visualizar.ordenarFrases.map(palavras => palavras.join(' '));
    var correcao = true;
    for(let index = 0; index < resposta.length ; index++){
      if (questao.opcoes[index].texto !== resposta[index]) {
        correcao = false;
        break;
      }
    }
    setCorreta(correcao);
    dispatch(actionsVisualizar.visualizarSetVerificarResposta(true));
  }

  function verificarRespostaVoz(){
    setCorreta(visualizar.resultadoRespostaVoz);
    dispatch(actionsVisualizar.visualizarSetVerificarResposta(true));
  }

  return (
    <Container>
      <ButtonsPularVerificar verificarResposta={visualizar.verificarResposta}>
        <div className="progress" style={{height: 4, width: '100%'}}>
          <div id="barra-progress-tempo" className="progress-bar bg-warning" role="progressbar" style={{width: '0'}} aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div className="d-flex justify-content-between align-items-center mt-3" style={{width: '100%'}}>
          <button type="button" className="btn btn-secondary shadow-default next ml-3" onClick={proximaQuestao}>PULAR</button>
          <TempoResposta tempo={questao.tempo} />
          <button id="verificar-resposta-quiz" type="button" className="btn btn-default shadow-default back mr-3" onClick={verificar}>VERIFICAR</button>
        </div>
      </ButtonsPularVerificar>
      <CorrecaoQuestao show={visualizar.verificarResposta} correta={correta}>
            { correta ?
              <div className="feedBack ml-3">
                <MdCheckCircle size={50} color={'white'}/>
                <p>Correto!<span><br/>+ {questao.valor} pontos</span></p>    
              </div>
              :
              <div className="feedBack ml-3">
                <MdCancel size={50} color={'white'} />
                <p>Errou!</p>    
              </div>
            }
          <button id="continuar-proxima-questao" type="button" className="btn btn-white shadow-default back mr-3" onClick={proximaQuestao}>CONTINUAR</button>
      </CorrecaoQuestao>
    </Container>
  );
}

export default Footer;
