import React from "react";
import ReactDOM from "react-dom";
import AppQuiz from "./src/index-quiz";

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

const el = document.getElementById('root-quiz');
ReactDOM.render(
    <AppQuiz urlFinalizar={el.getAttribute('data-url-finalizar')}  typeQuiz={el.getAttribute('data-type-quiz')} />, 
    el
);
