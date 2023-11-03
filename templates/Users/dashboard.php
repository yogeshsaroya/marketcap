<?php $this->assign('title', 'Dashboard | ' . env('APP_NAME'));
$auth = $this->request->getSession()->read('Auth.User');

?>




<?php $this->append('scriptBottom');  ?>
<script>
  $(document).ready(function() {
    


  });
</script>
<?php $this->end();  ?>