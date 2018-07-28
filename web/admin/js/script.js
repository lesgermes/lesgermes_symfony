$(function()
{
    $(document)
        .ajaxStart(NProgress.start)
        .ajaxStop(NProgress.done);
});

function disableModalInputs(disable) {
    var inputs = $('#modals').find('button, select');
    if (disable)
        inputs.prop('disabled', true);
    else
        inputs.prop('disabled', false);
}

function showError(message) {
    new PNotify({
        title: 'Erreur',
        text: message,
        type: 'error',
        styling: 'bootstrap3'
    });
}

function showSuccess(message) {
    new PNotify({
        title: 'Succès',
        text: message,
        type: 'success',
        styling: 'bootstrap3'
    });
}

function objectifyForm(formArray) {//serialize data function

    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}

function formAddToCollection(button, className, protoName) {
    var prototype = $($(button)[0].parentNode.parentNode).find('.' + className).attr('data-prototype');
    var id = $($(button)[0].parentNode.parentNode).find('.' + className + ' > .form-group').length;
    var regex = new RegExp(protoName, 'g');
    var regexLabel = new RegExp(protoName + 'label__', 'g');
    var html = prototype.replace(regexLabel, id).replace(regex, id);
    $($(button)[0].parentNode.parentNode).find('.' + className).append(html);
}