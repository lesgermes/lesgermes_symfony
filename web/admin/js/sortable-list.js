function initSortableList() {
    sortableList = $('.list-group-sortable-handles');
    sortableListElements = $(sortableList).find('.list-group-item');
    sortableListElements.prepend('<i class="fa fa-sort-desc fa-border"></i><i class="fa fa-sort-asc fa-border"></i>');

    $('.list-group-sortable-handles > .list-group-item .fa-sort-desc').click(sortableElement_moveDown);

    $('.list-group-sortable-handles > .list-group-item .fa-sort-asc').click(sortableElement_moveUp);
}

function sortableElement_moveDown(event) {
    element = $(event.target).parent();
    element.insertAfter(element.next());
}

function sortableElement_moveUp(event) {
    element = $(event.target).parent();
    element.insertBefore(element.prev());
}