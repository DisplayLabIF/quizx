import React from 'react';

function PlanoAcesso() {


  return (
    <div className="modal fade" id="modal-plano-acesso" tabIndex="-1" role="dialog" aria-labelledby="modalPlanoAcessoTitle" aria-hidden="true">
      <div className="modal-dialog modal-dialog-centered" role="document">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="planoAcessoModalLongTitle">Plano acesso</h5>
            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div className="modal-body">
            <h5 className="mt-2" style={{ textAlign: 'center' }}>
              Seu plano não possui acesso para este recurso.
            </h5>
          </div>
          <div className="modal-footer">
            <div className="text-center" style={{width: '100%'}}>
              
              <a  
                className="btn btn-default d-flex align-items-center justify-content-center m-2" 
                href="/planos"
                target="_blank"
              >
                Contratar um novo plano
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default PlanoAcesso;
