import React, { useEffect } from 'react';

function TempoResposta({ tempo }) {

  function formata(tempo, tempoCorrido) {
    let mm = null;
    let ss = parseInt(tempo) - tempoCorrido;
    let minOrseg = ss > 59 ? 'min' : 'seg';

    while (ss > 59) {
      if (ss >= 60) {
        mm += 1;
        ss -= 60;
      }
    }
    ss = ss < 0 ? 0 : ss;
    let format = `${!mm ? '' : mm < 10 ? '0' + mm + ':' : mm + ':'}${
      ss < 10 ? '0' + ss : ss
    }`;
    return `<span>${format}</span><br/>${minOrseg}. restantes`;
  }

  useEffect(() => {
    var cron = null;
    const tempoFooterQuestao = document.getElementById("tempo-footer-questao");
    if(tempo && tempo !== "NONE"){
      var tempoCorrido = 0;
      cron = setInterval(()=>{
        tempoCorrido++;
        tempoFooterQuestao.innerHTML = formata(tempo, tempoCorrido);
        var percentComplete = ((tempoCorrido / tempo) * 100);
        document.getElementById('barra-progress-tempo').style.width=percentComplete + '%';
        if(parseInt(tempo) === tempoCorrido){
          tempoFooterQuestao.innerHTML = 'Tempo esgotado';
          return clearInterval(cron);
        }
      }, (1000));
    }else{
      tempoFooterQuestao.innerHTML = '';
    }
    return () => clearInterval(cron);
  }, [tempo]);

  return (
    <p id="tempo-footer-questao" className="tempo-questao">Iniciando contagem</p>
  );
}

export default TempoResposta;
