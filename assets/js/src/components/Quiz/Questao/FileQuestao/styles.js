import styled from 'styled-components';

export const Container = styled.div`
  .border{
    border: 1px solid #DADADA;
  }
`;

export const Upload = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  width: 100%;
  position: relative;  

  .excluir{
    position: absolute;
    top: 50%;
    left: -20px;
    transform: translateY(-50%);
    z-index: 2;
    cursor: pointer;
  }

  .label-adicionar{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: #C4C4C4;
    height: 100px;
    width: 100%;
    cursor: pointer;
    padding: 0;
    span{
      padding: 5px;
    }
  }

  .label-alterar{    
    .file {
        display: block;
    }

    .file__overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        color: #ffffff;
        font-family: 'Quicksand', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s;
        cursor: pointer;
        padding: 5px;
    }

    .file__overlay--blur {
        backdrop-filter: blur(5px);
    }

    .file__overlay > * {
        transform: translateY(20px);
        transition: transform 0.25s;
    }

    .file__overlay:hover {
        opacity: 1;
    }

    .file__overlay:hover > * {
        transform: translateY(0);
    }
  }
  /* @media screen and (max-width: 991px) {
    .excluir{
      position: absolute;
      top: -20px;
      right: auto;
      left: 0;
      z-index: 2;
      cursor: pointer;
    }
  } */
`;

export const Video = styled.div`
  .cont-video {
    /* height: calc(100vh - 100px);
    width: 100%; */
    min-height: 100px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  iframe {
    width: 100%;
    height: 100%;
  }
`;


