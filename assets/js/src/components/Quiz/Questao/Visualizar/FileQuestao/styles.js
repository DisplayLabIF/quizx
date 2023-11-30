import styled from "styled-components";

export const Video = styled.div`
  .cont-video {
    /* height: calc(100vh - 100px);
    width: 100%; */
  }
  iframe {
    max-width: 100%;
    max-height: 500px;
    border-radius: 4px;
  }
  @media only screen and (max-width: 991px){
    .cont-video{
      text-align: center;
    }
  }
`;

export const Player = styled.div`
  display: flex;
  width: 100%;
  height: 100%;
  cursor: pointer;
  justify-content: center;
  padding: 5px;
  color: #000;
`;

export const Imagem = styled.div`
  @media only screen and (max-width: 991px){
    text-align: center;
  }
`;

export const Wrapper = styled.div`
  .audio-player{
    width: 100%;
    background: #ffe132;
    margin-top: 10px;
  }
`;