$("#tags").prop("selectedIndex", -1);
$("#tags").select2({
  width: '100%',
  placeholder: " Select or search for tags",
  createTag: function (params) {
    if (params.term.indexOf('@') === -1) {
      return null;
    }

    return {
      id: params.term,
      text: params.term
    }
  }
}).on('select2:open', function () {

  $('.select2-dropdown--above').attr('id', 'fix');
  $('#fix').removeClass('select2-dropdown--above');
  $('#fix').addClass('select2-dropdown--below');

});