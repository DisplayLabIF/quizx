jQuery(document).ready(function() {
    $('.remove-quizNivelamento').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
        setInputOrdemNivelamento();
        return false;
    });
    var $collectionHolder = $('ol.quizesNivelamento');

    $collectionHolder.data('index', $collectionHolder.children().length);
    
    $('.add-nivelamento').on('click', function(e) {
        e.preventDefault();
        addTagForm($collectionHolder);
    });
        
        
});

function setInputOrdemNivelamento() {
    $('li.quizNivelamento').each(function (index) {
        var $inputOrdem = $(this).find('.ordem');
        $inputOrdem.val(index + 1);

        atualizarMarcadorListGrupo();
    })
}

function atualizarMarcadorListGrupo() {
    $('li.quizNivelamento').each(function (index) {
        var $inputOrdem = $(this).find('.ordem');
        var $marcadorList = $(this).find('.btn-marcador-list');
        $marcadorList.text($inputOrdem.val());
    })
}

function addTagForm($collectionHolder) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    $collectionHolder.append(newForm);
    setInputOrdemNivelamento();

    $('.remove-quizNivelamento').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
        setInputOrdemNivelamento();
        return false;
    });
}       
