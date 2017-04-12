<html>

<head>
  <meta charset="utf-8">

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/select2-bootstrap.min.css" rel="stylesheet">
  <link href="css/4-col.css" rel="stylesheet">
  <link href="css/select2.min.css" rel="stylesheet"/>
  <link href="css/flex-images.css" rel="stylesheet">

</head>

<body>
<!-- navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button"
              class="navbar-toggle"
              data-toggle="collapse"
              data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"
         href="#">Start Bootstrap</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse"
         id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li>
          <a href="#">About</a>
        </li>
        <li>
          <a href="#">Services</a>
        </li>
        <li>
          <a href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Page Heading
        <small>Secondary Text</small>
      </h1>
    </div>
  </div>

  <!-- tag search -->
  <div class="row tags">
    <div class="container-fluid">
      <select id="tags" class="form-control" multiple>
        <option></option>
        <?php
        require('get_tags.php');
        foreach ($tag_array as $key => $value):
          echo '<option value="' . $key . '">' . $value . '</option>';
        endforeach;
        ?>
      </select>
    </div>
  </div>

  <!-- Loading / Images -->
  <div class="row" style="text-align: center">
    <div class="container">
      <div class="flex-images" id="results">
        <!-- results appear here -->
      </div>
      <div class="loading-info">
        <img src="loading.gif"/>
      </div>
    </div>
  </div>

  <hr>

  <!--  Footer -->
  <div class="container">
    <footer>
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright &copy; Your Website 2017</p>
        </div>
      </div>
    </footer>
  </div>

</div>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/flex-images.js"></script>

<!-- select2 for tags -->
<script type="text/javascript">
  $("#tags").prop("selectedIndex", -1);
  $("#tags").select2({
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
</script>

<!-- load images on scroll and <select> -->
<script type="text/javascript">
  var track_page = 1; //track user scroll as page number, right now page number is 1
  var loading = false; //prevents multiple loads

  load_contents(track_page); //initial content load

  $(window).scroll(function () { //detect page scroll
    if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) { //if user scrolled to bottom of the page
      track_page++; //page number increment
      load_contents(track_page); //load content
    }
  });

  $('select').change(function() {
    $("#results").html("");
    var values = $('#tags').val();
    var track_page = 1; //reset page
    load_contents(track_page, values);
  });

  //Ajax load function
  function load_contents(track_page, tags=[]) {
    if (loading == false) {
      loading = true;
      $('.loading-info').show();
      $.post('get_pages.php', {'page': track_page, 'tags':JSON.stringify(tags)}, function (data) {
        loading = false; //set loading flag off once the content is loaded
        if (data.trim().length == 0) {
          $('.loading-info').html("No more images!");
//          return;
        }
        $('.loading-info').hide(); //hide loading animation once data is received
        $("#results").append(data);
        new flexImages({ selector: '.flex-images', rowHeight: 300 });

      }).fail(function (xhr, ajaxOptions, thrownError) {
//        alert(thrownError); //alert with HTTP error
      })
    }
  }
</script>


</body>

</html>
