<?php
?>
Hi {{$name}}

Thank you for joining 6 Acts to save Democracy.
Your login id is your email {{$email}}
@isset($password)
As you've logged in Six Acts using your social media account or Google, we've automatically
created an account for you. You don't need to anything as you can keep using the site
logged in as you are.  We've generated this password for you: {{$password}}.
You can use your profile to change it - see the link below.
@endisset

You can access your profile here {{url('user/profile')}}

In your profile page you can change a few settings, including your password and avatar.

Thank you,

The 6 Acts team

Six Acts<
Flat 408, 41 Old Birley Street
Manchester M15 5RE
United Kingdom
