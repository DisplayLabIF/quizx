import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { Opcao } from '../styles';
import actionsVisualizar from '../../../../../../actions/Visualizar';

function MultiplaEscolha({ opcoes }) {
  const dispatch = useDispatch();
  const multiplaEscolhaSelected = useSelector(state => state.VisualizarReducer.multiplaEscolhaSelected);

  function setOpcaoSelected(opcaoId){
    if(Array.isArray(multiplaEscolhaSelected)){
      let multiplaEscolhaSelectedAux = [...multiplaEscolhaSelected];

      if(multiplaEscolhaSelectedAux.includes(opcaoId)){
        let index = multiplaEscolhaSelectedAux.indexOf(opcaoId);
        multiplaEscolhaSelectedAux.splice(index, 1);
      }else{
        multiplaEscolhaSelectedAux = [
          ...multiplaEscolhaSelectedAux,
          opcaoId
        ];
      }
      
      
      dispatch(actionsVisualizar.visualizarSetMultiplaEscolhaSelected(multiplaEscolhaSelectedAux));
    }else{
      if(multiplaEscolhaSelected === opcaoId){
        dispatch(actionsVisualizar.visualizarSetMultiplaEscolhaSelected(''));
      }else{
        dispatch(actionsVisualizar.visualizarSetMultiplaEscolhaSelected(opcaoId));
      }
    }
  }
  function clicked(opcaoID){
    if(Array.isArray(multiplaEscolhaSelected)){
      if(multiplaEscolhaSelected.includes(opcaoID)){
        return true;
      }else{
        return false;
      }
    }else if(multiplaEscolhaSelected === opcaoID){
      return true;
    }else{
      return false;
    }
  }
  return (
    <>
    {opcoes.map((opcao, index) => (
      <div key={index}>
        <Opcao
          clicked={clicked(opcao.id)}
          onClick={()=> setOpcaoSelected(opcao.id)}
        >
          { opcao.imagem !== undefined && opcao.imagem ? 
              <div className="texto">
                  <div className="index">{((index+1) + 9).toString(36).toUpperCase()}{!clicked(opcao.id) && '.'}</div>
                  <img
                    style={{
                      maxHeight: '100%',
                      maxWidth: '100%'
                    }}
                    className="img-fluid not-select"
                    src={opcao.imagem}
                  />
              </div>
              
          :
            <div className="texto">
              <div className="index">{((index+1) + 9).toString(36).toUpperCase()}{!clicked(opcao.id) && '.'}</div> {opcao.texto}
            </div>
          }
        </Opcao>
      </div>
    ))}
    </>
  );
}

export default MultiplaEscolha;
