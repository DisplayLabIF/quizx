import React from 'react';
import {BrowserRouter, Switch, Route} from 'react-router-dom';
import Quiz from "./components/Quiz";

function Routes(props){
    const urlFinalizar = props.urlFinalizar;
    const typeQuiz = props.typeQuiz;

    return (
        <BrowserRouter>
            <Switch>
                <Route 
                    path="/app/criar-quiz" 
                    render={ props => (
                        <Quiz {...props} urlFinalizar={urlFinalizar} typeQuiz={typeQuiz}/>
                    )}
                />
                <Route 
                    path="/app/:id/quiz" 
                    render={ props => (
                        <Quiz {...props} urlFinalizar={urlFinalizar} typeQuiz={typeQuiz}/>
                    )}
                />
            </Switch>
        </BrowserRouter>
    );
}

export default Routes;