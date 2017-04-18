<!DOCTYPE html>
<html>

<head>
  <title>RedditEarth - Search with Image Recognition</title>

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
         href="#">RedditEarth</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse"
         id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li>
          <a href="#">About</a>
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
<script src="js/select2_config.js"></script>
<script src="js/load_images.js"></script>


</body>

</html>
