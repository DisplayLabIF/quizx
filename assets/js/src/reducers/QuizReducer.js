import actionsTypes from '../constants/ActionsTypes';

const opcaoDefault = {
  id: null,
  numero_opcao: null,
  resposta_correta: false,
  texto: '',
  imagem: null,
  active: true
};

const questaoDefault = {
  id: 1,
  pergunta: '',
  explicacao_resposta: '',
  tipo:'MULTIPLA_ESCOLHA',
  opcoes: [{ ...opcaoDefault, numero_opcao: 1 }, { ...opcaoDefault, numero_opcao: 2 }],
  valor: 1,
  obrigatoria: true,
  mostrar_explicacao: true,
  tempo: 'NONE',
  active: true,
  numero_questao: 1,
  true_or_false_caracteres: ['V', 'F'],
  arquivos_questao:[{
    arquivos_resposta: {
      type: '',
      url: '',
      provider: ''
    },
    arquivos_explicacao: {
      type: '',
      url: '',
      provider: ''
    }
  }]
};

const INITIAL_STATE = {
  id: null,
  nome: '',
  nivel: 'NONE',
  assuntos: [],
  image: "https://quizclass.s3-sa-east-1.amazonaws.com/assets/images/quiz-apresentacao.png",
  questaoSelected: 0,
  questoes: [{ ...questaoDefault, id: 1 }, { ...questaoDefault, id: 2 }],
  active: true
};



