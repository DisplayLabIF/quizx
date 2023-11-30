import actionsTypes from '../constants/ActionsTypes';

const defaultRespostas ={
  multiplaEscolhaSelected: '',
  trueOrFalseSelected: [ {resposta_correta: true, selectedTrue: false, selectedFalse: false} ],
  respostaAberta: '', 
  ordenarFrases: [],
  resultadoRespostaVoz: false, 
}

const INITIAL_STATE = {
  verificarResposta: false,
  multiplaEscolhaSelected: '',
  trueOrFalseSelected: [ {resposta_correta: true, selectedTrue: false, selectedFalse: false} ],
  respostaAberta: '', 
  ordenarFrases: [],
  resultadoRespostaVoz: false, 
};

export default function(state = INITIAL_STATE, action) {
  switch (action.type) {
    case actionsTypes.VISUALIZAR_SET_MULTIPLA_ESCOLHA_SELECTED:
      return { ...state, multiplaEscolhaSelected: action.multiplaEscolhaSelected };

    case actionsTypes.VISUALIZAR_SET_TRUE_OR_FALSE_SELECTED:
      return { ...state, trueOrFalseSelected: action.trueOrFalseSelected };
    
    case actionsTypes.VISUALIZAR_SET_RESPOSTA_ABERTA:
      return { ...state, respostaAberta: action.respostaAberta };

    case actionsTypes.VISUALIZAR_SET_ORDENAR_FRASES:
      return { ...state, ordenarFrases: action.ordenarFrases };

    case actionsTypes.VISUALIZAR_SET_RESULTADO_RESPOSTA_VOZ:
      return { ...state, resultadoRespostaVoz: action.resultadoRespostaVoz };

    case actionsTypes.VISUALIZAR_SET_VERIFICAR_RESPOSTA:
      return { ...state, verificarResposta: action.verificarResposta };

    case actionsTypes.VISUALIZAR_SET_RESETAR_RESPOSTAS:
      return { ...state, ...defaultRespostas };

    default:
      return state;
  }
}
