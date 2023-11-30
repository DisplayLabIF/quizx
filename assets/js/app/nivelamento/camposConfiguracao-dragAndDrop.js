var adjustment;

$("ol.simple_with_animation_campos_configuracao").sortable({
    group: 'simple_with_animation_campos_configuracao',
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
        setOrdemCampos();
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
        setOrdemCampos();
    },
    onDrag: function ($item, position) {
        $item.css({
            left: position.left - adjustment.left,
            top: position.top - adjustment.top
        });
        setOrdemCampos();
    }
});

function setOrdemCampos() {

    let newOrdem = [];

    $('li.camposConfiguracao').each(function (index) {
        var $inputOrdem = $(this).find('label');
        newOrdem.push($inputOrdem.html());
    })
    document.getElementById('nivelamento_configuracaoNivelamento_ordemCamposArray').value = newOrdem;
}

setOrdemCampos();
