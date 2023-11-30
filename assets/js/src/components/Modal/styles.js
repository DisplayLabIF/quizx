import styled from 'styled-components';

export const Container = styled.div`
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  transition: all 0.5s;
  display: ${props => (props.show ? 'flex' : 'none')};
  align-items: center;
  justify-content: center;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 105;
`;
export const Modal = styled.div`
  width: 470px;
  height: 80%;
  border-radius: 8px;
  padding: 0 15px;
  background-color: #fff;
  flex-direction: column;
  z-index: 3;
  /*box-shadow: 0px 0px 4px #000;*/
  @media only screen and (max-width: 540px){
    width: 300px;
    height: 85%;
  }
`;
export const Header = styled.div`
  display: flex;
  justify-content: space-between;
  width: 100%;
  height: 20%;
  align-items: center;
  border-bottom: 1px solid #d9dce9;
  color: #ffe132;
  h1 {
    font-size: 18px;
    font-weight: bold;
    margin: 20px;
  }

  .closeButton {
    display: flex;
    height: 100%;
    align-items: center;
    padding: 0 20px;
    cursor: pointer;

    svg {
      font-size: 24px;
    }
  }
`;
export const Body = styled.div`
  display: flex;
  flex-direction: column;
  padding-bottom: 20px;
  height: 60%;
  
  p {
    i {
      color: #4a4a4a;
      opacity: 0.5;
    }
  }
`;
export const Wrapper = styled.div`
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
  width: ${props => (props.width ? props.width : '')};
  height: ${props => (props.height ? props.height : '')};
  label {
    color: var(--primary);
    font-size: 14px;
    font-weight: bold;
    margin-top: 10px;
  }
  .form-control {
    max-width: 70%;
  }
  .select {
    width: 50%;
  }
`;

export const Footer = styled.div`
  display: flex;
  bottom: 0px;

  align-items: center;
  border-top: 1px solid #d9dce9;
  width: 100%;
  height: 20%;

`;
