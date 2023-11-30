import styled from "styled-components";

export const Container = styled.div`
  width: 100%;
  height: 100px;
  margin-top: 15px;
  button {
    height: 50px;
    padding: 15px;
    padding-left: 30px;
    padding-right: 30px;
    cursor: pointer;
    font: 16px 'Roboto', sans-serif !important;
    font-weight: bold !important;
  }
  .tempo-questao{
    font-family: "Roboto", sans-serif;
    font-style: normal;
    font-size: 20px;
    text-align: center;
    color: #4A4A4A;
    span{
      font-weight: bold;
    }
  }
  button.back {
    width: 151px;
  }
  button.next {
    width: 151px;
  }
`;


export const CorrecaoQuestao = styled.div`
  display: ${props => (props.show ? 'flex' : 'none')};
  align-items: center;
  justify-content: space-between;
  background: ${props => (props.correta ? '#86FFA8' : '#FC6D5D')};
  width: 100%; 
  height: 100%;
  .feedBack{ 
    height: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    p{
      margin: 0;
      margin-left: 7px;
      font-family: "Roboto", sans-serif;
      font-weight: bold;
      font-size: 20px;
      color: ${props => (props.correta ? '#326741' : '#78362F')};
      span{
        font-weight: normal;
      }
    }
  }
`;

export const ButtonsPularVerificar = styled.div`
  display: ${props => (props.verificarResposta && 'none')};
`;

