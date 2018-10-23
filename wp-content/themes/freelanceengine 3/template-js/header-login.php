<script type="text/template" id="header_login_template">

    <div class="dropdown-info-acc-wrapper">
        <div class="et-dropdown dropdown">
            <div class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                <span class="avatar">
                    <img alt="" src="{{= et_avatar_url }}" class="avatar avatar-96 photo avatar-default" height="96" width="96">
                    {{= display_name }}
                </span>
                <span class="caret"></span>
            </div>
            <ul class="dropdown-menu et-dropdown-login" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="<?php echo et_get_page_link('profile'); ?>" class="display-name">
                        <i class="fa fa-user"></i><?php _e("Your Profile", ET_DOMAIN) ?>
                    </a>
                </li>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="<?php echo wp_logout_url(); ?>" class="logout">
                        <i class="fa fa-sign-out"></i><?php _e("Logout", ET_DOMAIN) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</script>