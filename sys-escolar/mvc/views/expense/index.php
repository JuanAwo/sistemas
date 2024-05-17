
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-expense"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_expense')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('expense_add')) { ?>
                <h5 class="page-header">
                    <a class="btn btn-success" href="<?php echo base_url('expense/add') ?>">
                        <i class="fa fa-plus"></i> 
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('expense_expense')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('expense_date')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('expense_uname')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('expense_amount')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('expense_note')?></th>
                                <?php if(permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_expense = 0; if(count($expenses)) {$i = 1; foreach($expenses as $expense) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('expense_expense')?>">
                                        <?php echo $expense->expense; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('expense_date')?>">
                                        <?php echo date("d M Y", strtotime($expense->date)); ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('expense_uname')?>">
                                        <?php echo $expense->uname; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('expense_amount')?>">
                                        <?php echo $expense->amount; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('expense_note')?>">
                                        <?php echo $expense->note; ?>
                                    </td>

                                    <?php if(permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_edit('expense/edit/'.$expense->expenseID, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('expense/delete/'.$expense->expenseID, $this->lang->line('delete')) ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>