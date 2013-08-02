
function main_funct(parent) {
  var saved_pid   = 0;
  var saved_page  = 0;
  
  function validateJSON(data) {try { data = JSON.parse(data); } catch(e) { console.log("Error in answer "+e+" in data "+data); return null; } return data; }
  function showLoader(){ $('.ajax-loader').css('display','block'); return; };
  function hideLoader() { $('.ajax-loader').css('display','none'); return; };
  this.ajax = function(controller,action,data,success) {
    showLoader();
    var ajaxRequest = {};
    ajaxRequest.type = "POST";
    ajaxRequest.data = data;
    ajaxRequest.url = '/'+controller+'/'+action;
    ajaxRequest.success = function(data) {
      hideLoader();
      if(!success)
        return;
      success(data);
    };
    $.ajax(ajaxRequest);
  };
  this.loginClick = function() {    
    var login     = $('#login').val();    
    var password  = $('#password').val();
    var remember  = $('#remember').attr('checked');
    
    this.ajax('login','ajax_login',{login:login,password:password,remember:remember},function(returnData){
      var data = JSON.parse(returnData);      
      if(data.error) {
        $('.errors').css('display','block');
        $('.errors').text('*'+data.error);
        return false;
      } else if(data.redirect) {        
        location.href = data.redirect;
      }
      $('.errors').css('display','none');      
    });
    return false;
  };

  this.getProjectId   = function(){ return saved_pid;};
  this.getPageId      = function(){ return saved_page;};

  this.loadTab = function(project_id,page_id) {    
    saved_pid = project_id;
    saved_page = page_id;
    
    $('.chart').remove();
    
    function activateChart(index, chart){      
      var container = $(chart).children("div.chart_graph")[0];
      var chart_id  = $(chart).attr('id');
      window.chart.addChart(container,chart_id);
    }
    
    function onAnswer(data) {      
      if(!(data = validateJSON(data)))
        return false;
      
      if(data.error) {
        alert(data.error);                        
        return false;
      }
    
      if(data.html) {
        $('#chartspace').html(data.html);
        $('.chart').each(activateChart);
      }                      
      return false;
    }
    
    this.ajax('project','ajax_load_tab',{project_id:project_id, page_id:page_id}, onAnswer);
    return false;                    
  };

  this.pageReload = function(){
    this.loadTab(saved_pid,saved_page);
  };
  
  return this;
}

window.main = main_funct(window);


