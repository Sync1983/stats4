<ul style="margin-left: 10px; padding: 0px;" id="main-view" class="ui-sortable">  
  <?php foreach ($charts as $item):?>
    <li class="chart" id="chart_<?php echo $item['page_id']."_".$item['position']."_".$item['counter_id']?>">
      <div class="chart_graph"></div>
      <div class="chart-toolbox" onclick="return window.main.showToolbox(this);"></div>
      <div class="panel btn-toolbar" style="display: none; margin-left: 20px; margin-top: -4px">
        <div class="btn-group" style="margin-left: 30px;">          
          <button type="button" class="btn btn-default" onclick="window.chart.changeView(2,<?php echo "'".$item['page_id']."_".$item['position']."_".$item['counter_id']."'"?>);">Линия</button>
          <button type="button" class="btn btn-default" onclick="window.chart.changeView(0,<?php echo "'".$item['page_id']."_".$item['position']."_".$item['counter_id']."'"?>);">Сплайн</button>
          <button type="button" class="btn btn-default" onclick="window.chart.changeView(3,<?php echo "'".$item['page_id']."_".$item['position']."_".$item['counter_id']."'"?>);">Область</button>
          <button type="button" class="btn btn-default" onclick="window.chart.changeView(1,<?php echo "'".$item['page_id']."_".$item['position']."_".$item['counter_id']."'"?>);">Столбцы</button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default" onclick="   window.chart.exportCSV(<?php echo "'".$item['page_id']."_".$item['position']."_".$item['counter_id']."'"?>);">Экспорт CSV</button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default" onclick="  window.main.deleteChart(<?php echo "'".$item['page_id']."_".$item['position']."_".$item['counter_id']."'"?>);">Удалить</button>
        </div>
      </div>
    </li>
  <?php endforeach;?>    
</ul>

<script>
  //$("#main-view").sortable({ stop: updateChartPosition, cancel: ".sortable-disabled"});
  $("#main-view").disableSelection();
</script>