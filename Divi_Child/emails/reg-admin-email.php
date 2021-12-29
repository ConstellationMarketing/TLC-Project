The following transaction has been processed: <br/>
<br/>
Course Number: <?php echo $course_sku; ?>, <br/>
Student: <?php echo $firstname; ?> <?php echo $lastname; ?>, <br/>
Payment Type: <?php echo $payment_type; ?> <br/>
Amount: $<?php echo $course_total; ?> <br/>
Transaction Information: <?php echo $payment_method; ?>  <br/>
<?php if ( $financial_aid ): ?>
  <b>Student has requested Financial Aid</b>
<?php endif; ?>