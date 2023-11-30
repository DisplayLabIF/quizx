import React, { useEffect, useCallback } from "react";
import { FaEye } from "react-icons/fa";
import { Container } from "./styles";
import QuestaoTypes from "./QuestaoTypes";
import Footer from './Footer';
import FileQuestao from './FileQuestao';
import { useDispatch, useSelector } from 'react-redux';
import actionsVisualizar from '../../../../actions/Visualizar';

function Visualizar() {
  const dispatch = useDispatch();
  const quiz = useSelector(state => state.QuizReducer);
  const verificarResposta = useSelector(state => state.VisualizarReducer.verificarResposta);
  const questao = quiz.questoes[quiz.questaoSelected];

  const shuffleArray = useCallback(array => {
    if (!array) return;
    for (let i = array.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
  }, []);

  useEffect(()=>{
    dispatch(actionsVisualizar.visualizarSetVerificarResposta(false));
    dispatch(actionsVisualizar.visualizarSetResetarRespostas());
    if(questao.tipo === 'MULTIPLA_ESCOLHA'){
      var qtdRespostasCorretas = 0;
      questao.opcoes.forEach(opcao => {
        if(opcao.resposta_correta){
          qtdRespostasCorretas++;
        }
      });

      if(qtdRespostasCorretas > 1){
        dispatch(actionsVisualizar.visualizarSetMultiplaEscolhaSelected([]));
      }else if(qtdRespostasCorretas <= 1){
        dispatch(actionsVisualizar.visualizarSetMultiplaEscolhaSelected(''));
      }
      
    }else if(questao.tipo === 'V_F'){
      const trueOrFalseSelected = questao.opcoes.map((opcao, index)=>{
        return {
          resposta_correta: opcao.resposta_correta,
          selectedTrue: false,
          selectedFalse: false
        };
      });
      dispatch(actionsVisualizar.visualizarSetTrueOrFalseSelected(trueOrFalseSelected));
    }else if(questao.tipo === 'ORDENAR'){
      const newFrases = [];
      questao.opcoes.forEach(opcao => {
        newFrases.push(shuffleArray(opcao.texto.split(' ')));
      });
      dispatch(actionsVisualizar.visualizarSetOrdenarFrases(newFrases));
    }
  },[quiz]);

  function mostrarFileQuestao(){
    var existeArquivoResposta = false;
    if(questao.arquivos_questao.arquivos_resposta){
      for( let index = 0; index < questao.arquivos_questao.arquivos_resposta.length; index++){
        if(
          questao.arquivos_questao.arquivos_resposta[index].url !== undefined && 
          questao.arquivos_questao.arquivos_resposta[index].url !== ''
        ){
          existeArquivoResposta = true;
          break;
        }
      }
    }

    var existeArquivoExplicacao = false;
    if(questao.arquivos_questao.arquivos_explicacao){
      for( let index = 0; index < questao.arquivos_questao.arquivos_explicacao.length; index++){
        if(
          questao.arquivos_questao.arquivos_explicacao[index].url !== undefined && 
          questao.arquivos_questao.arquivos_explicacao[index].url !== ''
        ){
          existeArquivoExplicacao = true;
          break;
        }
      }
    }
    
    if((existeArquivoResposta) ||
      (
        (verificarResposta && questao.mostrar_explicacao) && 
        (existeArquivoExplicacao || questao.explicacao_resposta)
      )
    ){
      return true;
    }else{
      return false;
    }
  }

  function mostartExplicacao(){
    var existeArquivoExplicacao = false;
    if(questao.arquivos_questao.arquivos_explicacao){
      for( let index = 0; index < questao.arquivos_questao.arquivos_explicacao.length; index++){
        if(
          questao.arquivos_questao.arquivos_explicacao[index].url !== undefined && 
          questao.arquivos_questao.arquivos_explicacao[index].url !== ''
        ){
          existeArquivoExplicacao = true;
          break;
        }
      }
    }

    if(verificarResposta && questao.mostrar_explicacao 
      && (existeArquivoExplicacao || questao.explicacao_resposta)){
      return true;
    }else{
      return false;
    }
  }
  

  return (
    <Container>
      <FaEye /> <span>Pré-visualização da questão</span>
      <div className="row pt-3">
        { mostrarFileQuestao() &&
          <div className="col-12 col-lg-5 col-xl-5 mt-2">
            {questao.arquivos_questao.arquivos_resposta.map((arquivo_resposta, index)=>(
              <FileQuestao 
                key={index}
                respostaOuExplicacao={'RESPOSTA'} 
                arquivo={arquivo_resposta} 
                index={index}
              ></FileQuestao>
            ))}
            { mostartExplicacao() &&
              <div className="mt-4">
                  <label>
                      <i className="fas fa-info-circle" title="Explicação"></i> Explicação da resposta 
                  </label>
                  {questao.arquivos_questao.arquivos_explicacao.map((arquivo_explicacao, index)=>(
                    <FileQuestao 
                      key={index}
                      respostaOuExplicacao={'EXPLICACAO'} 
                      arquivo={arquivo_explicacao} 
                      index={index}
                    ></FileQuestao>
                  ))}
                  <p className="text-justify" dangerouslySetInnerHTML={{ __html: questao.explicacao_resposta }}></p>
              </div>
            }
          </div>
        }       
        <div className={mostrarFileQuestao() ? "col-12 col-lg-7 col-xl-7 mt-2" : "col-12 mt-2"}>
          <p className="text-justify" dangerouslySetInnerHTML={{ __html: questao.pergunta }}></p>
          <QuestaoTypes />
        </div>
      </div>
      <Footer />
    </Container>
  );
}

export default Visualizar;
