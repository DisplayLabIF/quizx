import actionsTypes from '../constants/ActionsTypes';

const actions = {
    setQuiz: quiz => ({
        type: actionsTypes.SET_QUIZ,
        quiz
    }),
    setQuizQuestao: (old_id, questao) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO,
        data: { old_id, questao}
    }),
    setQuizQuestoes: questoes => ({
        type: actionsTypes.SET_QUIZ_QUESTOES,
        questoes
    }),
    setQuizId: id => ({
        type: actionsTypes.SET_QUIZ_ID,
        id
    }),
    setQuizQuestaoId: (old_id, new_id) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_ID,
        data:{ old_id, new_id }
    }),
    setQuizNome: nome => ({
        type: actionsTypes.SET_QUIZ_NOME,
        nome
    }),
    setQuizNivel: nivel => ({
        type: actionsTypes.SET_QUIZ_NIVEL,
        nivel
    }),
    setQuizAssuntos: assuntos => ({
        type: actionsTypes.SET_QUIZ_ASSUNTOS,
        assuntos
    }),
    setQuizImage: image => ({
        type: actionsTypes.SET_QUIZ_IMAGE,
        image
    }),
    setQuizQuestaoSelected:  questao_index => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_SELECTED,
        questao_index
    }),
    setQuizAddQuestao: id => ({
        type: actionsTypes.SET_QUIZ_ADD_QUESTAO,
        id
    }),
    setQuizExcluirQuestao: () => ({
        type: actionsTypes.SET_QUIZ_EXCLUIR_QUESTAO
    }),
    setQuizQuestaoPergunta: (id, pergunta) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_PERGUNTA,
        data: { id, pergunta }
    }),
    setQuizQuestaoExplicacaoResposta: (id, explicacaoResposta) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_EXPLICACAO_RESPOSTA,
        data: { id, explicacaoResposta }
    }),
    setQuizQuestaoTipoResposta:(id, tipoResposta) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_TIPO_RESPOSTA,
        data: { id, tipoResposta }
    }),
    setQuizAddOpcao: id_questao => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_ADD_OPCAO,
        id_questao
    }),
    excluirQuizOpcao: (indiceQuestao, indiceOpcao) => ({
        type: actionsTypes.EXCLUIR_QUIZ_OPCAO,
        indiceOpcao,
        indiceQuestao
    }),
    setQuizOpcaoTexto: (indiceQuestao, indiceOpcao, texto) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_OPCAO_TEXTO,
        indiceOpcao,
        indiceQuestao,
        texto
    }),
    setQuizTrueOrFalseCaracteres: (indiceQuestao, trueOrFalse, texto) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_TRUE_OR_FALSE_CARACTERES,
        indiceQuestao,
        texto,
        trueOrFalse
    }),
    setQuizOpcaoImagem: (indiceQuestao, indiceOpcao, imagem) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_OPCAO_IMAGEM,
        indiceOpcao,
        indiceQuestao,
        imagem
    }),
    setQuizOpcaoRespostaCorreta: (indiceQuestao, indiceOpcao, resposta_correta) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_OPCAO_RESPOSTA_CORRETA,
        indiceOpcao,
        indiceQuestao,
        resposta_correta
    }),
    setQuizOpcaoId: (indiceQuestao, indiceOpcao, id) => ({
        type: actionsTypes.SET_QUIZ_OPCAO_ID,
        indiceOpcao,
        indiceQuestao,
        id
    }),
    setQuizQuestaoTempo: (id, tempo) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_TEMPO,
        data: { id, tempo }
    }),
    setQuizQuestaoObrigatoria: (id, obrigatoria) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_OBRIGATORIA,
        data: { id, obrigatoria }
    }),
    setQuizQuestaoMostrarExplicacao: (id, mostrar_explicacao) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_MOSTRAR_EXPLICACAO,
        data: { id, mostrar_explicacao }
    }),
    setQuizQuestaoValor: (id, valor) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_VALOR,
        data: { id, valor }
    }),
    setQuizQuestaoTipoArquivo: (id, tipo, respostaOuExplicacao) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_TIPO_ARQUIVO,
        data: { id, tipo, respostaOuExplicacao }
    }),
    setQuizQuestaoUrlArquivo: (id, url, respostaOuExplicacao, indice) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_URL_ARQUIVO,
        data: { id, url, respostaOuExplicacao, indice }
    }),
    setQuizQuestaoProvider: (id, provider, respostaOuExplicacao, indice) => ({
        type: actionsTypes.SET_QUIZ_QUESTAO_PROVIDER,
        data: { id, provider, respostaOuExplicacao, indice }
    }),
    setQuizArquivosQuestao: (id_questao, arquivos) => ({
        type: actionsTypes.SET_QUIZ_ARQUIVOS_QUESTAO,
        data: { id_questao, arquivos }
    }),
    
}

export default actions;