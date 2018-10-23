<?php
if(!defined('USE_SOCIAL') || !USE_SOCIAL ) return;
require_once dirname( __FILE__ ).'/social_auth.php';
require_once dirname( __FILE__ ).'/twitter.php';
require_once dirname( __FILE__ ).'/facebook.php';
require_once dirname( __FILE__ ).'/google.php';
require_once dirname( __FILE__ ).'/linkedin.php';
require_once dirname( __FILE__ ).'/settings.php';
require_once dirname( __FILE__ ).'/template.php';