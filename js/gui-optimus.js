/* Show the range slider value in the output box nearby */
function rangeOutputUpdate(rangeElement) {
    $(rangeElement).siblings("output").val($(rangeElement).val());
}
