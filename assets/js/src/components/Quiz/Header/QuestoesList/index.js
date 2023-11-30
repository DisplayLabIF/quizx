import React, { useRef } from 'react';
import { useDispatch } from 'react-redux';
import { useDrag, useDrop } from 'react-dnd';
import actionsQuiz from '../../../../actions/Quiz';
import { Container } from './styles';

function QuestoesList({ questao, index, questaoSelected, moveItem }) {
  const dispatch = useDispatch();

  const ref = useRef();

  const [{ isDragging }, dragRef] = useDrag({
      item: { type: 'questao', index, ...questao },
      collect: monitor => ({
        isDragging: monitor.isDragging()
      })
  });

  const [, dropRef] = useDrop({
      accept: 'questao',
      drop: itemDragged => {
          const draggedIndex = itemDragged.index;
          const targetIndex = index;

          if (draggedIndex !== targetIndex){
            moveItem(draggedIndex, targetIndex);
          }
      }
  });

  dragRef(dropRef(ref));

  return (
    <Container ref={ref} isDragging={isDragging}>
      <button 
          type="button" 
          className={`btn ${questaoSelected === index ? 'btn-default text-dark' : 'btn-secondary' } m-1`}
          onClick={()=>dispatch(actionsQuiz.setQuizQuestaoSelected(index))}
      >
          {index+1}
      </button>
    </Container>
    );
}

export default QuestoesList;