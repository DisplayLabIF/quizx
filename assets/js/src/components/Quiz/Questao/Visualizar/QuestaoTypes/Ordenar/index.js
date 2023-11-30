import React, { useCallback } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import actionsVisualizar from '../../../../../../actions/Visualizar';
import DragAndDrop from "../../../../../DragAndDrop";
import { Container, Options, Wrapper } from './styles';
import PalavraComponent from './Palavra';
import produce from 'immer';

function Ordenar() {
  const dispatch = useDispatch();

  const ordenarFrases = useSelector(state => state.VisualizarReducer.ordenarFrases);

  const moveItem = useCallback(
    (from, to, indexFrase) => {
      dispatch(actionsVisualizar.visualizarSetOrdenarFrases(
        produce(ordenarFrases, draft => {
          const dragged = draft[indexFrase][from];
          draft[indexFrase].splice(from, 1);
          draft[indexFrase].splice(to, 0, dragged);
        })
      ));
    },
    [ordenarFrases]
  );

  return (
    <Container>
      <Wrapper id="wrapper">
        <Options>
          {ordenarFrases.map((palavras, indexFrase) => {
            return (
              <DragAndDrop key={indexFrase}>
                <div>
                  {ordenarFrases.length > 1 && (
                    <span className="numero">{indexFrase + 1}.</span>
                  )}
                  <div className="frase">
                    {palavras &&
                      palavras.map((palavra, index) => {
                        return (
                          <PalavraComponent
                            key={index}
                            palavra={palavra}
                            moveItem={moveItem}
                            index={index}
                            indexFrase={indexFrase}
                          />
                        );
                    })}
                  </div>
                </div>
              </DragAndDrop>
            );
          })}
        </Options>
      </Wrapper>
    </Container>
  );
}

export default Ordenar;
