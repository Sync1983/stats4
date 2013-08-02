function chart_funct() {
  
  var defaultChartOptions = {
    title:        { text: "Chart" },    
    plotOptions:  { area: { marker: { enabled: true, symbol: 'circle', radius: 2, states: { hover: { enabled: true } } } } },
    series:       [{}],
    yAxis:        { title: { text: 'Кол-во' } }, 
    tooltip:      { crosshairs: [true],shared: true },
    chart:        { renderTo: $("chart"), type: 'areaspline', zoomType: 'none',
                    events: { 
                      load: onChartLoaded
                    }
                  },
    xAxis:       {  title:      { text: 'Время' }, 
                    type:       'datetime', 
                    showEmpty:  false, 
                    dateTimeLabelFormats: { day: '%e of %b'},       
                    labels:     {
                      rotation: -45, 
                      align:    'right', 
                      style:    {
                        fontSize: '10px',
                        fontFamily: 'Verdana, sans-serif'
                      } 
                    }
     }
  };
  
  function validateJSON(data) {try { data = JSON.parse(data); } catch(e) { console.log("Error in answer "+e+" in data "+data); return null; } return data; }
  function mergeRecursive(obj1, obj2) {for (var p in obj2) {if (obj2[p].constructor===Object ) obj1[p] = mergeRecursive(obj1[p], obj2[p]); else obj1[p] = obj2[p]; } return obj1; }
  function barFormatter() {
    var text = "Позиция: <b>"+ this.x + '</b><br>';
    for(var i in this.points) {
     var append = "";
     if((this.points[i].userOptions)&&(this.points[i].series.userOptions.units))
      append = this.points[i].series.userOptions.units;
     text += "<br>"+this.points[i].series.name+": <i>";
     var y = this.points[i].y;
     if(y%1!==0)
       text += Highcharts.numberFormat(this.points[i].y, 1);
     else
       text += this.points[i].y;
     text += "</i> "+append;
    }
    return text;
  };

  function onChartLoaded(event) {    
    var chart = event.currentTarget;
    var chart_id = chart.chart_id;
    
    function onAnswer(data){
      if(!(data = validateJSON(data)))
        return false;
      
      if(data.name)
        chart.setTitle({text:data.name},{});
      
      if(data.series) {      
        for(var i in chart.series)
          chart.series[i].remove();
        chart.series = new Array();
        chart.redraw();
        for(var i in data.series)
          chart.addSeries(data.series[i],true);         
      }
      console.log(data);
    }
    
    var data = window.timeController.getPeriod();    
    data['chart_id']  = chart.options.chart.chart_id;
    data['filter']    = window.filter.getParams();    
    window.main.ajax('chart','ajax_load_chart',data, onAnswer);
  }
   
  this.addChart = function (container,chart_id) {
    var options             = defaultChartOptions;    
    options.chart.renderTo  = container;
    options.chart.chart_id  = chart_id;
    return new Highcharts.Chart(options);
  };

  this.appendChart = function (id) {
    var data = {};
    data['project_id']  = window.main.getProjectId();
    data['page_id']     = window.main.getPageId();
    data['chart_id']    = id;
    window.main.ajax('project','ajax_append_chart',data, onAnswer);
    
    function onAnswer(data){
      if(!(data = validateJSON(data))){
        console.log("Json error in",data);
        return;
      }
      if(data.error){
        console.log("Append Chart answer error: "+ data.error);
        return;
      }
      if(data.success)
        window.main.pageReload();
    }
  
    return false;
  };

  this.changeView = function (view,chart) {
    var parts = chart.split("_");    
    var page_id   = parseInt(parts[0]);
    var position  = parseInt(parts[1]);
    var chart_id  = parseInt(parts[2]);
    if(page_id!==window.main.getPageId())
      return;
    var data = {};    
    data['chart_id']  = chart_id;
    data['page_id']   = page_id;    
    data['view']      = view;
    window.main.ajax('chart','ajax_change_chart_view',data, onAnswer);
    
    function onAnswer(data){
      if(!(data=validateJSON(data)))
        return false;      
      if(!data.success)
        return false;
      
      this.addChart($("li#chart_"+chart+">div.chart_graph")[0],"chart_"+chart);    
      return false;
    }
  
    return false;
  };

  return this;
 }

window.chart = chart_funct();
