import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import MultiplaEscolha from './MultiplaEscolha';
import VF from './VF';
import Aberta from './Aberta';
import RespostaVoz from './RespostaVoz';
import Ordenar from './Ordenar';
import actionsQuiz from '../../../../actions/Quiz';
import apiQuizClass from '../../../../services/apiQuizClass'

function Questao({save}) {

  const dispatch = useDispatch();
  
  const quiz = useSelector(state => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];
  const opcoes = questao.opcoes;

  async function saveOpcao(active, indiceOpcao) {
    document.getElementById('box-salvando').style.display='block';
    const data ={
      opcao:{
        ...opcoes[indiceOpcao],
        active
      },
      questao_id: questao.id
    };

    try {
      const response = !opcoes[indiceOpcao].id
      ? await apiQuizClass.post('/questoes/opcoes', data)
      : await apiQuizClass.put(`/questoes/opcoes/${opcoes[indiceOpcao].id}`, data);
      
      if(opcoes[indiceOpcao] && !opcoes[indiceOpcao].id){
        dispatch(actionsQuiz.setQuizOpcaoId(quiz.questaoSelected, indiceOpcao, response.data.opcao_id));
      }

      document.getElementById('box-salvando').style.display='none';
      if(response.status !== 200){
        document.getElementById('box-salvando-erro').style.display='block';
        setTimeout(function() {
            document.getElementById('box-salvando-erro').style.display='none';
        }, 2000);
      }
    } catch (e) {
      console.log(e)
      document.getElementById('box-salvando').style.display='none';
      document.getElementById('box-salvando-erro').style.display='block';
      setTimeout(function() {
          document.getElementById('box-salvando-erro').style.display='none';
      }, 2000);
    }        
  }

  function excluirOpcao(opcaoId, indiceOpcao){
    if(opcaoId){
      saveOpcao(false, indiceOpcao);
    }
    dispatch(actionsQuiz.excluirQuizOpcao(quiz.questaoSelected, indiceOpcao));
  }

  function addOpcao() {
    dispatch(actionsQuiz.setQuizAddOpcao(questao.id));
    save(true);
  }

  async function upload(e, index) {
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('quiz_id', quiz.id);
    formData.append('questao_id', questao.id);
    formData.append('opcao_id', opcoes[index].id);
    formData.append('numero_opcao', opcoes[index].numero_opcao);

    document.getElementById(`label-image-upload-opcao_${opcoes[index].id}`).style.display = 'none';
    document.getElementById(`feedback-image-upload-opcao_${opcoes[index].id}`).style.display = 'block';
    document.getElementById(`feedback-image-upload-opcao_${opcoes[index].id}`).innerHTML = 'Enviando...';

    
    try {
        const response = await apiQuizClass.post('/upload', formData);
        dispatch(actionsQuiz.setQuizOpcaoImagem(quiz.questaoSelected, index, response.data.url));
        dispatch(actionsQuiz.setQuizId(response.data.quiz_id));
        dispatch(actionsQuiz.setQuizQuestaoId(questao.id, response.data.questao_id));
        dispatch(actionsQuiz.setQuizOpcaoId(quiz.questaoSelected, index, response.data.opcao_id));
    } catch (e) {
      document.getElementById(`feedback-image-upload-opcao_${opcoes[index].id}`).innerHTML = 'Erro!'
      setTimeout(function() {
        document.getElementById(`label-image-upload-opcao_${opcoes[index].id}`).style.display = 'block';
        document.getElementById(`feedback-image-upload-opcao_${opcoes[index].id}`).style.display = 'none';
      }, 3000);
    }
  }
  
  const layouts = {
    MULTIPLA_ESCOLHA: (
      <MultiplaEscolha
        saveOpcao={saveOpcao}
        excluirOpcao={excluirOpcao}
        upload={upload}
      />
    ),
    V_F: (
      <VF
        saveQuestao={save}
        saveOpcao={saveOpcao}
        excluirOpcao={excluirOpcao}
        upload={upload}
      />
    ),
    ABERTA: (
      <Aberta
        saveOpcao={saveOpcao}
        excluirOpcao={excluirOpcao}
      />
    ),
    RESPOSTA_VOZ: (
      <RespostaVoz
        saveOpcao={saveOpcao}
        excluirOpcao={excluirOpcao}
      />
    ),
    ORDENAR: (
      <Ordenar
        saveOpcao={saveOpcao}
        excluirOpcao={excluirOpcao}
      />
    ),
  };

  if (layouts[questao.tipo]) return (
    <div>
      <div className="row align-middle m-1 pt-4">
        <div className="mr-auto">
            <label>Alternativas de resposta</label>
        </div>
        <div>
            <button type="button" className="btn btn-default btn-sm" onClick={addOpcao}><i className="fas fa-plus"></i> Adicionar alternativa</button>
        </div>
      </div>
      {layouts[questao.tipo]}
    </div>
    
  );

  return '';
}

export default Questao;
