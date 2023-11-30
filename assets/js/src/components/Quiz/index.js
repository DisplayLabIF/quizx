import React, { useEffect } from 'react';
import Header from './Header'
import Questao from './Questao'
import { Container } from './styles';
import Visualizar from "./Questao/Visualizar";
import { useDispatch, useSelector } from 'react-redux';
import apiQuizClass from './../../services/apiQuizClass'
import actionsQuiz from '../../actions/Quiz';
import ModalCadastrarUsuario from '../Modal/CadastrarUsuario';
import PlanoAcesso from '../Modal/PlanoAcesso';

function Quiz(props) {
  const dispatch = useDispatch();
  const quiz = useSelector(state => state.QuizReducer);

  async function getQuiz(id) {
    try {
      let url = '';
      if(id === 'getSessionQuiz'){
        url = '/quizes/session';
      }else{
        url = `/quizes/${id}`;
      }
      const response = await apiQuizClass.get(url);
      dispatch(actionsQuiz.setQuiz(response.data));
    } catch (e) {
    }        
  }

  function finalizarQuiz(){
    if(localStorage.getItem('@QuizClassClientId')){
        document.getElementById("finalizar-quiz").click();
    }else{
      document.getElementById("show-modal-cadastrar-usuario").click();
      // dispatch(actionsShow.toggleShowModalCadastrarUsuario());
    }
  }

  

  useEffect(() => {
    if(props.match.params.id){
      getQuiz(props.match.params.id);
    }else if(localStorage.getItem('@QuizClassSessionId') && !localStorage.getItem('@QuizClassClientId')){
      getQuiz('getSessionQuiz');
    }
  }, []);


  useEffect(() => {
    if(quiz.id && localStorage.getItem('@QuizClassClientId')){
      props.history.push(`/app/${quiz.id}/quiz`);
    }
  }, [quiz.id]);

  return <Container className="pt-4">
      <Header quiz={quiz} finalizarQuiz={finalizarQuiz}></Header>
      {quiz.questoes.length > 0 &&
        <div>
          <Questao></Questao>
          <Visualizar/>
        </div>
      }
      
      
      <div id="box-salvando" style={{ display: 'none' }}>Salvando...</div>
      <div id="box-salvando-erro" style={{ display: 'none' }}>Erro ao salvar!</div>
      <a  id="finalizar-quiz" href={`${props.urlFinalizar.slice(0, -1)}${quiz.id}${props.typeQuiz && '?type='+props.typeQuiz}`} style={{ display: 'none' }}></a>
      <button 
        id="show-modal-cadastrar-usuario" 
        type="button"
        data-toggle="modal" data-target="#modal-cadastrar-usuario" 
        style={{display:'none'}}
      >
      </button>
      <button 
        id="show-modal-plano-acesso" 
        type="button"
        data-toggle="modal" data-target="#modal-plano-acesso" 
        style={{display:'none'}}
      >
      </button>
      <ModalCadastrarUsuario urlFinalizar={props.urlFinalizar} typeQuiz={props.typeQuiz}/>
      <PlanoAcesso />
  </Container>;
}

export default Quiz;