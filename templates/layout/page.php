<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title . (isset($page_title) ? " : $page_title" : '') ?></title>

	<link href="<?php echo $config['common_library_url'] ?>css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['common_library_url'] ?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $config['common_library_url'] ?>bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="<?php echo $config['site_home'] ?>css/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo $config['site_home'] ?>images/silk_theme.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo $config['site_home'] ?>css/library/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo $config['site_home'] ?>css/library/nprogress.css" rel="stylesheet">
  <link href="<?php echo $config['site_home'] ?>css/library/footable.css" rel="stylesheet">
  <link href="<?php echo $config['site_home'] ?>css/library/flat/green.css" rel="stylesheet">
  <link href="<?php echo $config['site_home'] ?>css/library/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <link href="<?php echo $config['site_home'] ?>css/library/jqvmap.min.css" rel="stylesheet"/>
  <link href="<?php echo $config['site_home'] ?>css/library/daterangepicker.css" rel="stylesheet">
  <link href="<?php echo $config['site_home'] ?>css/library/custom.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo $config['site_home'] ?>/css/layout.css">
  <?php echo $css_includes; ?>

  <!--Autofill functionality  -->
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  </head>

  <body class="nav-md">

    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">

            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><span><small>FAM</small></span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $user['name'] ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <br>
                <h3>Navigation Panel</h3>
                <ul class="nav side-menu">
                  <li><a href="dashboard.php"><i class="fa fa-home"></i> Overall Dashboard</a></li>
                  <li><a href="task-dashboard.php"><i class="fa fa-table"></i> Task Dashboard</a></li>
                  <li><a href="my_applicants.php"><i class="fa fa-edit"></i> My Applicants</a></li>

                  <!-- <li><a href="all_stages.php"><i class="fa fa-server"></i> Bulk Enter Data for All Applicants</a></li> -->
                      <?php if($is_director) { ?>
                      <li><a><i class="fa fa-wrench"></i>Tools <span class="fa fa-chevron-left"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="applicants.php"><i class="fa fa-users"></i> Applicants</a></li>
                        <li><a href="add_applicant.php"><i class="fa fa-user-plus"></i> Add Applicant</a></li>
                        <li><a href="find_applicant.php"><i class="fa fa-search"></i> Find Applicants</a></li>
                        <li><a href="all_in_one.php"><i class="fa fa-table"></i> All in One View</a></li>
                        <li><a href="assign_evaluators_applicants.php"><i class="fa fa-edit"></i> Assign Evaluators</a></li>
                        <li><a href="task_update.php"><i class="fa fa-tasks"></i> Task Update</a></li>
                        <li><a href="no_data.php"><i class="fa fa-exclamation-triangle"></i> Show Evaluators Who haven't entered Data</a></li>
                        <li><a href="evaluators.php"><i class="fa fa-clipboard"></i> Evaluators</a></li>
                        <li><a href="free_pool.php"><i class="fa fa-tree"></i> Free Pool</a></li>
                        <!-- <li><a href="edit_application.php"><i class="fa fa-edit"></i> Add/Edit Applications</a></li> -->
                        <li><a href="selected_fellows.php"><i class="fa fa-user"></i> Selected Fellows</a></li>
                        <li><a href="shelter_selection.php"><i class="fa fa-user"></i>Shelter Selection (SOFs)</a></li>
                        <!-- <li><a href="update_madapp.php"><i class="fa fa-lock"></i><em>Update MADApp</em></a></li> -->
                      </ul></li>
                      <?php } ?>
                  </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

          </div>
        </div>


        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

<!-- page content -->
<div class="right_col" role="main" id="main-content">

<?php if(i($QUERY, 'error') or i($QUERY, 'success')) { ?>
<div class="x_panel">
<div id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
  if(isset($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
  else print $QUERY['error']; //Its set in the code(validation error or something.
?></div>
<div id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>
</div>
<?php } ?>

<?php include($GLOBALS['template']->template); ?>

</div>

        <!-- footer content -->
        <!-- <footer>
          <div class="pull-right">
            Bootstrap Admin Template
          </div>
          <div class="clearfix"></div>
        </footer> -->
        <!-- /footer content -->
      </div>
    </div>

    <script src="<?php echo $config['site_home'] ?>/js/library/bootstrap.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/fastclick.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/nprogress.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/Chart.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/gauge.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/bootstrap-progressbar.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/icheck.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/skycons.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.pie.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.time.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.stack.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.resize.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.orderBars.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.flot.spline.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/curvedLines.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/date.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.vmap.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.vmap.world.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.vmap.sampledata.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/moment.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/daterangepicker.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.inputmask.bundle.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/jquery.knob.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/footable.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/custom.min.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/gridviewscroll.js"></script>
    <script src="<?php echo $config['site_home'] ?>/js/library/bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js"></script>
  	<script src="<?php echo $config['site_home'] ?>js/application.js" type="text/javascript"></script>
 	  <?php echo $js_includes; ?>
  </body>
</html>
