The following transaction has been processed: <br/>
<br/>
Course Number: <?php echo $course_sku; ?>, <br/>
Student: <?php echo $firstname; ?> <?php echo $lastname; ?>, <br/>
<?php if ( $user_asked_for_aid ): ?>
  <b>Student has requested Financial Aid</b>
<?php endif; ?>