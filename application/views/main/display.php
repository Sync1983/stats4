<div style="width: 90%; left: 5%; position: relative;">
<div style="margin-top: 45px; margin-bottom: 10px; height:30px">
  <?php
  if($isAdmin)
    echo '<button class="btn btn-primary" onclick="return main.createProject();">Создать</button>';
  ?>
</div>
<table id="projects">
  <thead>
    <tr>
      <th>ID</th>
      <th>Название</th>
      <th style="width: 20%;">Key</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($projects as $item):?>
      <tr>
        <td><?php echo $item['id'];?></td>
        <td><a href="/project/show/?id=<?php echo $item['id'];?>"><?php echo $item['title'];?></a></td>            
        <td><?php echo $item['api_key'];?></td>
      </tr>
  <?php endforeach;?>    
  </tbody>
</table>

<script>
  $('#projects').dataTable({'iDisplayLength': 50 });
</script>
</div>