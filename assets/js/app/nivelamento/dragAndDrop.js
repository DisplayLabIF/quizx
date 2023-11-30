var adjustment;

$("ol.simple_with_animation").sortable({
    group: 'simple_with_animation',
    pullPlaceholder: false,
    // animation on drop
    onDrop: function  ($item, container, _super) {
        var $clonedItem = $('<li/>').css({height: 0});
        $item.before($clonedItem);
        $clonedItem.animate({'height': $item.height()});

        $item.animate($clonedItem.position(), function  () {
            $clonedItem.detach();
            _super($item, container);
        });
        setInputOrdemNivelamento();
    },

    // set $item relative to cursor position
    onDragStart: function ($item, container, _super) {
        var offset = $item.offset(),
            pointer = container.rootGroup.pointer;

        adjustment = {
            left: pointer.left - offset.left,
            top: pointer.top - offset.top
        };

        _super($item, container);
        setInputOrdemNivelamento();
    },
    onDrag: function ($item, position) {
        $item.css({
            left: position.left - adjustment.left,
            top: position.top - adjustment.top
        });
        setInputOrdemNivelamento();
    }
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

atualizarMarcadorListGrupo();
