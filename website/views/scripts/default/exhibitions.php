<div class="row section_header">
    <div class="span12">
        <hr/>
    </div>
</div>

<ul class="nav nav-pills pull-right">
    <?php if(count($this->view) > 0): ?><li class="<?php if($this->active == "view"): ?>active<?php endif; ?>"><a href="#view" data-toggle="tab">VIEW</a></li><?php endif; ?>
    <?php if(count($this->current) > 0): ?><li class="<?php if($this->active == "current"): ?>active<?php endif; ?>"><a href="#current" data-toggle="tab">CURRENT</a></li><?php endif; ?>
    <?php if(count($this->upcoming) > 1) : ?><li class="<?php if($this->active == "upcoming"): ?>active<?php endif; ?>"><a href="#upcoming" data-toggle="tab">UPCOMING</a></li><?php endif; ?>
    <?php if(count($this->past) > 0) : ?><li class="<?php if($this->active == "past"): ?>active<?php endif; ?>"><a href="#past" data-toggle="tab">PAST</a></li><?php endif; ?>
</ul>
<div class="clearfix"></div>

<div class="tab-content">
    <?php if(count($this->view) > 0): ?>
        <div class="tab-pane <?php if($this->active == "view"): ?>active<?php endif; ?>" id="view">
            <?php echo $this->partial("includes/exhibition.php", array("exhibition" => current($this->view))) ?>
        </div>
    <?php endif; ?>
    <?php if(count($this->current) > 0): ?>
        <div class="tab-pane <?php if($this->active == "current"): ?>active<?php endif; ?>" id="current">
            <?php echo $this->partial("includes/exhibition.php", array("exhibition" => current($this->current))) ?>
        </div>
    <?php endif; ?>
    <?php if(count($this->upcoming) > 1): ?>
        <div class="tab-pane <?php if($this->active == "upcoming"): ?>active<?php endif; ?>" id="upcoming">
            <?php echo $this->partial("includes/exhibitionList.php", array("exhibitions" => $this->upcoming)) ?>
        </div>
    <?php endif; ?>
    <?php if(count($this->past) > 0): ?>
        <div class="tab-pane <?php if($this->active == "past"): ?>active<?php endif; ?>" id="past">
            <?php echo $this->partial("includes/exhibitionList.php", array("exhibitions" => $this->past)) ?>
        </div>
    <?php endif; ?>
</div>
