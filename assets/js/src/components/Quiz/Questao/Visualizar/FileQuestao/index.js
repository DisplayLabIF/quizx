import React from 'react';
import { useSelector } from 'react-redux';
import Image from './Imagem';
import Video from './Video';
import Audio from './Audio';

export default function FileQuestao({ respostaOuExplicacao, arquivo, index }) {
    const quiz = useSelector(state => state.QuizReducer);
    const questao = quiz.questoes[quiz.questaoSelected];

    const file_types = {
        image: (
          <Image 
            url={arquivo.url} 
          />
        ),
        video: (
          <Video 
            url={arquivo.url}
            questaoId={questao.id}
            respostaOuExplicacao={respostaOuExplicacao}
          />
        ),
        audio: (
          <Audio 
            url={arquivo.url} 
            questaoId={questao.id}
          />
        ),
      };

      if(file_types[arquivo.type]){
        return (
          <div className="mb-2">{file_types[arquivo.type]}</div>
        );
      }

    return '';
}
