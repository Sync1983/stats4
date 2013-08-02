function windows_manager() {
  function validateJSON(data) {try { data = JSON.parse(data); } catch(e) { console.log("Error in answer "+e+" in data "+data); return null; } return data; }
  
  this.addPage = function() {
    var title = "Добавить вкладку";
    var html =       
      '<div class="input-group">'+
      '<span class="input-group-addon">Имя: </span>'+
      '<input type="text" class="form-control input-large" id="name" style="width:100%; margin-top:10px;" placeholder="Tab name">'+
      '<div class="error" style="display:none;color:red"></div></div>';
      
    var dialog = $(html).dialog({
          width:  500,               
          resizable:false,
          title: title,
          close: function() {dialog.remove();},
          buttons: { "Сохранить": onSave },               
          modal: true
      });   
      
    function onSave() {
      var data = {};
      data['project_id'] = window.main.getProjectId();
      var name = $(dialog).children('input#name').val();      
      if((!name)||(name==="")){
        console.log($(dialog));
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Пустое имя");
        return;
      }
      data['name'] = name;
      window.main.ajax('project','ajax_add_tab',data,onAnswer);
    }
  
    function onAnswer(data) {
      if(!(data = validateJSON(data))) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Ошибка сервера");
        return;
      }
      if(data.error){
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Ошибка сервера "+data.error);
        return;
      }
      if(data.success==="Ok!") {
        dialog.dialog('close');
        window.location = window.location;
      };
    }
    
    return false;  
  };

  this.removePage = function() {
    var title = "Удалить активную вкладку?";
    var html  = '<div><div class="alert alert-danger">Уверены, что хотите удалить активную вкладку?</div><div class="error" style="display:none;color:red"></div></dvi>';
      
    var dialog = $(html).dialog({
          width:  500,               
          resizable:false,
          title: title,
          close: function() {dialog.remove();},
          buttons: { "Удалить": onAccept},               
          modal: true
      });   
      
    function onAccept() {
      var data = {};
      data['project_id']  = window.main.getProjectId();
      data['page_id']     = window.main.getPageId();
      window.main.ajax('project','ajax_remove_tab',data,onAnswer);
    }
  
    function onAnswer(data) {
      if(!(data = validateJSON(data))) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Ошибка сервера");
        return;
      }
      if(data.error){
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Ошибка сервера "+data.error);
        return;
      }
      if(data.success==="Ok!") {
        dialog.dialog('close');
        window.location = "/project/show/?id="+window.main.getProjectId();
      };
    }
    
    return false;  
  };

  this.renamePage = function() {
    var title = "Переименовать вкладку?";
    var name = $(".nav.nav-tabs>li.active")[0].textContent;
    var html =       
      '<div class="input-group">'+
      '<span class="input-group-addon">Имя: </span>'+
      '<input type="text" class="form-control input-large" id="name" style="width:100%; margin-top:10px;" value="'+name+'">'+
      '<div class="error" style="display:none;color:red"></div></div>';
      
    var dialog = $(html).dialog({
          width:  500,               
          resizable:false,
          title: title,
          close: function() {dialog.remove();},
          buttons: { "Переименовать": onRename},               
          modal: true
      });   
    
    var newName = name;
    
    function onRename() {
      var data = {};
      data['project_id']  = window.main.getProjectId();
      data['page_id']     = window.main.getPageId();
      var name = $(dialog).children('input#name').val();      
      if((!name)||(name==="")){
        console.log($(dialog));
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Пустое имя");
        return;
      }
      data['name'] = name;
      newName = name;
      window.main.ajax('project','ajax_rename_tab',data,onAnswer);
    }
  
    function onAnswer(data) {
      if(!(data = validateJSON(data))) {
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Ошибка сервера");
        return;
      }
      if(data.error){
        dialog.children(".error").css('display','block');
        dialog.children(".error").text("Ошибка сервера "+data.error);
        return;
      }
      if(data.success==="Ok!") {
        dialog.dialog('close');
        $($(".nav.nav-tabs>li.active>a")[0]).text(newName);
      };
    }
    
    return false;  
  };

  this.showToolbox = function(item) {
    var parent = $(item).parent();
    var toolbox = $(parent).children(".panel.btn-toolbar");
    var state = $(toolbox).css('display');    
    if((state==="none")||(!state))
      $(toolbox).css('display','block');
    else
      $(toolbox).css('display','none');
    return false;
  }
  
  return this;
}

window.wmanager = windows_manager();


