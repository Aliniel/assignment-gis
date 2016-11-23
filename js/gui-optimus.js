/* Show the range slider value in the output box nearby */
function rangeOutputUpdate(rangeElement) {
    $(rangeElement).siblings("output").val($(rangeElement).val());
}

/* Adds another option group */
function addOptionGroup() {
    var optionGroup = $(".option-group");
    var n = optionGroup.length + 1;
    if (n > 7) {
        alert("Maximum number of options shown already.");
        return;
    }

    var newOptionGroup = optionGroup.first().clone();

    newOptionGroup.find("select").prop("name", "select-" + n);
    newOptionGroup.find("input[type='text']").prop("name", "input-" + n);
    newOptionGroup.find("input[type='range']").prop("name", "range-" + n);

    $(".fa:first").before("<hr/>");
    $(".fa:first").before(newOptionGroup);
}

/* Removes all option groups except the first one on form reset. */
function removeOptionGroups() {
    $(".option-group:not(:first)").remove();
    $("hr").remove();
}
