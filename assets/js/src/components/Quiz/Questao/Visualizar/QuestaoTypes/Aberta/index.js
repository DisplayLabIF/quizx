import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import actionsVisualizar from '../../../../../../actions/Visualizar';

function Aberta() {
  const dispatch = useDispatch();
  const respostaAberta = useSelector(state => state.VisualizarReducer.respostaAberta);

  return (
    <textarea  
      className="form-control mb-3" 
      name="resposta" 
      value={respostaAberta}
      placeholder={'Escreva aqui sua resposta'}
      onChange={e => dispatch(actionsVisualizar.visualizarSetRespostaAberta(e.target.value))}
    />
  );
}

export default Aberta;
