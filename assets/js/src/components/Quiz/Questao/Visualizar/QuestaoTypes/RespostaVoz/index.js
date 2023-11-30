import React, { useState, useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { Feedback, Wrapper } from './styles';
import Microfone from './Microfone';
import apiQuizClass from '../../../../../../services/apiQuizClass';
import actionsVisualizar from '../../../../../../actions/Visualizar';


function RespostaVoz({ questaoId }) {
  const dispatch = useDispatch();
  const [showFeedBack, setShowFeedback] = useState(false);
  const [textFeedBack, setTextFeedback] = useState('');
  const [qtdErros, setQtdErros] = useState(1);

  function recording() {
    setTextFeedback('Ouvindo...');
    setShowFeedback(true);
  }

  function stopRecording() {
    setTextFeedback('Tente novamente');
  }

  useEffect(()=>{
    if (qtdErros > 2) {
      setQtdErros(0);
      document.getElementById("continuar-proxima-questao").click();
    }
  },[qtdErros]);

  async function onResult(audioData, typeRecording, blobUrl) {
    setTextFeedback('');

    const bodyParameters = {
      questao_id: questaoId,
      data: audioData,
      type_recording: typeRecording
    };
    const result = (
      await apiQuizClass.post('/audio/recognition', bodyParameters)
    ).data;
    // setShowFeedback(false);
    const percentualAcerto = result.percentualAcerto;
    if (result.acertou) {
      const message = (
        <div className="p-2" style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <span>
            Você acertou <b>{percentualAcerto}%</b>
          </span>
          <span
              style={{ fontSize: 13, fontWeight: 'normal' }}
              dangerouslySetInnerHTML={{
                __html: 'O que foi dito: ' + result.fraseCompleta
              }}
          ></span>
          {result.respostaFormatada && (
            <span
              style={{ fontSize: 13, color: '#4a4a4a', fontWeight: 'normal' }}
              dangerouslySetInnerHTML={{
                __html: 'Resposta correta: ' + result.respostaFormatada
              }}
            ></span>
          )}          
        </div>
      );
      setTextFeedback(message);
      setShowFeedback(true);
      dispatch(actionsVisualizar.visualizarSetResultadoRespostaVoz(true));
    } else {
      const message = (
        <div className="p-2" style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <span>Tente novamente</span>
          {result.fraseCompleta && (
            <span
              style={{ fontSize: 13, fontWeight: 'normal' }}
              dangerouslySetInnerHTML={{
                __html: 'O que foi dito: ' + result.fraseCompleta
              }}
            ></span>
          )}
          {result.message && (
            <span
              style={{ fontSize: 13, fontWeight: 'normal' }}
              dangerouslySetInnerHTML={{
                __html: result.message
              }}
            ></span>
          )}
        </div>
      );
      setTextFeedback(message);
      setShowFeedback(true);
      dispatch(actionsVisualizar.visualizarSetResultadoRespostaVoz(false));
      setQtdErros(v => v + 1);
    }

    document.getElementById("verificar-resposta-quiz").click();
  }

  return (
      <Wrapper>
        <div id="microfone-wrap">
          <Microfone
            onRecording={recording}
            onStop={stopRecording}
            onResult={onResult}
            setShowFeedback={setShowFeedback}
          />
          
          <Feedback className="border-radius" show={showFeedBack}>
            {textFeedBack}
          </Feedback>
          
        </div>
      </Wrapper>
  );
}

export default RespostaVoz;
