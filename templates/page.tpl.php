<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
 <div class="container">
  <div class="navbar-header">
   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
   </button>
   <a class="navbar-brand" href="<?php print $front_page; ?>" rel="home"><?php print $site_name; ?></a>
  </div><!-- /.navbar-header -->
  <?php if ($main_menu): ?>
  <div class="navbar-collapse collapse">
   <?php print(render(menu_tree('main-menu'))); ?>
  </div><!--/.navbar-collapse -->
  <?php endif;?>
 </div><!-- /.container -->
</div><!-- /.navbar -->

<div class="container">
 <?php if ($title && !drupal_is_front_page()): ?>
 <div class="page-header">
  <h1><?php print $title; ?></h1>
 </div>
 <?php endif; ?>
 <?php print $messages; ?>
 <div class="row">
  <?php if ($page['highlighted']): ?>
  <div class="col-md-12 highlighted">
   <?php print render($page['highlighted']); ?>
  </div>
  <?php endif; ?>
  <?php if ($page['slideshow']): ?>
  <div class="col-md-12 slideshow">
   <?php print render($page['slideshow']); ?>
  </div>
  <?php endif; ?>
  <?php if ($page['sidebar_first']): ?>
  <div class="col-md-3 sidebar sidebar-first">
   <?php print render($page['sidebar_first']); ?>
  </div>
  <?php endif; ?>
  <?php if($page['sidebar_first'] && $page['sidebar_second']) { ?>
  <div class="col-md-6">
  <?php } elseif($page['sidebar_first'] || $page['sidebar_second']) { ?>
  <div class="col-md-9">
  <?php } ?>
   <?php if (isset($tabs)): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
   <?php print render($page['help']); ?>
   <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>

   <?php print render($page['content']); ?>
   <?php if ($page['content_bottom']): ?>
    <?php print render($page['content_bottom']); ?>
   <?php endif; ?>
  </div>
  <?php if ($page['sidebar_second']): ?>
  <div class="col-md-3 sidebar sidebar-second">
   <?php print render($page['sidebar_second']); ?>
  </div>
  <?php endif; ?>
 </div><!-- /.row-->
 <div class="push"></div>
</div><!-- /.container -->

<div id="footer">
 <div class="container text-muted">
   <?php print render($page['footer']); ?>
 </div>
</div>
      <?php  /*if ($logo): ?>
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
      <?php endif; */ ?>
