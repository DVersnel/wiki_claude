function filterTagsSelectOptions(input) {
    var select = document.getElementById(input.id.replace('_search', ''));
    if (!select) {
        return;
    }

    var query = input.value.toLowerCase();
    for (var i = 0; i < select.options.length; i++) {
        var option = select.options[i];
        option.hidden = query !== '' && option.text.toLowerCase().indexOf(query) === -1;
    }
}
