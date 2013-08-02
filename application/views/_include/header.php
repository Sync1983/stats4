<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js" type="text/javascript"></script>
  <base href="<?php echo $base_url?>"/>
  
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" href="/styles/bootstrap.min.css"            ></link>
  <link rel="stylesheet" href="/styles/bootstrap-responsive.min.css" ></link>  
  <link rel="stylesheet" href="/styles/jquery.dataTables.css"        ></link>
  <link rel="stylesheet" href="/styles/main.css"                     ></link>
  <link rel="stylesheet" href="/styles/filter.css"                   ></link>
  <link rel="stylesheet" href="/styles/datepicker.css"               ></link>
  
  <script src="/js/bootstrap.min.js"         ></script>
  <script src="/js/main.js"                  ></script>
  <script src="/js/windows_manager.js"       ></script>
  <script src="/js/jquery.dataTables.min.js" ></script>
  <script src="/js/chart.js"                 ></script>
  <script src="/js/filter.js"                ></script>
  <script src="/js/time_control.js"          ></script>
  <script src="/js/highcharts.src.js"        ></script>
  <script src="/js/datepicker.js"            ></script>
  <script src="/js/jquery.jeditable.mini.js" ></script>
  
</head>
  <body>    
    <div id="filter" class="modal hide fade" data-show="false">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Фильтр</h3><span>Имя: </span><input style = "margin-top: 5px;" type="text" name="name" style="width: 40%;"/>
      </div>
      <div class="modal-body" style="height: 800px">    
        <table style="width:100%">
        </table>
      </div>
      <div class="modal-footer">
        <a id="filter-apply" href="#" class="btn btn-primary" onclick="return window.filter.apply(this);">Apply</a>
        <a id="filter-save"  href="#" class="btn btn-primary" onclick="return window.filter.save(this);">Save filter</a>
      </div>
    </div> 