<script type="text/template" id="ae-report-loop">
    <span class="message-avatar">
        {{=avatar}}
    </span>
    <div class="message-item">
        <h2 class="author-message">{{= display_name }}</h2>
        {{= comment_content }}

	    <# if(file_list){ #>
	    {{= file_list }}
	    <# } #>
        <span class="message-time">   {{= message_time }}</span>
    </div>
</script>