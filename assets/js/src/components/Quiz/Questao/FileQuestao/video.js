import React, { useEffect } from 'react';
import { Upload, Video, LabelAdicionar } from './styles';
import { PulseLoader } from 'react-spinners';
import { FaTimes } from 'react-icons/fa';
import VimeoPlayer from '@vimeo/player';

function video({upload, uploadFeedback, urlArquivo, questaoId, respostaOuExplicacao, excluir}) {

  useEffect(() => {
    if(uploadFeedback === '' && urlArquivo && urlArquivo !== undefined){
      const video = {
        url: urlArquivo,
        controls: false,
        muted: true
      };
      var videoPlayer = new VimeoPlayer(`video-preview_${respostaOuExplicacao}_${questaoId}`, video);
      videoPlayer.setVolume(0);
    }
  }, [urlArquivo, uploadFeedback]);

  return (
    <>
    <input
      type="file"
      accept="video/*"
      id={`video-upload-questao_${respostaOuExplicacao}_${questaoId}`}
      style={{ display: 'none' }}
      onChange={e =>
        upload(e)
      }
    />
    {uploadFeedback === '' && urlArquivo && urlArquivo !== undefined ?
      <Upload className="border border-radius">
          <div className="excluir" onClick={excluir} title="Excluir arquivo">
            <FaTimes size={13} />
          </div>
          <label
            className="label-alterar"
            htmlFor={`video-upload-questao_${respostaOuExplicacao}_${questaoId}`}
          >
            <Video>
              <div className="cont-video" id={`video-preview_${respostaOuExplicacao}_${questaoId}`}></div>
            </Video> 
            <div className="file__overlay border-radius text-center">
              <i className="fas fa-file-video"></i> Alterar vídeo
            </div>
          </label>
      </Upload>
     
    :
      <Upload>
        <div className="excluir" onClick={excluir} title="Excluir arquivo">
          <FaTimes size={13} />
        </div>
        <label 
          htmlFor={`video-upload-questao_${respostaOuExplicacao}_${questaoId}`}
          className="label-adicionar border-radius" 
        >
          {uploadFeedback === '' ?
            <span><i className="fas fa-file-video"></i> Adicionar um vídeo</span>
            : uploadFeedback === 'INICIANDO_UPLOAD' ?
              <PulseLoader size={2} margin={2} color="#333" />
            : uploadFeedback === 'ENVIANDO' ?
              <div style={{width: 100+'%'}}>
                <div className="progress" style={{height: 5, width: '100%'}}>
                  <div id={`video-upload-questao-progress-bar_${respostaOuExplicacao}_${questaoId}`} className="progress-bar bg-warning" role="progressbar" style={{width: '0%'}} aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p id={`video-upload-questao-progress-percent_${respostaOuExplicacao}_${questaoId}`}>0%</p>
              </div>
            : uploadFeedback
          }
        </label>
        </Upload>

    }
    </>
  );
}

export default video;