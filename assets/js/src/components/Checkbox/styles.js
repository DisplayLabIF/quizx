import styled from "styled-components";

export const Container = styled.label`
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: -3px;
  height: 31px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

  /* Hide the browser's default checkbox */
  input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
  }

  /* Create a custom checkbox */
  .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    /*margin-left: 5px;*/
    background-color: white;
    border-radius: 4px;
    border: 1px solid #9F9C9C;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
  }

  /* On mouse-over, add a grey background color */
  &:hover input ~ .checkmark {
    /*background-color: var(--secondary);*/
  }

  /* When the checkbox is checked, add a blue background */
  input:checked ~ .checkmark {
    /*background-color: var(--secondary);*/
  }

  /* Create the checkmark/indicator (hidden when not checked) */
  .checkmark:after {
    content: "";
    position: absolute;
    display: none;
  }

  /* Show the checkmark when checked */
  input:checked ~ .checkmark:after {
    display: block;
  }

  /* Style the checkmark/indicator */
  .checkmark:after {
    left: 7px;
    top: 0px;
    width: 10px;
    height: 17px;
    border: solid #23AC39;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
  }
`;
