Dear <?php echo $user_name ?>,<br/>
<br/>
We received a request from <?php echo $current_user_name . ' ' . $current_user_last_name ?>, a TLC student, who would like to connect with you, possibly to refer you a case or discuss local counsel. Please respond to this student at your earliest convenience directly via email at <?php echo $return_address; ?>.<br/>
<br/>
<b>Request Subject:</b> <br/>
<?php echo $request_subject; ?> <br/>
<br/>
<b>Request Message:</b> <br />
<?php echo $request_message; ?> <br/>
<br/>
If you’d like to to learn more about <?php echo $current_user_name . ' ' . $current_user_last_name ?> before responding, here’s a link to the TLC directory: <a href="<?php echo get_home_url() . '/alumni-resources/student-directory/' ?>"><?php echo get_home_url() . '/alumni-resources/student-directory/' ?></a><br/>
<br/>
Have a great day!,<br/>
<br/>
<b>Laurie Goodman and the TLC website team</b>