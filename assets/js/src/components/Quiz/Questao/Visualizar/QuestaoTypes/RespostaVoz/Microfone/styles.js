import styled from 'styled-components';

export const Container = styled.div`
`;


export const Mic = styled.button`
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 20px;
  width: 100px;
  height: 100px;
  font-size: 48px;
  border-radius: 50%;
  transition: all 0.5s;
  border: none;
  cursor: pointer;
`;

export const Alert = styled.div`
  display: ${props => (props.show ? 'block' : 'none')};
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translate(-50%, -50%);
`;
