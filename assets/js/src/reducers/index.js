import { combineReducers } from 'redux';
import QuizReducer from './QuizReducer'
import ShowReducer from './ShowReducer'
import VisualizarReducer from './VisualizarReducer';

export default combineReducers({    
  QuizReducer,
  ShowReducer,
  VisualizarReducer
});