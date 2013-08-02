<div id="content">
<h1><?php echo $title ?></h1>
<ul class="breadcrumb">
  <li><a href="/">Projects</a> <span class="divider">/</span></li>
  <li class="active"><?php echo $title ?></li>  
</ul>

<ul class="nav nav-tabs">
  <?php foreach ($pages as $item):?>
    <li<?php if($item['id']==$active_tab) echo ' class="active" '?>><a id="navTab<?php echo $item['id'];?>" href="/project/show/<?php echo "?id=$active_project&tab=".$item['id']?>"><?php echo $item['name'];?></a></li>
  <?php endforeach;?>
</ul>

<div id="workspace">
  <div id = "chartspace">
    
  </div>
</div>

<script>
  window.main.loadTab(<?php echo $active_project.",".$active_tab ?>);
</script>
</div>