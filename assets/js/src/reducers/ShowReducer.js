import actionsTypes from '../constants/ActionsTypes';

const INITIAL_STATE = {
  showModalCadastrarUsuario: false,
};

export default function(state = INITIAL_STATE, action) {
  switch (action.type) {
    case actionsTypes.TOGGLE_SHOW_MODAL_CADASTRAR_USUARIO:
      return { ...state, showModalCadastrarUsuario: !state.showModalCadastrarUsuario };
      
    default:
      return state;
  }
}
