  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="">Stats</a>
        <div class="nav-collapse collapse">
          <ul class="nav">
            
            
          </ul>   
          <ul class="nav pull-right">
            <li class="dropdown">              
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="time-button">Время<b class="caret"></b></a>                
                <ul class="dropdown-menu" role="menu" aria-labelledby="time-filter" id="time-filter">
                  <li>
                      <a href="#" onclick="return window.timeController.selectPeriod(this);">
                        <input type="radio" name="optionsRadios" id="time-week" value="time-week" style="margin-right: 10px;margin-top: -2px;"/>Неделя
                      </a>
                  </li>                  
                  <li>
                      <a href="#" onclick="return window.timeController.selectPeriod(this);">
                        <input type="radio" name="optionsRadios" id="time-mounth" value="time-mounth" style="margin-right: 10px;margin-top: -2px;" checked/>Месяц
                      </a>
                  </li>                  
                  <li>
                      <a href="#" onclick="return window.timeController.selectPeriod(this);">
                        <input type="radio" name="optionsRadios" id="time-2mounth" value="time-2mounth" style="margin-right: 10px;margin-top: -2px;"/>Все время
                      </a>
                  </li>                  
                  <li>
                      <a href="#" onclick="return window.timeController.selectPeriod(this);">
                        <input type="radio" name="optionsRadios" id="time-custom" value="time-custom" style="margin-right: 10px;margin-top: -2px;"/>Настроить                      
                      </a>
                        <div style="margin-left: 20px;" class="time-text">
                          <ul style="list-style: none;padding-right: 10px; padding-bottom: 10px;" >
                            <li><span style="float: left; margin-top: 12px;">Начало: </span>   <input class="btn" type="button" id="time-custom-start" value="time-custom-start" style="float: right;"/></li>
                            <li><span style="float: left; margin-top: 12px;">Окончание: </span><input class="btn" type="button" id="time-custom-stop"  value="time-custom-stop"  style="float: right;"/></li>
                          </ul>
                        </div>                    
                  </li>
                  <li><a href="" role="button" class="btn" data-toggle="modal" onclick="return window.timeController.applyPeriod();">Изменить</a></li>
                </ul>                
            </li>            
            <li class="dropdown">              
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="filter-button">Фильтр: Все <b class="caret"></b></a>                
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" id="filter-dropdown">
                  <?php foreach ($filters as $item):?>
                    <li class="dropdown-submenu">
                      <a href="#" onclick="return window.filter.setup(<?php echo str_replace("\"", "'", $item['data']).",\"".$item['name']."\""?>);">
                        <input type="radio" name="optionsRadios"  id="<?php echo $item['name']?>" value="<?php echo $item['name']?>" style="margin-right: 10px;margin-top: -2px;"/><?php echo $item['name']?>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="#" onclick="return window.filter.change(<?php echo str_replace("\"", "'", $item['data']).",\"".$item['name']."\""?>,this);">Изменить</a></li>
                        <li><a href="#" onclick="return    window.filter.del(<?php echo str_replace("\"", "'", $item['data']).",\"".$item['name']."\""?>,this);">Удалить</a></li>
                      </ul>
                    </li>
                  <?php endforeach;?> 
                  <li>
                      <a href="#" onclick="return window.filter.setup({},this);">
                        <input type="radio" name="optionsRadios" id="all" value="all" style="margin-right: 10px;margin-top: -2px;"/>Все
                      </a>                      
                  </li>
                  <li><a href="" role="button" class="btn" data-toggle="modal" onclick="return window.filter.show();">Добавить</a></li>
                </ul>                
            </li>  
            <li class="dropdown">
                <a href ="#" class="dropdown-toggle" id ="settings-group" data-toggle="dropdown" data-target="#">Управление<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="dropdown-submenu">
                    <a href="#" onclick="return false;">Диаграммы</a>
                    <ul class="dropdown-menu">
                      <li><a href="#" onclick="return flase;">Создать</a></li>
                    <?php foreach ($charts as $item):?>
                      <li <?php if($isAdmin) echo 'class="dropdown-submenu"'?>>
                        <a href="#" id="chart_menu_<?php echo $item['id']?>" onclick="return window.chart.appendChart(<?php echo $item['id']?>);"><?php echo $item['name']?></a>
                        <?php if($isAdmin) 
                            echo  '<ul class="dropdown-menu">'.
                                  '  <li><a href="#" onclick="return false;">Изменить</a></li>'.
                                  '  <li><a href="#" onclick="return false;">Удалить</a></li>'.
                                  '</ul>';?>                                
                      </li>
                    <?php endforeach;?>       
                    </ul>  
                  </li>                
                  <li class="dropdown-submenu">  
                    <a href="#" onclick="return false;">Страницы</a>
                    <ul class="dropdown-menu">                      
                      <li><a href="#" onclick="return window.wmanager.addPage();    ">Добавить страницу</a></li>
                      <li><a href="#" onclick="return window.wmanager.renamePage(); ">Переименовать страницу</a></li>
                      <li><a href="#" onclick="return window.wmanager.removePage(); ">Удалить страницу</a></li>
                    </ul>                  
                  </li>
                  <li class="dropdown-submenu">  
                    <a href="#" onclick="return false;">Пользователи</a>                    
                    <ul class="dropdown-menu">
                      <li><a href="#" onclick="return flase;">Создать</a></li>
                      <?php foreach ($users as $item):?>
                      <?php if($isAdmin) echo  
                         '<li class="dropdown-submenu">'.
                         '<a href="#" id="'.$item["id"].'" onclick="return flase;">'.$item['login']." ( ".$item['name']." )".'</a>'.
                         '<ul class="dropdown-menu">'.
                         '  <li><a href="#" onclick="return false;">Изменить</a></li>'.
                         '  <li><a href="#" onclick="return false;">Удалить</a></li>'.
                         '</ul>'.
                        '</li>';?>
                      <?php endforeach;?>                       
                    </ul>                                        
                    <li>  
                      <a href="#" onclick="return false;">Сбросить кеширование</a>                    
                    </li>
                </ul>
            </li>
            <li>
              <a href="login/logout">Выход</a>            
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>

<script type="text/javascript">
    jQuery(document).ready(function() {
      $('#filter').modal({show:false});
      period = window.timeController.getPeriod();      
      $('#time-custom-start').val($.datepicker.formatDate('yy-mm-dd', new Date(period.bday*1000)));      
      $('#time-custom-stop').val($.datepicker.formatDate('yy-mm-dd', new Date(period.eday*1000)));      
      
      $('#time-custom-stop').DatePicker({
        date: (new Date(period.eday*1000)),
        current: (new Date(period.eday*1000)),
        flat: false,
        calendars: 1,        
        position: 'right',        
        onChange: function(formated, dates){
          $('#time-custom-stop').val(formated);        
          $('#time-custom-stop').DatePickerHide(); 
          window.timeController.changeStop(dates);
        },
      });
      
      $('#time-custom-start').DatePicker({
        date: (new Date(period.bday*1000)),
        current: (new Date(period.bday*1000)),
        flat: false,
        calendars: 1,        
        position: 'right',
        onChange: function(formated, dates){
          $('#time-custom-start').val(formated);        
          $('#time-custom-start').DatePickerHide();        
          window.timeController.changeStart(dates);
        },
      });
      $('btn dropdown-toggle').dropdown();
      window.timeController.updateHeader();
    });
  </script>