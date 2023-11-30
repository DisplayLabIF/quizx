import React from 'react';
import { useSelector } from 'react-redux';

function CadastrarUsuario(props) {
  const quiz = useSelector(state => state.QuizReducer);

  return (
    <div className="modal fade" id="modal-cadastrar-usuario" tabIndex="-1" role="dialog" aria-labelledby="modalCadastrarUsuarioTitle" aria-hidden="true">
      <div className="modal-dialog modal-dialog-centered" role="document">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="exampleModalLongTitle">Cadastro</h5>
            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div className="modal-body">
            <h5 className="mt-2" style={{ textAlign: 'center' }}>
              Para finalizar o quiz você precisa efetuar o cadastro!
            </h5>
            <form className="mt-3" action="/app/criar-quiz/cadastrar">
              <input type="hidden" name="quiz_id" defaultValue={quiz.id} />
              <input type="hidden" name="quiz_type" defaultValue={props.typeQuiz ? props.typeQuiz : ''} />
              <input 
                name="nome"
                type="text"
                className="form-control form-default mb-2"
                placeholder="Seu nome"
                required
              /> 
              <input 
                name="email"
                type="email"
                className="form-control form-default mb-2"
                placeholder="Seu E-mail"
                required
              /> 
              <input 
                name="senha"
                type="password"
                className="form-control form-default"
                placeholder="Sua senha"
                required
              /> 
              <button
                className="btn btn-default btn-block border-radius font-weight-bold mt-2 mb-3"
                style={{ marginRight: 5 }}
                type="submit"
              >
                Concluir
              </button>
            </form>
          </div>
          <div className="modal-footer">
            <div className="text-center" style={{width: '100%'}}>
              {/* <a  
                className="btn btn-primary m-2" 
                href={`/connect/professor/facebook?redirect=app_quiz_finalizar&quiz_id=${quiz.id}${props.typeQuiz && '&type='+props.typeQuiz}`}
              >
                <i className="fab fa-facebook-square"></i> Entrar com o Facebook
              </a> */}
              <a  
                className="btn btn-danger m-2" 
                href={`/connect/professor/google?redirect=app_quiz_finalizar&quiz_id=${quiz.id}${props.typeQuiz && '&type='+props.typeQuiz}`}
              >
                <i className="fab fa-google"></i> Entrar com o Gmail
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default CadastrarUsuario;
