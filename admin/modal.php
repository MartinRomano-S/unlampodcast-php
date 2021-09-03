 <div class="modal fade" tabindex="-1" role="dialog" id="modal" name="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
        </div>
      </div>
    </div>
  </div>
  <?php 
    if( isset($_SESSION['error']) ){
          echo "<script>$(document).ready(function(){showError('".$_SESSION['error']."');});</script>";
          unset($_SESSION['error']);
    }else{
      if(isset($_SESSION['noerror'])){
        echo "<script>$(document).ready(function(){showNoError('".$_SESSION['noerror']."');});</script>";
            unset($_SESSION['noerror']);
      }
    }
   ?>