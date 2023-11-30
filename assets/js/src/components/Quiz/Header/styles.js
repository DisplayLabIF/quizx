import styled from 'styled-components';

export const Container = styled.div`
    .remover-imagem{
        cursor: pointer;
        margin-right: -40px;
        padding-left: 5px;
    }
`;

export const ImageUpload = styled.label`
  
  position: relative;  
  .image__img {
      display: block;
  }

  .image__overlay {
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
  }

  .image__overlay--blur {
      backdrop-filter: blur(5px);
  }

  .image__overlay > * {
      transform: translateY(20px);
      transition: transform 0.25s;
  }

  .image__overlay:hover {
      opacity: 1;
  }

  .image__overlay:hover > * {
      transform: translateY(0);
  }
`;


export const ImagePreview = styled.img`
  max-height: 45px;
`;

