
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-expense"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("expense/index")?>"><?=$this->lang->line('menu_expense')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_expense')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('expense')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="namea" class="col-sm-2 control-label">
                            <?=$this->lang->line("expense_expense")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="namea" name="expense" value="<?=set_value('expense')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('expense'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="date" class="col-sm-2 control-label">
                            <?=$this->lang->line("expense_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('amount')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="amount" class="col-sm-2 control-label">
                            <?=$this->lang->line("expense_amount")?>
                        </label>
                        <div class="col-sm-6">
                            
                           <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('amount'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("expense_note")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea style="resize:none;" class="form-control" id="note" name="note"><?=set_value('note')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_expense")?>" >
                        </div>
                    </div>

                </form>
                <?php if ($siteinfos->note==1) { ?>
                    <div class="col-md-8">
                    <div class="panel-group" id="accordion"> 
                        <div class="panel panel-default"> 
                            <div class="panel-heading"> 
                                <h4 class="panel-title"> 
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                                <span class="fa fa-send"></span> Más información</a> 
                                </h4> 
                            </div> 
                            <div id="collapseOne" class="panel-collapse collapse"> 
                                <div class="panel-body">
                                    <div class="col-md-12 text-justify">
                                        <div class="alert alert-dismissible alert-info">
                                            <p>
                                               Cuando agregue  los gastos de su institución estos se visualizaran en forma de gráficos en su panel principal.
                                            </p>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#date").datepicker();
</script>
