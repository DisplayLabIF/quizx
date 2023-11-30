import styled from 'styled-components';

export const Container = styled.div`
  position: relative;
`;

export const Controls = styled.div`
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 5px 5px;
  width: 96%;
  position: absolute;
  bottom: 15px;
  margin-left: 2%;
  height: 60px;
  transition: all 1s;
  opacity: ${props => (props.showControls ? 0.8 : 0)};
  .btn-play {
    border: none;
    background: none;
    font-size: 16px;
    padding-left: 3%;
    color: #000000;
    cursor: pointer;
    width: '10%';
    height: '100%';
  }
  .btn-expand {
    border: none;
    color: #000000;
    background: none;
    font-size: 16px;
    margin-left: 5px;
  }
`;
