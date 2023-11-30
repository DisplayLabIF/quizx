import React, { useRef } from 'react';
import { useDrag, useDrop } from 'react-dnd';

import { Option } from '../styles';

function Palavra({ palavra, index, indexFrase, moveItem }) {
  const ref = useRef();

  const [{ isDragging }, dragRef] = useDrag({
    item: { type: 'palavra', index, palavra },
    collect: monitor => ({
      isDragging: monitor.isDragging()
    })
  });

  const [, dropRef] = useDrop({
    accept: 'palavra',
    hover(item, monitor) {
      const draggedIndex = item.index;
      const targetIndex = index;

      if (draggedIndex === targetIndex) return;

      const targetSize = ref.current.getBoundingClientRect();
      const targetCenter = (targetSize.right - targetSize.left) / 2;

      const draggedOffset = monitor.getClientOffset();
      const draggedRight = targetSize.right - draggedOffset.x;

      if (draggedIndex > targetIndex && draggedRight < targetCenter) {
        return;
      }

      if (draggedIndex < targetIndex && draggedRight > targetCenter) {
        return;
      }

      moveItem(draggedIndex, targetIndex, indexFrase);

      item.index = targetIndex;
    },
    drop: palavraDragged => {
      const draggedIndex = palavraDragged.index;
      const targetIndex = index;
      if (draggedIndex !== targetIndex)
        moveItem(draggedIndex, targetIndex, indexFrase);
    }
  });

  dragRef(dropRef(ref));

  return (
    <Option ref={ref} isDragging={isDragging}>
      <span>{palavra}</span>
    </Option>
  );
}

export default Palavra;
