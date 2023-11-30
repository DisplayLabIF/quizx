import React from 'react';
import ContentEditable from 'react-contenteditable';
import { Container } from './styles';
import BotoesEditor from './BotoesEditor';
import actionsQuiz from '../../../actions/Quiz';
import { useDispatch, useSelector } from 'react-redux';
import apiQuizClass from '../../../services/apiQuizClass';
import isUUID from 'validator/lib/isUUID';
import FileQuestao from './FileQuestao';
import QuestaoTypes from './QuestaoTypes';
import ConfiguracaoQuestao from './ConfiguracaoQuestao';

function Questao() {
  const dispatch = useDispatch();

  const explicacaoTiposRespostas = {
    MULTIPLA_ESCOLHA: (
      <span
        className="ml-2"
        data-toggle="tooltip"
        data-original-title="Adicione suas alternativas e marque as verdadeiras."
      >
        <i className="fas fa-info-circle "></i>
      </span>
    ),
    V_F: (
      <span
        className="ml-2"
        data-toggle="tooltip"
        data-original-title="Adicione suas alternativas e informe quais são V (verdadeiras) e quais são F (falsas). E se quiser trocar os caracteres que sinalizam as alternativas verdadeiras e falsas você também pode!"
      >
        <i className="fas fa-info-circle "></i>
      </span>
    ),
    ABERTA: (
      <span
        className="ml-2"
        data-toggle="tooltip"
        data-original-title="Adicione suas alternativas, quando a correção da questão for feita de forma automática, sera verificado se o texto da resposta contem alguma das alternativas cadastradas."
      >
        <i className="fas fa-info-circle "></i>
      </span>
    ),
    RESPOSTA_VOZ: (
      <span
        className="ml-2"
        data-toggle="tooltip"
        data-original-title="Adicione suas alternativas, quando a correção da questão for feita, sera verificado se o que a pessoa falou contem alguma das alternativas cadastradas."
      >
        <i className="fas fa-info-circle "></i>
      </span>
    ),
    ORDENAR: (
      <span
        className="ml-2"
        data-toggle="tooltip"
        data-original-title="Adicione suas alternativas. Cada alternativa cadastrada irá ser uma frase que precisa ser ordenada."
      >
        <i className="fas fa-info-circle "></i>
      </span>
    ),
  };

  const quiz = useSelector((state) => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];

  async function saveQuestao(active, dataQuestao = null) {
    document.getElementById('box-salvando').style.display = 'block';

    const data = {
      questao: dataQuestao
        ? { ...dataQuestao, active }
        : { ...questao, active },
      quiz_id: quiz.id,
    };

    try {
      let isUuid = isUUID(data.questao.id.toString());

      const response = !isUuid
        ? await apiQuizClass.post('/quizes/questoes', data)
        : await apiQuizClass.put(`/quizes/questoes/${data.questao.id}`, data);

      if (response.data.quiz_id) {
        dispatch(actionsQuiz.setQuizId(response.data.quiz_id));
        dispatch(actionsQuiz.setQuizNome(response.data.quiz_nome));
        if (response.data.id_session) {
          localStorage.setItem('@QuizClassSessionId', response.data.id_session);
        }
      }

      if (!isUuid) {
        dispatch(
          actionsQuiz.setQuizQuestao(data.questao.id, response.data.questao)
        );
      }

      document.getElementById('box-salvando').style.display = 'none';
      if (response.status !== 200) {
        document.getElementById('box-salvando-erro').style.display = 'block';
        setTimeout(function () {
          document.getElementById('box-salvando-erro').style.display = 'none';
        }, 2000);
      }
    } catch (e) {
      console.log(e);
      document.getElementById('box-salvando').style.display = 'none';
      document.getElementById('box-salvando-erro').style.display = 'block';
      setTimeout(function () {
        document.getElementById('box-salvando-erro').style.display = 'none';
      }, 2000);
    }
  }

  const handleTipoResposta = async (e) => {
    const recurso = e.target.value;

    try {

      dispatch(actionsQuiz.setQuizQuestaoTipoResposta(questao.id, recurso));
      
    } catch (e) {
      if (e.response.status === 403) {
        document.getElementById('show-modal-plano-acesso').click();

        console.log(e.response);
      } else {
        console.log(e);
      }
    }
  };

  return (
    <Container>
      <div className="row m-3 p-3">
        <div className="col-12 col-lg-7 col-xl-7">
          <div className="row align-middle">
            <div className="mr-auto ">
              <label>Pergunta</label>
            </div>
            <BotoesEditor
              questaoId={questao.id}
              respostaOuExplicacao={'RESPOSTA'}
              arquivos={
                questao.arquivos_questao.arquivos_resposta &&
                questao.arquivos_questao.arquivos_resposta
              }
            />
          </div>
          <div className="row">
            {questao.arquivos_questao.arquivos_resposta &&
              questao.arquivos_questao.arquivos_resposta[0] &&
              questao.arquivos_questao.arquivos_resposta[0].type !==
                undefined && (
                <div
                  className="col-12 col-lg-3 col-xl-3 text-center pr-lg-3 pr-xl-3"
                  style={{ padding: 0, marginTop: 5 }}
                >
                  {questao.arquivos_questao.arquivos_resposta.map(
                    (arquivo_resposta, index) => (
                      <FileQuestao
                        key={index}
                        respostaOuExplicacao={'RESPOSTA'}
                        arquivo={arquivo_resposta}
                        index={index}
                      ></FileQuestao>
                    )
                  )}
                </div>
              )}
            <div
              className={
                questao.arquivos_questao.arquivos_resposta &&
                questao.arquivos_questao.arquivos_resposta[0] &&
                questao.arquivos_questao.arquivos_resposta[0].type !== undefined
                  ? 'col-12 col-lg-9 col-xl-9'
                  : 'col-12'
              }
              style={{ padding: 0 }}
              onBlur={() => saveQuestao(true)}
            >
              <ContentEditable
                html={questao.pergunta}
                onPaste={(e) => {
                  let text = e.clipboardData.getData('text/plain');
                  dispatch(
                    actionsQuiz.setQuizQuestaoPergunta(questao.id, text)
                  );
                  document.execCommand('insertText', false, text);
                  e.preventDefault();
                }}
                onChange={(e) =>
                  dispatch(
                    actionsQuiz.setQuizQuestaoPergunta(
                      questao.id,
                      e.target.value
                    )
                  )
                }
                id={`pergunta_${questao.id}`}
                disabled={false}
              />
            </div>
          </div>
          <div className="row align-middle pt-3">
            <div className="mr-auto ">
              <label>
                Explicação da resposta
                <span
                  className="ml-2"
                  tabIndex="0"
                  data-toggle="tooltip"
                  title="Caso queria explicar a resposta desta questão, descreva aqui. Se configurado para mostrar a explicação, será mostrado após a resolução da questão."
                >
                  <i className="fas fa-info-circle "></i>
                </span>
              </label>
            </div>
            <BotoesEditor
              questaoId={questao.id}
              respostaOuExplicacao={'EXPLICACAO'}
              arquivos={
                questao.arquivos_questao.arquivos_explicacao &&
                questao.arquivos_questao.arquivos_explicacao
              }
            />
          </div>
          <div className="row" onBlur={() => saveQuestao(true)}>
            {questao.arquivos_questao.arquivos_explicacao &&
              questao.arquivos_questao.arquivos_explicacao[0] &&
              questao.arquivos_questao.arquivos_explicacao[0].type !==
                undefined && (
                <div
                  className="col-12 col-lg-3 col-xl-3 text-center pr-lg-3 pr-xl-3"
                  style={{ padding: 0, marginTop: 5 }}
                >
                  {questao.arquivos_questao.arquivos_explicacao.map(
                    (arquivo_explicacao, index) => (
                      <FileQuestao
                        key={index}
                        respostaOuExplicacao={'EXPLICACAO'}
                        arquivo={arquivo_explicacao}
                        index={index}
                      ></FileQuestao>
                    )
                  )}
                </div>
              )}
            <ContentEditable
              className={
                questao.arquivos_questao.arquivos_explicacao &&
                questao.arquivos_questao.arquivos_explicacao[0] &&
                questao.arquivos_questao.arquivos_explicacao[0].type !==
                  undefined
                  ? 'col-12 col-lg-9 col-xl-9'
                  : 'col-12'
              }
              html={questao.explicacao_resposta}
              onPaste={(e) => {
                let text = e.clipboardData.getData('text/plain');
                dispatch(
                  actionsQuiz.setQuizQuestaoExplicacaoResposta(questao.id, text)
                );
                document.execCommand('insertText', false, text);
                e.preventDefault();
              }}
              onChange={(e) =>
                dispatch(
                  actionsQuiz.setQuizQuestaoExplicacaoResposta(
                    questao.id,
                    e.target.value
                  )
                )
              }
              id={`explicacao-resposta_${questao.id}`}
              disabled={false}
            />
          </div>
        </div>
        <div className="col-lg-5 col-xl-5 col-12 pl-0 pl-lg-4 pl-xl-4">
          <label className="mt-1 mb-2">Tipo de resposta</label>
          {explicacaoTiposRespostas[questao.tipo]}
          <select
            type="text"
            className="form-control form-select form-default mb-3"
            style={{ height: '40px' }}
            onChange={(e) => handleTipoResposta(e)}
            value={questao.tipo}
            onBlur={() => saveQuestao(true)}
          >
            <option value="MULTIPLA_ESCOLHA">Múltipla Escolha</option>
            <option value="V_F">V ou F</option>
          </select>
          <QuestaoTypes save={saveQuestao}></QuestaoTypes>
        </div>
      </div>
      <ConfiguracaoQuestao questao={questao} saveQuestao={saveQuestao} />
    </Container>
  );
}

export default Questao;
