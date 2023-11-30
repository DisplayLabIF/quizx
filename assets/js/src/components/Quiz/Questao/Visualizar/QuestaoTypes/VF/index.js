import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { TrueOrFalse } from '../styles';
import { FaTimes, FaCheck } from 'react-icons/fa';
import actionsVisualizar from '../../../../../../actions/Visualizar';

function VF({ opcoes, trueOrFalseCaracteres }) {
  const dispatch = useDispatch();

  const visualizar = useSelector(state => state.VisualizarReducer);
  const trueOrFalseSelected = visualizar.trueOrFalseSelected;
  const verificarResposta = visualizar.verificarResposta;

  function setSelected(index, trueOrFalse){
    let trueOrFalseSelectedAux = [...trueOrFalseSelected];
    if(trueOrFalse){
      if(trueOrFalseSelectedAux[index].selectedTrue){
        trueOrFalseSelectedAux[index].selectedTrue=false;
      }else{
        if(trueOrFalseSelectedAux[index].selectedFalse){
          trueOrFalseSelectedAux[index].selectedFalse=false;
        }
        trueOrFalseSelectedAux[index].selectedTrue=true;
      }
    }else{
      if(trueOrFalseSelectedAux[index].selectedFalse){
        trueOrFalseSelectedAux[index].selectedFalse=false;
      }else{
        if(trueOrFalseSelectedAux[index].selectedTrue){
          trueOrFalseSelectedAux[index].selectedTrue=false;
        }
        trueOrFalseSelectedAux[index].selectedFalse=true;
      }
    }
    dispatch(actionsVisualizar.visualizarSetTrueOrFalseSelected(trueOrFalseSelectedAux));
  }

  function verificar(index){
    if(trueOrFalseSelected[index] && trueOrFalseSelected[index].resposta_correta){
      if(trueOrFalseSelected[index].selectedTrue){
        return <FaCheck color="green"/>;
      }else{
        return <FaTimes color="red"/>;
      }
    }else{
      if(trueOrFalseSelected[index] && trueOrFalseSelected[index].selectedFalse){
        return <FaCheck color="green"/>;
      }else{
        return <FaTimes color="red"/>;
      }
    }      
  }

  return (
    <>
    {opcoes.map((opcao, index) => (
      <div 
        key={index} 
        className="d-flex align-items-center flex-row mb-3" 
      >
        <TrueOrFalse 
          trueOrFalse={true} 
          selected={trueOrFalseSelected[index] ? trueOrFalseSelected[index].selectedTrue : false}
          onClick={()=>setSelected(index, true)}
        >
          {trueOrFalseCaracteres[0]}
        </TrueOrFalse>
        <TrueOrFalse 
          trueOrFalse={false} 
          selected={trueOrFalseSelected[index] ? trueOrFalseSelected[index].selectedFalse : false}
          onClick={()=>setSelected(index, false)}
        >
          {trueOrFalseCaracteres[1]}
        </TrueOrFalse>
        { opcao.imagem !== undefined && opcao.imagem ? 
            <div className="imagem-opcao ml-3"> 
              <img
                style={{
                  maxHeight: '100%',
                  maxWidth: '100%'
                }}
                className="img-fluid"
                src={opcao.imagem}
              />
            </div>
          :
          <span>{opcao.texto}</span>
        }
        {verificarResposta && 
          verificar(index)
        }
      </div>
    ))}
    </>
  );
}

export default VF;
