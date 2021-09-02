<?php
$active = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
// echo "active:$active";
$nav_items = array(
  ["Dashboard","home","FeatureList.php"],
  ["日報","file-text","DailyReport.php"],
  ["除權息預告表","file","ExrightAnnouncement.php"],
  ["每日排行","shopping-cart","DailyRanking.php"],
  ["庫存","layers","Inventory.php"],
  ["追蹤","bar-chart-2","DailyRanking1.php"],
);
$menu_content = "";
foreach ($nav_items as $item) {
  if ($active == $item[2]){
    $menu_content .= "<li class=\"nav-item\">\r\n
      <a class=\"nav-link active\" href=\"#\">\r\n
        <span data-feather=\"$item[1]\"></span>\r\n
        $item[0] <span class=\"sr-only\">(current)</span>\r\n
      </a>\r\n
    </li>\r\n";
  }else{
    $menu_content .= "<li class=\"nav-item\">\r\n
      <a class=\"nav-link \" href=\"$item[2]\">\r\n
        <span data-feather=\"$item[1]\"></span>$item[0]\r\n
      </a>\r\n
    </li>\r\n";
  }
}

?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
          <?=$menu_content?>
        <!--
          <li class="nav-item">
            <a class="nav-link active" href="#">
              <span data-feather="home"></span>
              Dashboard <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ExrightAnnouncement.php">
              <span data-feather="file"></span>
              除權息預告表
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="DailyRanking.php">
              <span data-feather="shopping-cart"></span>
              每日排行
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="users"></span>
              Customers 客戶
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="bar-chart-2"></span>
              Reports
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="layers"></span>
              Integrations
            </a>
          </li>
        </ul>
-->
        
      </div>
    </nav>