export default function(state = INITIAL_STATE, action) {
  let questoes = [];
  let opcoes = [];

  switch (action.type) {
    case actionsTypes.SET_QUIZ:
      return { ...action.quiz, questaoSelected: 0};

    case actionsTypes.SET_QUIZ_QUESTAO:
      let setQuestoes = state.questoes.map(questao => {
        return questao.id === action.data.old_id
          ? { ...action.data.questao }
          : questao;
      });
      return { 
        ...state, 
        questoes: setQuestoes 
      };

    case actionsTypes.SET_QUIZ_NOME:
      return { ...state, nome: action.nome };

    case actionsTypes.SET_QUIZ_NIVEL:
      return { ...state, nivel: action.nivel };
    
    case actionsTypes.SET_QUIZ_ASSUNTOS:
      return { ...state, assuntos: action.assuntos };

    case actionsTypes.SET_QUIZ_IMAGE:
      return { ...state, image: action.image };
    
    case actionsTypes.SET_QUIZ_ID:
      return { ...state, id: action.id };
      
    case actionsTypes.SET_QUIZ_QUESTAO_SELECTED:
      return { ...state, questaoSelected: action.questao_index };

    case actionsTypes.SET_QUIZ_ADD_QUESTAO:
      let newQuestao = {
        ...questaoDefault,
        tipo: state.questoes.length > 0 ? state.questoes[state.questoes.length - 1].tipo : 'MULTIPLA_ESCOLHA',
        id: state.questoes.length + 1,
        numero_questao: state.questoes.length + 1
      };
      return {
        ...state,
        questaoSelected: state.questoes.length,
        questoes: [
          ...state.questoes,
          { ...newQuestao }
        ]
      };

    case actionsTypes.SET_QUIZ_EXCLUIR_QUESTAO:
    
      questoes = [...state.questoes];

      let newQuestaoSelected = 0;

      if(questoes.length === 1){
        newQuestaoSelected = 0;
      } else if(state.questaoSelected === 0){
        newQuestaoSelected = state.questaoSelected+1;
      }else if(state.questaoSelected === (questoes.length - 1)){
        newQuestaoSelected = state.questaoSelected-1;
      }else{
        newQuestaoSelected = state.questaoSelected-1;
      }

      questoes.splice(state.questaoSelected, 1);

      questoes.forEach((questao, index) => {
        questoes[index].numero_questao = index + 1;
      });

      return {
        ...state,
        questaoSelected: newQuestaoSelected,
        questoes
      };

    case actionsTypes.SET_QUIZ_QUESTAO_PERGUNTA:
      let questoesPergunta = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { ...questao, pergunta: action.data.pergunta }
          : questao;
      });
      return { ...state, questoes: questoesPergunta };
    
    case actionsTypes.SET_QUIZ_QUESTAO_EXPLICACAO_RESPOSTA:
      let questoesExplicacaoResposta = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { ...questao, explicacao_resposta: action.data.explicacaoResposta }
          : questao;
      });
      return { ...state, questoes: questoesExplicacaoResposta };

    case actionsTypes.SET_QUIZ_QUESTAO_TIPO_RESPOSTA:
      let questoesTipoResposta = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { 
              ...questao, 
              tipo: action.data.tipoResposta,
              true_or_false_caracteres: 
                action.data.tipoResposta === 'V_F' && !questao.true_or_false_caracteres ?
                  ['V', 'F']
                :
                questao.true_or_false_caracteres
            }
          : questao;
      });
      return { ...state, questoes: questoesTipoResposta };

    case actionsTypes.SET_QUIZ_QUESTAO_TEMPO:
      let questoesTempo = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { ...questao, tempo: action.data.tempo }
          : questao;
      });
      return { ...state, questoes: questoesTempo };

    case actionsTypes.SET_QUIZ_QUESTAO_OBRIGATORIA:
      let questoesObrigatoria = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { ...questao, obrigatoria: action.data.obrigatoria }
          : questao;
      });
      return { ...state, questoes: questoesObrigatoria };

    case actionsTypes.SET_QUIZ_QUESTAO_MOSTRAR_EXPLICACAO:
      let questoesMostrarExplicacao = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { ...questao, mostrar_explicacao: action.data.mostrar_explicacao }
          : questao;
      });
      return { ...state, questoes: questoesMostrarExplicacao };

    case actionsTypes.SET_QUIZ_QUESTAO_VALOR:
      let questoesValor = state.questoes.map(questao => {
        return questao.id === action.data.id
          ? { ...questao, valor: action.data.valor }
          : questao;
      });
      return { ...state, questoes: questoesValor };

    case actionsTypes.SET_QUIZ_QUESTAO_TRUE_OR_FALSE_CARACTERES:
      questoes = [...state.questoes];
      if(action.trueOrFalse){
        questoes[action.indiceQuestao].true_or_false_caracteres[0]=action.texto;
      }else{
        questoes[action.indiceQuestao].true_or_false_caracteres[1]=action.texto;
      }
      
      return {
        ...state,
        questoes
      };
      
    case actionsTypes.SET_QUIZ_QUESTAO_ID:
      let questoesId = state.questoes.map(questao => {
        return questao.id === action.data.old_id
          ? { ...questao, id: action.data.new_id }
          : questao;
      });
      return { 
        ...state, 
        questoes: questoesId 
      };
    
    case actionsTypes.SET_QUIZ_QUESTAO_ADD_OPCAO:
      let questoesAddOpcao = state.questoes.map(questao => {
        if(questao.id === action.id_questao){
          questao.opcoes.forEach((opcao, index) => {
            questao.opcoes[index].numero_opcao = index + 1;
          });
          return {
            ...questao,
            opcoes: [
              ...questao.opcoes,
              { ...opcaoDefault, numero_opcao: questao.opcoes.length + 1 }
            ]
          };
        }else{
          return  questao;
        }
      });

      

      return { ...state, questoes: questoesAddOpcao };

    case actionsTypes.EXCLUIR_QUIZ_OPCAO:
      
      questoes = [...state.questoes];
      questoes[action.indiceQuestao].opcoes.splice(action.indiceOpcao, 1);

      questoes[action.indiceQuestao].opcoes.forEach((opcao, index) => {
        questoes[action.indiceQuestao].opcoes[index].numero_opcao = index + 1;
      });

      return {
        ...state,
        questoes
      };
      
    case actionsTypes.SET_QUIZ_QUESTAO_OPCAO_TEXTO:
      questoes = [...state.questoes];
      questoes[action.indiceQuestao].opcoes[action.indiceOpcao].texto=action.texto;
      
      return {
        ...state,
        questoes
      };
    
    case actionsTypes.SET_QUIZ_QUESTAO_OPCAO_IMAGEM:
      questoes = [...state.questoes];
      questoes[action.indiceQuestao].opcoes[action.indiceOpcao].imagem=action.imagem;
      
      return {
        ...state,
        questoes
      };
    
    case actionsTypes.SET_QUIZ_QUESTAO_OPCAO_RESPOSTA_CORRETA:
  
      questoes = [...state.questoes];
      questoes[action.indiceQuestao].opcoes[action.indiceOpcao].resposta_correta=action.resposta_correta;
      
      return {
        ...state,
        questoes
      };
    
    case actionsTypes.SET_QUIZ_OPCAO_ID:

      questoes = [...state.questoes];
      questoes[action.indiceQuestao].opcoes[action.indiceOpcao].id=action.id;
      
      return {
        ...state,
        questoes
      };

    case actionsTypes.SET_QUIZ_ARQUIVOS_QUESTAO:
        questoes = state.questoes.map(questao => {
          return questao.id === action.data.id_questao
            ? { 
                ...questao,
                arquivos_questao: action.data.arquivos
              }
            : questao;
        });
      return { 
        ...state, 
        questoes: questoes 
      };
  

    case actionsTypes.SET_QUIZ_QUESTAO_TIPO_ARQUIVO:
      let questoesTipoArquivo = state.questoes.map(questao => {
        if(questao.id === action.data.id){
          let newArquivosQuestao = questao.arquivos_questao;

          if(action.data.respostaOuExplicacao === 'RESPOSTA'){
            if(!newArquivosQuestao.arquivos_resposta){
              newArquivosQuestao = {
                ...newArquivosQuestao,
                arquivos_resposta: [
                  {
                    type: action.data.tipo,
                    url: '',
                    provider: ''
                  }
                ]
              };
            }else if(newArquivosQuestao.arquivos_resposta.length < 2){
              newArquivosQuestao = {
                ...newArquivosQuestao,
                arquivos_resposta: [
                  ...newArquivosQuestao.arquivos_resposta,
                  {
                    type: action.data.tipo,
                    url: '',
                    provider: ''
                  }
                ]
              };
            }else{
              return questao
            }
            
          }else if(action.data.respostaOuExplicacao === 'EXPLICACAO'){
            if(!newArquivosQuestao.arquivos_explicacao){
              newArquivosQuestao = {
                ...newArquivosQuestao,
                arquivos_explicacao: [
                  {
                    type: action.data.tipo,
                    url: '',
                    provider: ''
                  }
                ]
              };
            }else if(newArquivosQuestao.arquivos_explicacao.length < 2){
              newArquivosQuestao = {
                ...newArquivosQuestao,
                arquivos_explicacao: [
                  ...newArquivosQuestao.arquivos_explicacao,
                  {
                    type: action.data.tipo,
                    url: '',
                    provider: ''
                  }
                ]
              };
            }else{
              return questao
            }
            
          }else{
            return questao
          }
         

          return {...questao, arquivos_questao: newArquivosQuestao}

        }else{

          return questao

        }
      });
      return { ...state, questoes: questoesTipoArquivo };

    case actionsTypes.SET_QUIZ_QUESTAO_URL_ARQUIVO:
      let questoesUrlArquivo = state.questoes.map(questao => {
        if(questao.id === action.data.id){
          let newArquivosQuestao = questao.arquivos_questao;

          if(action.data.respostaOuExplicacao === 'RESPOSTA'){
            let newArquivosResposta = [...newArquivosQuestao.arquivos_resposta];
            newArquivosResposta[action.data.indice].url=action.data.url;

            newArquivosQuestao = {
              ...newArquivosQuestao,
              arquivos_resposta: newArquivosResposta
            };
          }else if(action.data.respostaOuExplicacao === 'EXPLICACAO'){
            let newArquivosExplicacao = [...newArquivosQuestao.arquivos_explicacao];
            newArquivosExplicacao[action.data.indice].url=action.data.url;

            newArquivosQuestao = {
              ...newArquivosQuestao,
              arquivos_explicacao: newArquivosExplicacao
            };
          }

          return {...questao, arquivos_questao: newArquivosQuestao}

        }else{

          return questao
          
        }
      });
      return { ...state, questoes: questoesUrlArquivo };
    
    case actionsTypes.SET_QUIZ_QUESTAO_PROVIDER:
      let questoesProvider = state.questoes.map(questao => {
        if(questao.id === action.data.id){
          let newArquivosQuestao = questao.arquivos_questao;

          if(action.data.respostaOuExplicacao === 'RESPOSTA'){
            let newArquivosResposta = [...newArquivosQuestao.arquivos_resposta];
            newArquivosResposta[action.data.indice].provider=action.data.provider;

            newArquivosQuestao = {
              ...newArquivosQuestao,
              arquivos_resposta: newArquivosResposta
            };
          }else if(action.data.respostaOuExplicacao === 'EXPLICACAO'){
            let newArquivosExplicacao = [...newArquivosQuestao.arquivos_explicacao];
            newArquivosExplicacao[action.data.indice].provider=action.data.provider;

            newArquivosQuestao = {
              ...newArquivosQuestao,
              arquivos_explicacao: newArquivosExplicacao
            };
          }

          return {...questao, arquivos_questao: newArquivosQuestao}

        }else{

          return questao
          
        }
      });
      return { ...state, questoes: questoesProvider };

    case actionsTypes.SET_QUIZ_QUESTOES:
      
      questoes = JSON.parse(JSON.stringify(action.questoes));;

      questoes.forEach((questao, index) => {
        questoes[index].numero_questao = index + 1;
      });
      
      return { ...state, questoes: questoes };
      
    
    default:
      return state;
  }
}