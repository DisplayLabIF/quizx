import React, { useState } from 'react';
import apiQuizClass from './../../../../services/apiQuizClass'
import apiVimeo from './../../../../services/apiVimeo'
import { ScaleLoader } from 'react-spinners';
import Image from './image';
import Video from './video';
import Audio from './audio';
import { useDispatch, useSelector } from 'react-redux';
import actionsQuiz from '../../../../actions/Quiz';
import { Container } from './styles';

function fileQuestao({respostaOuExplicacao, arquivo, index}) {
  const dispatch = useDispatch();

  const quiz = useSelector(state => state.QuizReducer);
  const questao = quiz.questoes[quiz.questaoSelected];

  const [uploadFeedback, setUploadFeedback] = useState('');

  async function upload(e) {
    document.getElementById('box-salvando').style.display='block';
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('quiz_id', quiz.id);
    formData.append('questao_id', questao.id);
    formData.append('numero_questao', questao.numero_questao);
    formData.append('resposta_ou_explicacao', respostaOuExplicacao);
    formData.append('tipo_arquivo', arquivo.type);
    formData.append('index_arquivo', index);


    setUploadFeedback(
        <ScaleLoader height={10} width={17} margin={0} color="#333" />
    );
    try {
        const response = await apiQuizClass.post('/upload', formData);
        dispatch(actionsQuiz.setQuizQuestaoUrlArquivo(questao.id, response.data.url, respostaOuExplicacao, index));
        dispatch(actionsQuiz.setQuizQuestaoProvider(questao.id, 'S3', respostaOuExplicacao, index));
        dispatch(actionsQuiz.setQuizId(response.data.quiz_id));
        dispatch(actionsQuiz.setQuizQuestaoId(questao.id, response.data.questao_id));
        setUploadFeedback('');
        document.getElementById('box-salvando').style.display='none';
    } catch (e) {
        setUploadFeedback('Erro no upload!');
        document.getElementById('box-salvando').style.display='none';
        setTimeout(function() {
          setUploadFeedback('');
        }, 2000);
    }
  }
  
  async function uploadVideo(e){

    const file = e.target.files[0];
    setUploadFeedback('INICIANDO_UPLOAD');
    try {
      const upload= {
        upload:{
            approach: 'tus',
            size: file.size
        },
        name: file.name, 
      };
      const response = await apiVimeo.post('/me/videos', JSON.stringify(upload));

      try{
        setUploadFeedback('ENVIANDO');
        var uploadEndPoint = response.data.upload.upload_link;

        const uploader = new tus.Upload(file, {
            uploadUrl: uploadEndPoint,
            retryDelays: [0, 1000, 3000, 5000],
            uploadSize: file.size,
            onError: function(error) {
              console.log("Failed because: " + error);
              setUploadFeedback('Erro no upload!');
              setTimeout(function() {
                setUploadFeedback('');
              }, 2000);
            },
            onProgress: function(bytesUploaded, bytesTotal) {
                var percentComplete = ((bytesUploaded / bytesTotal) * 100);
                document.getElementById(`video-upload-questao-progress-percent_${respostaOuExplicacao}_${questao.id}`).innerHTML=percentComplete.toFixed(2) + '%';
                document.getElementById(`video-upload-questao-progress-bar_${respostaOuExplicacao}_${questao.id}`).style.width=percentComplete + '%';
            },
            onSuccess: function() {
              dispatch(actionsQuiz.setQuizQuestaoUrlArquivo(questao.id, response.data.link, respostaOuExplicacao, index));
              dispatch(actionsQuiz.setQuizQuestaoProvider(questao.id, 'VIMEO', respostaOuExplicacao, index));
              setUploadFeedback('');
            }
        })
        uploader.start();
      } catch(e){
        setUploadFeedback('Erro no upload!');
        setTimeout(function() {
          setUploadFeedback('');
        }, 2000);
      }
    } catch (e) {
      setUploadFeedback('Erro no upload!');
      setTimeout(function() {
        setUploadFeedback('');
      }, 2000);
    }
  }

  async function excluir(){
    document.getElementById('box-salvando').style.display='block';
    try {
      var arquivosQuestao = questao.arquivos_questao;
      if(respostaOuExplicacao === "RESPOSTA"){
        arquivosQuestao.arquivos_resposta.splice(index, 1);
      }else if(respostaOuExplicacao === "EXPLICACAO"){
        arquivosQuestao.arquivos_explicacao.splice(index, 1);
      }
      dispatch(actionsQuiz.setQuizArquivosQuestao(questao.id, arquivosQuestao));

      const data ={
        questao:{
          ...questao,
          active: true,
          arquivos_questao: arquivosQuestao
        },
        quiz_id: quiz.id
      };

      await apiQuizClass.put(`/quizes/questoes/${data.questao.id}`, data);
      dispatch(actionsQuiz.setQuizArquivosQuestao(questao.id, arquivosQuestao));

      document.getElementById('box-salvando').style.display='none';
    } catch (e) {
      document.getElementById('box-salvando').style.display='none';
    }  
  }

  const file_types = {
    image: (
      <Image 
        upload={upload} 
        urlArquivo={arquivo.url} 
        uploadFeedback={uploadFeedback} 
        questaoId={questao.id} 
        respostaOuExplicacao={respostaOuExplicacao}
        excluir={excluir}
      />
    ),
    video: (
      <Video 
        upload={uploadVideo} 
        urlArquivo={arquivo.url}
        uploadFeedback={uploadFeedback} 
        questaoId={questao.id} 
        respostaOuExplicacao={respostaOuExplicacao}
        excluir={excluir}
      />
    ),
    audio: (
      <Audio 
        upload={upload} 
        urlArquivo={arquivo.url} 
        uploadFeedback={uploadFeedback} 
        questaoId={questao.id} 
        respostaOuExplicacao={respostaOuExplicacao}
        excluir={excluir}
      />
    ),
  };

  if(file_types[arquivo.type]){
    return (
      <Container className="mb-3">{file_types[arquivo.type]}</Container>
    );
  }

  return '';
}

export default fileQuestao;