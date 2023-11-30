import styled from 'styled-components';

export const ConfigQuestao = styled.nav`
  min-height: 76px;
  border-top: 0.5px solid #cccccc;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin: 0 30px;
  .navbar-toggler{
    margin-top: 13px;
    margin-bottom: 13px;
  }
  ul {
    width: 100%;
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    li {
      /* margin-left: 40px; */
      display: flex;
      flex-direction: row;
      align-items: center;
      text-align: right;
      

      .form-control, .form-select {
          height: 32px;
      }
      .form-select {
          width: 80px;
      }
      label {
        margin-right: 5px;
        font-weight: normal;
        line-height: 100%;
      }
    }
  }

  @media only screen and (max-width: 991px){
    ul {
      display: flex;
      align-items: flex-end;
      li {
        padding: 0;
        margin-top: 10px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: right;
      }
    }
  }
`;
