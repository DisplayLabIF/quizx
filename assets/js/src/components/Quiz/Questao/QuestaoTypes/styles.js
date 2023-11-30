import styled from 'styled-components';

export const Wrapper = styled.div`
  display: flex;
  flex-direction: row;
  /* justify-content: space-between; */
  align-items: center;
  width: 100%;
  height: 52px;
  padding: 0 15px;
  width: 100%;
  input{
    border: 1px solid #DADADA;
    box-sizing: border-box;
    border-radius: 4px;
    box-shadow: none;
  }
  .true-input,
  .false-input{
    height: 18px;
    width: 18px;
    font-weight: bold;
    border: 1px solid #DADADA;
    box-shadow: none;
    outline: none;
  }
  .true-input{
    color: green;
  }
  .false-input{
    color: red;
  }
  .true-input:focus,
  .false-input:focus{
    border: 1px solid var(--primary);
  }
`;

export const WrapperImageAudio = styled.div`
  display: ${props => (props.hasAsset ? 'flex' : 'none')};
  flex-direction: column;
  width: 50%;
  height: 100%;
  padding-bottom: 20px;
  justify-content: center;
  align-items: center;
`;

export const ImageView = styled.img`
  max-width: 380px;
  max-height: 380px;
  opacity: 0;
  position: absolute;
  top: 0;
  left: 0;
`;
export const AudioWrapper = styled.div`
  width: 100%;
  display: flex;
  align-items: flex-end;
  justify-content: center;
`;
