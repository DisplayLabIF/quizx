if(document.getElementById('nivelamento_configuracaoNivelamento_obterDadosRespondente_0').checked){
    document.getElementById('info-solicitar-dados').style.display = 'block';
}else{
    document.getElementById('info-solicitar-dados').style.display = 'none';
}

if(document.getElementById('nivelamento_configuracaoNivelamento_redirecionarUsuario_0').checked){
    document.getElementById('urlCallBack-quiz-configuracao').style.display = 'block';
}else{
    document.getElementById('urlCallBack-quiz-configuracao').style.display = 'none';
}

if(document.getElementById('nivelamento_configuracaoNivelamento_definirTempoResposta_0').checked){
    document.getElementById('tempo-maximo-quiz-configuracao').style.display = 'block';
}else{
    document.getElementById('tempo-maximo-quiz-configuracao').style.display = 'none';
}

if(document.getElementById('nivelamento_configuracaoNivelamento_obterEmpresa').checked){
    document.getElementById('nivelamento_configuracaoNivelamento_obterCnpj').disabled = false;
    document.getElementById('nivelamento_configuracaoNivelamento_obterCpf').disabled = true;
}

if(document.getElementById('nivelamento_configuracaoNivelamento_redirecionarAutomaticamente_1').checked){
    document.getElementById('textoBotaoRedirecionar-quiz-configuracao').style.display = 'block';
}else{
    document.getElementById('textoBotaoRedirecionar-quiz-configuracao').style.display = 'none';
}

document.getElementById('nivelamento_configuracaoNivelamento_definirTempoResposta_0').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('tempo-maximo-quiz-configuracao').style.display = 'block';
    }
})
document.getElementById('nivelamento_configuracaoNivelamento_definirTempoResposta_1').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('tempo-maximo-quiz-configuracao').style.display = 'none';
    }
})


document.getElementById('nivelamento_configuracaoNivelamento_obterDadosRespondente_0').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('info-solicitar-dados').style.display = 'block';
    }
})
document.getElementById('nivelamento_configuracaoNivelamento_obterDadosRespondente_1').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('info-solicitar-dados').style.display = 'none';
    }
})

document.getElementById('nivelamento_configuracaoNivelamento_redirecionarUsuario_0').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('urlCallBack-quiz-configuracao').style.display = 'block';
    }
})
document.getElementById('nivelamento_configuracaoNivelamento_redirecionarUsuario_1').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('urlCallBack-quiz-configuracao').style.display = 'none';
    }
})
document.getElementById('nivelamento_configuracaoNivelamento_obterEmpresa').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('nivelamento_configuracaoNivelamento_obterCnpj').disabled = false;
        document.getElementById('nivelamento_configuracaoNivelamento_obterCpf').disabled = true;
    } else {
        document.getElementById('nivelamento_configuracaoNivelamento_obterCnpj').disabled = true;
        document.getElementById('nivelamento_configuracaoNivelamento_obterCpf').disabled = false;
    }
})

document.getElementById('nivelamento_configuracaoNivelamento_redirecionarAutomaticamente_1').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('textoBotaoRedirecionar-quiz-configuracao').style.display = 'block';
    }
})
document.getElementById('nivelamento_configuracaoNivelamento_redirecionarAutomaticamente_0').addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('textoBotaoRedirecionar-quiz-configuracao').style.display = 'none';
    }
})

