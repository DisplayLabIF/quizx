import React from 'react';
import { Imagem } from '../styles';
function image({url}) {

  return (
    <Imagem>
      <img
        src={url}
        className="img-fluid border-radius m-0"
        draggable="false"
        alt='imagem questão'
        style={{
          display: (!url || url === undefined || url === '') && 'none'
        }}
      />
    </Imagem>
  );
}

export default image;