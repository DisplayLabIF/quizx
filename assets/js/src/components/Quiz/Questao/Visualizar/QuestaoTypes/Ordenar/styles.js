import styled, { css } from 'styled-components';

export const Container = styled.div`
  width: 100%;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
`;

export const Options = styled.div`
  display: flex;
  width: 100%;
  flex-direction: column;
  flex-wrap: wrap;
  input[type='radio'] {
    display: none;
  }
  span.numero {
    margin: 0;
    margin-bottom: 10px;
    font-size: 16px;
    font-weight: 700;
  }
  .frase {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }
`;

export const Option = styled.div`
  display: flex;
  justify-content: space-around;
  align-items: center;
  background: #ffe132;
  border-radius: 4px;
  line-height: 22px;
  font-size: 13px;
  padding: 8px 38px;
  cursor: move;
  margin-right: 20px;
  margin-bottom: 5px;
  border: 2px solid #cacaca;
  span {
    font-size: 20px;
    margin: 0;
    width: 100%;
    text-align: center;
    color: #4a4a4a;
    font-weight: 700;
  }

  ${props =>
    props.isDragging &&
    css`
      border: 2px dashed rgba(0, 0, 0, 0.2);
      background: transparent;
      box-shadow: none;
      cursor: grabbing;
      span {
        opacity: 0;
      }
    `};
`;

export const Wrapper = styled.div`
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
`;
