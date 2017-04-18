var track_page = 1; //track user scroll as page number, right now page number is 1
var loading = false; //prevents multiple loads

load_contents(track_page); //initial content load

$(window).scroll(function () { //detect page scroll
  if ($(window).scrollTop() >= $(document).height() - $(window).height() - 200) { //if user scrolls within 100px of bottom of the page
    var values = $('#tags').val();
    load_contents(track_page, values); //load content
  }
});

$('#tags').change(function () {
  $('#tags').prop('disabled', true);
  $("#results").html("");
  var values = $('#tags').val();
  track_page = 1; //reset page
  load_contents(track_page, values);
  $('.loading-info').html('<br/><img src="img/loading.gif"/>');

});

//Ajax load function
function load_contents(page, tags=[]) {
  if (loading == false) {
    loading = true;
    $('.loading-info').show();

    $.post('src/get_pages.php', {'page': page, 'tags': JSON.stringify(tags)}, function (data) {
      $("#tags").prop("disabled", false);
      loading = false; //set loading flag off once the content is loaded
      if (data.trim().length == 0) {
        $('.loading-info').html("<br/>No more images!");
        return;
      }
      track_page++;
      $('.loading-info').hide(); //hide loading animation once data is received
      $("#results").append(data);
      new flexImages({selector: '.flex-images', rowHeight: 400});

    }).fail(function (xhr, ajaxOptions, thrownError) {
//        alert(thrownError); //alert with HTTP error
    })
  }
}
