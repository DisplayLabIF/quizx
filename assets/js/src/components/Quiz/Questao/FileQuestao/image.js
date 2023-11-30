import React from 'react';
import { Upload, LabelAdicionar } from './styles';
import { FaTimes } from 'react-icons/fa';
// import { Container } from './styles';

function image({upload, uploadFeedback, urlArquivo, questaoId, respostaOuExplicacao, excluir}) {

  return (
    <>
      <input
        type="file"
        accept="image/*"
        id={`image-upload-questao_${respostaOuExplicacao}_${questaoId}`}
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
              htmlFor={`image-upload-questao_${respostaOuExplicacao}_${questaoId}`}
            >
              <img
                className="file img-fluid mt-2 mb-2"
                src={urlArquivo}
              />
              <div className="file__overlay border-radius text-center">
                  <i className="fas fa-image"></i> Alterar imagem
              </div>
            </label>
        </Upload>
        
      :
        <Upload>
          <div className="excluir" onClick={excluir} title="Excluir arquivo">
            <FaTimes size={13} />
          </div>
          <label 
            htmlFor={`image-upload-questao_${respostaOuExplicacao}_${questaoId}`}
            className="label-adicionar border-radius"
          >
            {uploadFeedback === '' ?
              <span><i className="fas fa-image"></i> Adicionar uma imagem</span>
              : uploadFeedback
            }
          </label>
        </Upload>
      }
    </>
  );
}

export default image;