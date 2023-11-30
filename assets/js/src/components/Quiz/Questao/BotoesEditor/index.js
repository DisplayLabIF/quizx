import React from 'react';
import { useDispatch } from 'react-redux';
import actionsQuiz from '../../../../actions/Quiz';

import { Container } from './styles';

function BotoesEditor({questaoId, respostaOuExplicacao, arquivos}) {
  const dispatch = useDispatch();

  function execCommand(cmd, args = null) {
    document.execCommand(cmd, false, args);
  }
  return <Container className="bg-gray border-radius p-2">
  <button
    type="button"
    className="btn btn-sm"
    onClick={(e) => execCommand("bold")}
    onMouseDown={(e) => e.preventDefault()}
  >B</button>
  <button 
    type="button" 
    className="btn btn-sm ml-1"
    onClick={(e) => execCommand("italic")}
    onMouseDown={(e) => e.preventDefault()}
  ><i>I</i></button>
  <button 
    type="button" 
    className="btn btn-sm ml-1"
    onClick={(e) => execCommand("underline")}
    onMouseDown={(e) => e.preventDefault()}
  ><u>S</u></button>
  <button  
    type="button" 
    className="btn btn-sm ml-1"
    onClick={(e) =>{
        dispatch(actionsQuiz.setQuizQuestaoTipoArquivo(
          questaoId,
          'image',
          respostaOuExplicacao
        ))
    }}
    disabled={
      arquivos && arquivos[0] ?
        arquivos.length >=2 ? true : 
        arquivos[0].type === 'image' ? true : false
      :
      false
    }
    // onMouseDown={(e) => e.preventDefault()}
  ><i className="fas fa-image"></i></button>
  <button 
    type="button" 
    className="btn btn-sm ml-1"
    onClick={(e) =>{
      dispatch(actionsQuiz.setQuizQuestaoTipoArquivo(
        questaoId,
        'video',
        respostaOuExplicacao
      ))
    }}
    disabled={
      arquivos && arquivos[0] ?
        arquivos.length >=2 ? true : 
        arquivos[0].type === 'video' ? true : false
      :
      false
    }
    // onMouseDown={(e) => e.preventDefault()}
  ><i className="fas fa-video"></i></button>
  <button 
    type="button" 
    className="btn btn-sm ml-1"
    onClick={(e) =>{
      dispatch(actionsQuiz.setQuizQuestaoTipoArquivo(
        questaoId,
        'audio',
        respostaOuExplicacao
      ))
    }}
    disabled={
      arquivos && arquivos[0] ?
        arquivos.length >=2 ? true : 
        arquivos[0].type === 'audio' ? true : false
      :
      false
    }
    // onMouseDown={(e) => e.preventDefault()}
  ><i className="fas fa-volume-up"></i></button>
</Container>;
}

export default BotoesEditor;