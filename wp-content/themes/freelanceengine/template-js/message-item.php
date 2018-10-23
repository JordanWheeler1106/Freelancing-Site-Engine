<?php
    if( defined( 'MILESTONE_DIR_URL' ) ) {
        ?>
        <script type="text/template" id="ae-message-loop">
            <# if( changelog == 1 ) { #>
                {{=str_message}}
            <# } else { #>
                <div class="form-group-work-place">
                    <div class="avatar-chat-wrapper">
                        <a href="#" class="avatar-employer">
                            {{=avatar}}
                        </a>
                    </div>
                    <div class="content-chat-wrapper">
                        <div class="triangle"></div>
                        <div class="content-chat fixed-chat">
                         <div class="param-content">{{= comment_content }}</div>
                         {{= file_list }}
                        </div>
                        <div class="date-chat">{{= message_time }}</div>
                    </div>
                </div>
            <# } #>
        </script>
        <?php
    } else {
        ?>
        <script type="text/template" id="ae-message-loop">
            <div class="form-group-work-place">
                <div class="avatar-chat-wrapper">
                    <a href="#" class="avatar-employer">
                        {{=avatar}}
                    </a>
                </div>
                <div class="content-chat-wrapper">
                    <div class="triangle"></div>
                    <div class="content-chat fixed-chat">
                        <div class="param-content">{{= comment_content }}</div>
                     {{= file_list }}
                    </div>
                    <div class="date-chat">{{= message_time }}</div>
                </div>
            </div>
        </script>
        <?php
    }
?>
