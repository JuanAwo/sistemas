<?php $this->load->view("components/page_header"); ?>
<?php $this->load->view("components/page_topbar"); ?>
<?php $this->load->view("components/page_menu"); ?>

        <aside class="right-side">
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <?php $this->load->view($subview); ?>
                    </div>
                </div>
            </section>
        </aside>

        <footer class="main-footer">
          	<strong><?=$siteinfos->footer?></strong>
            <a class="pull-right" target="_blank" href="https://www.facebook.com/profile.php?id=100007623007147"><i class="fa fa-facebook-official"></i> Síguenos en Facebook.</a>
        </footer>
<?php $this->load->view("components/page_footer"); ?>


