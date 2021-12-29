<table style="visibility: hidden;" id="students-table">
  <thead>
    <tr>
      <th>Profile Picture</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Firm</th>
      <th>State</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($students as $student): ?>
      <?php $userdata = get_userdata($student->ID); ?>
      <tr data-id="<?php echo $student->ID; ?>">
        <td><img width="64" height="64" src="<?php echo get_avatar_url($student->ID, ['size' => 64]) ?>"></td>
        <td><?php echo $userdata->first_name ?></td>
        <td><?php echo $userdata->last_name ?></td>
        <td><?php echo get_user_meta( $student->ID, 'billing_company', true ); ?></td>
        <td><?php echo get_user_meta( $student->ID, 'billing_state', true ); ?></td>
        <td><a href="mailto:<?php echo $userdata->user_email; ?>"><?php echo $userdata->user_email; ?></a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  window.addEventListener('load', function () {
    jQuery(document).ready(function ($) {
      $('#students-table thead').append('<tr><th></th><th></th><th></th><th></th><th></th><th></th></tr>');
      $('#students-table thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" class="column_search" />' );
      } );
  
    // DataTable
    var table = $('#students-table').DataTable({
      orderCellsTop: true,
      order: [1, 'asc']
    });
  
 
 
    $( '#students-table thead'  ).on( 'keyup', ".column_search",function () {
   
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    } );
      $('#students-table').css({visibility: 'visible'})
    })
  })
</script>