<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <section class="error-404 clearfix">
                <div class="left-col">
                    <p><?php _e('404', 'nictitate-lite');?></p>
                </div><!--left-col-->
                <div class="right-col">
                    <h1><?php _e('Page not found...', 'nictitate-lite');?></h1>
                    <p><?php _e("We're sorry, but we can't find the page you were looking for. It's probably some thing we've done wrong but now we know about it we'll try to fix it. In the meantime, try one of this options:", 'nictitate-lite');?></p>
                    <ul class="arrow-list">
                        <li><a href="javascript: history.go(-1);"><?php _e('Go back to previous page', 'nictitate-lite');?></a></li>
                        <li><a href="<?php echo home_url('/'); ?>"><?php _e('Go to homepage', 'nictitate-lite');?></a></li>
                    </ul>
                </div><!--right-col-->
            </section><!--error-404-->
        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->