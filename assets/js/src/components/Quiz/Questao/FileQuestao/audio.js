import React from 'react';
import { Upload, LabelAdicionar } from './styles';
import { FaTimes } from 'react-icons/fa';
// import { Container } from './styles';

function audio({upload, uploadFeedback, urlArquivo, questaoId, respostaOuExplicacao, excluir}) {

  return (
    <>
      <input
        type="file"
        accept="audio/*"
        id={`audio-upload-questao_${respostaOuExplicacao}_${questaoId}`}
        style={{ display: 'none' }}
        onChange={e =>
          upload(e)
        }
      />
      { uploadFeedback === '' && urlArquivo && urlArquivo !== undefined ?
        <Upload className="border border-radius">
            <div className="excluir" onClick={excluir} title="Excluir arquivo">
              <FaTimes size={13} />
            </div>
            <label
               className="label-alterar" 
              htmlFor={`audio-upload-questao_${respostaOuExplicacao}_${questaoId}`}
            >
              <div className="d-flex justify-content-center align-items-center" style={{width:100, height: 100}}>
                <i className="fas fa-volume-up fa-3x"></i>
              </div>
              
              <div className="file__overlay border-radius text-center">
                <i className="fas fa-file-audio"></i> Alterar áudio
              </div>
            </label>
        </Upload>
        
      :
        <Upload>
          <div className="excluir" onClick={excluir} title="Excluir arquivo">
            <FaTimes size={13} />
          </div>
          <label 
            htmlFor={`audio-upload-questao_${respostaOuExplicacao}_${questaoId}`}
            className="label-adicionar border-radius" 
          >
            {uploadFeedback === '' ?
              <span><i className="fas fa-file-audio"></i> Adicionar um áudio</span>
              : uploadFeedback
            }
          </label>
        </Upload>
      }
    </>
  );
}

export default audio;