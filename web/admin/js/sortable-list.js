function initSortableList() {
    sortableList = $('.list-group-sortable-handles');
    sortableListElements = $(sortableList).find('.list-group-item');
    sortableListElements.prepend('<button class="btn btn-default btn-sm set-position">Set position</button><i class="fa fa-sort-desc fa-border"></i><i class="fa fa-sort-asc fa-border"></i>');

    $('.list-group-sortable-handles > .list-group-item .fa-sort-desc').click(sortableElement_moveDown);

    $('.list-group-sortable-handles > .list-group-item .fa-sort-asc').click(sortableElement_moveUp);

    $('button.set-position').click(sortableElement_setPositionButton);
}

function sortableElement_moveDown(event) {
    element = $(event.target).parent();
    element.insertAfter(element.next());
}

function sortableElement_moveUp(event) {
    element = $(event.target).parent();
    element.insertBefore(element.prev());
}

function sortableElement_setPositionButton(event) {
    element = $(event.target).parent();
    list = element.parent().children();
    element.prepend('<input type="number">');
    button = element.find('button.set-position');
    button.remove();
    input = element.find('input[type=number]');
    input.val(list.index(element));
    input.focus();
    input.focusout(sortableElement_setPositionButton_focusout)
}

function sortableElement_setPositionButton_focusout(event) {
    element = $(event.target).parent();
    list = element.parent().children();
    position = $(event.target).val();
    element.appendToWithIndex(element.parent(),position,list.index(element));
    element.prepend('<button class="btn btn-default btn-sm set-position">Set position</button>');
    input = element.find('input[type=number]');
    input.remove();
    button = element.find('button.set-position');
    button.click(sortableElement_setPositionButton);
}

$.fn.appendToWithIndex=function(to,index,currentIndex){
    if(! to instanceof jQuery){
        to=$(to);
    };
    if(index===0){
        $(this).prependTo(to)
    }else if (index>currentIndex){
        $(this).insertAfter(to.children().eq(index));
    }else{
        $(this).insertBefore(to.children().eq(index));
    }
}