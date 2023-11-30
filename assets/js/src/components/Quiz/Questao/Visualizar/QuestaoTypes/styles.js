import styled from 'styled-components';

export const Opcao = styled.div`
  display: flex;
  width: 100%;
  /* justify-content: space-around; */
  align-items: center;
  margin-bottom: 20px;
  background: ${props => (props.clicked ? 'rgba(255, 225, 50, 0.6)' : '#fff')};
  border-radius: 4px;
  border: 1px solid #DDDDDD;
  min-height: 70px;
  line-height: 22px;
  font-size: 15px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  .texto{
    display: flex;
    flex-direction: row;
    align-items: center;
    height: 100%;
    font-size: 18px;
    margin: 0;
    margin-left: 20px;
    width: 100%;
    font-weight: ${props => (props.clicked ? 'bold' : 'normal')};
    color: #4A4A4A;
    padding: 10px 20px 10px 0;
    .index{
      min-width: 25px;
      min-height: 25px;
      line-height: 25px;
      text-align: center;
      margin-right: 5px;
      font-size: ${props => (props.clicked && '16px')};
      background: ${props => (props.clicked && '#4A4A4A')};
      border-radius: ${props => (props.clicked && '50%')};
      color: ${props => (props.clicked && 'white')};
    }
  }
`;

export const TrueOrFalse = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: "Roboto", sans-serif;
  width: 40px;
  height: 40px;
  margin-bottom: 0;
  margin-left: 10px;
  cursor: pointer;
  background: ${props => (
  props.trueOrFalse ? 
    props.selected ? '#FFE132' : '#E7E8EA' 
  : 
    props.selected ? '#FFE132' : '#E7E8EA'
  )};
  /* border: ${props => (
    props.selected ? '1px solid #000000;' : 'none'
  )}; */
  box-sizing: border-box;
  border-radius: 3px;
  font-weight: bold;
  color:${props => (props.trueOrFalse ? '#326741;' : 'rgba(249, 45, 45, 0.92)')};
  font-size: 18px;
`;

export const Wrapper = styled.div`
  pointer-events:${props => (props.disabled && 'none')};
  opacity:${props => (props.disabled && "0.7")};
`;
