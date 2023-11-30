import React from "react";
import { Provider } from 'react-redux';
import store from './store';
import Routes from "./routes";
import GlobalStyle from './styles/global';

function AppQuiz(props) {
  

  return (
    <Provider store={store}>
      <Routes urlFinalizar={props.urlFinalizar} typeQuiz={props.typeQuiz} />
      <GlobalStyle />
    </Provider>
  );
}

export default AppQuiz;
