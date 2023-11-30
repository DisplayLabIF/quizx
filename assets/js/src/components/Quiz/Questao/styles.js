import styled from 'styled-components';

export const Container = styled.div`
    margin-top: 1.5rem;
    border: 1px solid #dadada;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
    border-radius: 4px;

    .align-middle {
        display: flex;
        align-items: center;
    }

    .box-alternativas {
        padding-left: 2.5rem;
    }

    div[contenteditable="true"]{
      position: relative;
      width: 100%;
      min-height: 80px;
      border: 1px solid #dadada;
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
      border-radius: 4px;
      padding: 5px;
      font-size: 18px;
      margin-bottom: 10px;
      margin-top: 5px;
    } 
    div[contenteditable="true"]:focus {
      outline: 1px;
      outline-color: var(--primary);
      background: #ffffff;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 4px;
      border: 1px solid var(--primary);
      box-sizing: border-box;
    } 
    
    @media screen and (max-width: 767px) {
      .box-alternativas {
        padding-left: 0rem;
      }
    }
`;