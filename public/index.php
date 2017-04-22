<!DOCTYPE html>
<html>

<head>
  <title>RedditEarth - Search with Image Recognition</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  <meta charset="utf-8">

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/select2-bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/select2.min.css" rel="stylesheet">
  <link href="css/flex-images.css" rel="stylesheet">


</head>

<body>
<!-- navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
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
         href="#">RedditEarth</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse"
         id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li>
          <a href="#" data-toggle="modal" data-target="#about_modal">About</a>
        </li>
        <li>
          <a href="#" data-toggle="modal" data-target="#faq_modal">FAQ</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- modal for About in navbar -->
<div class="modal fade about_modal" tabindex="-1" role="dialog" aria-labelledby="about_modal" aria-hidden="true"
     id="about_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="about_modal">About</h4>
      </div>
      <div class="modal-body">
        <h4><b>RedditEarth:</b> searching r/EarthPorn with image recognition</h4>
        <p class="text" align="justify">
          I am a huge fan of r/EarthPorn (landscape pictures) subreddit on Reddit. But one thing that I felt was missing
          is the ability to filter images by landscape. So I built this tag-based search tool to filter images based on
          objects detected in images. All tags are generated using AWS Rekognition, a deep-learning image analysis
          service.
        </p>
      </div>
      <div class="modal-footer">
        <p class="text" align="justify">
          <a href="https://github.com/tingwai-to/reddit-locations">Source code</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- modal for FAQ in navbar -->
<div class="modal fade faq_modal" tabindex="-1" role="dialog" aria-labelledby="faq_modal" aria-hidden="true"
     id="faq_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="faq_modal">FAQ</h4>
      </div>
      <div class="modal-body">
        <h4>What is this?</h4>
        <p class="text" align="justify">
          RedditEarth is a tag-based search tool for filtering r/EarthPorn images based on objects in an image. Search
          supports multiple tags, for example try "water" and "mountain".
        </p>
        <br>
        <h4>How does this work?</h4>
        <p class="text" align="justify">
          Images are put through AWS Rekognition's object and scene detection. This generates tags based on
          things it thinks it sees. Tags and relevant metadata are then stored in a database for easy access. Only the
          top "hot" Reddit posts are saved.
        </p>
        <br>
        <h4>How often does this update?</h4>
        <p class="text" align="justify">
          Once an hour.
        </p>
        <br>
        <h4>Will you support other subreddits?</h4>
        <p class="text" align="justify">
          Eventually. I'm looking at you r/Wallpapers.
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Body -->
<div class="container">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">r/EarthPorn
        <small>search with image recognition</small>
      </h1>
    </div>
  </div>

  <!-- tag search -->
  <div class="row tags">
    <div class="container-fluid">
      <select id="tags" class="form-control" multiple>
        <option></option>
        <?php
        require('src/get_tags.php');
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
        <br/><img src="img/loading.gif"/>
      </div>
    </div>
  </div>

  <hr>

  <!--  Footer -->
  <!--  <div class="container">-->
  <!--    <footer>-->
  <!--      <div class="row">-->
  <!--        <div class="col-lg-12">-->
  <!--          <p>Copyright &copy; Your Website 2017</p>-->
  <!--        </div>-->
  <!--      </div>-->
  <!--    </footer>-->
  <!--  </div>-->

</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/flex-images.js"></script>
<script src="js/select2_config.js"></script>
<script src="js/load_images.js"></script>


</body>

</html>
