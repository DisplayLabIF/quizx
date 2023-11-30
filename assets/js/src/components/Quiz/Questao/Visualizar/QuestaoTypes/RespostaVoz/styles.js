import styled from 'styled-components';

export const Container = styled.div`
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  position: relative;
  h1 {
    top: 15%;
    font-size: 21px;
    line-height: 29px;
    color: #4a4a4a;
    font-style: normal;
    font-weight: normal;
    text-align: center;
    margin-bottom: 10px;
  }
  h2 {
    text-align: center;
    font-size: 36px;
    line-height: 49px;
    font-weight: bold;
    margin-bottom: 0px;
  }
  button + button {
    margin-left: 25px;
  }
`;

export const Feedback = styled.div`
  display: flex;
  visibility: ${props => (props.show ? 'visible' : 'hidden')};
  justify-content: center;
  align-items: center;
  margin-top: 30px;
  min-width: 200px;
  min-height: 35px;
  background: #ffefbc;
  color: #4a4a4a;
`;

export const Wrapper = styled.div`
  display: flex;
  justify-content: center;
  width: 100%;
  min-height: 265px;
  margin-bottom: 20px;
  div#microfone-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 50%;
  }
`;
