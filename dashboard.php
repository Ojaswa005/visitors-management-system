<?php
session_start();
include 'connection.php';
$name = $_SESSION['name'];
$id = $_SESSION['id'];
if(empty($id))
{
    header("Location: index.php"); 
}

 $total_visitors = mysqli_query($conn,"select count(*) from tbl_visitors");
 $total_visitors = mysqli_fetch_row($total_visitors);

 $today_visitors = mysqli_query($conn,"select COUNT(*) from tbl_visitors where DATE(in_time) = CURDATE();");
 $today_visitors = mysqli_fetch_row($today_visitors);

 $yesterday_visitors = mysqli_query($conn,"select count(*) from tbl_visitors where DATE(in_time)=CURDATE()-1");
 $yesterday_visitors = mysqli_fetch_row($yesterday_visitors);

 $week_visitors = mysqli_query($conn,"select count(*) from tbl_visitors where DATE(in_time)>= CURDATE()-7");
 $week_visitors = mysqli_fetch_row($week_visitors);

?>
<?php include('include/header.php'); ?>

  <div id="wrapper">

    <?php include('include/side-bar.php'); ?>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          
        </ol>
<div class="row">
  <div class="col-sm-4">
    <section class="panel panel-featured-left panel-featured-primary">
      <div class="panel-body">
        <div class="widget-summary">
          <div class="widget-summary-col widget-summary-col-icon">
            <div class="summary-icon bg-secondary">
              <i class="fa fa-user"></i>
            </div>
          </div>
          <div class="widget-summary-col">
            <div class="summary">
              <h4 class="title">Total Visitors</h4>
              <div class="info">
                <strong class="amount"><?php echo $total_visitors[0]; ?></strong><br>   
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="col-sm-4">
    <section class="panel panel-featured-left panel-featured-primary">
      <div class="panel-body">
        <div class="widget-summary">
          <div class="widget-summary-col widget-summary-col-icon">
            <div class="summary-icon bg-secondary">
              <i class="fa fa-user"></i>
            </div>
          </div>
          <div class="widget-summary-col">
            <div class="summary">
              <h4 class="title">Today Visitors</h4>
              <div class="info">
                <strong class="amount"><?php echo $today_visitors[0]; ?></strong><br>  
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="col-sm-4">
    <section class="panel panel-featured-left panel-featured-primary">
      <div class="panel-body">
        <div class="widget-summary">
          <div class="widget-summary-col widget-summary-col-icon">
            <div class="summary-icon bg-secondary">
              <i class="fa fa-user"></i>
            </div>
          </div>
          <div class="widget-summary-col">
            <div class="summary">
              <h4 class="title">Yesterday Visitors</h4>
              <div class="info">
                <strong class="amount"><?php echo $yesterday_visitors[0]; ?></strong><br>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="col-sm-4">
    <section class="panel panel-featured-left panel-featured-primary">
      <div class="panel-body">
        <div class="widget-summary">
          <div class="widget-summary-col widget-summary-col-icon">
            <div class="summary-icon bg-secondary">
              <i class="fa fa-user"></i>
            </div>
          </div>
          <div class="widget-summary-col">
            <div class="summary">
              <h4 class="title">Last 7 Days Visitors</h4>
              <div class="info">
                <strong class="amount"><?php echo $week_visitors[0]; ?></strong><br>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
</div>
</div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <?php include('include/footer.php'); ?>
