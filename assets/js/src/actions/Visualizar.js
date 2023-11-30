import actionsTypes from '../constants/ActionsTypes';

const actions = {
  visualizarSetTrueOrFalseSelected: trueOrFalseSelected => ({
    type: actionsTypes.VISUALIZAR_SET_TRUE_OR_FALSE_SELECTED,
    trueOrFalseSelected
  }),
  visualizarSetOrdenarFrases: ordenarFrases => ({
    type: actionsTypes.VISUALIZAR_SET_ORDENAR_FRASES,
    ordenarFrases
  }),
  visualizarSetMultiplaEscolhaSelected: multiplaEscolhaSelected => ({
    type: actionsTypes.VISUALIZAR_SET_MULTIPLA_ESCOLHA_SELECTED,
    multiplaEscolhaSelected
  }),
  visualizarSetTrueOrFalseSelected: trueOrFalseSelected => ({
    type: actionsTypes.VISUALIZAR_SET_TRUE_OR_FALSE_SELECTED,
    trueOrFalseSelected
  }),
  visualizarSetResultadoRespostaVoz: resultadoRespostaVoz => ({
    type: actionsTypes.VISUALIZAR_SET_RESULTADO_RESPOSTA_VOZ,
    resultadoRespostaVoz
  }),
  visualizarSetRespostaAberta: respostaAberta => ({
    type: actionsTypes.VISUALIZAR_SET_RESPOSTA_ABERTA,
    respostaAberta
  }),
  visualizarSetVerificarResposta: verificarResposta => ({
    type: actionsTypes.VISUALIZAR_SET_VERIFICAR_RESPOSTA,
    verificarResposta
  }),
  visualizarSetResetarRespostas: () => ({
    type: actionsTypes.VISUALIZAR_SET_RESETAR_RESPOSTAS
  }), 
};
export default actions;
