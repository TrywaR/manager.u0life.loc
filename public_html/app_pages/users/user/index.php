<?
$oUser = new user( $_REQUEST['user_id'] );
$oUser->show_rewards = true;
$oUser->show_role_val = true;
if ( $oLock->iUserRole < 500 ) {
  $oUser->query .= ' AND `public` > 0';
}
$arrUser = $oUser->get_user();
?>
<?php if ($arrUser['id']): ?>
  <div class="container">
    <div class="row block_user">
      <div class="col-12 _header">
        <div class="_img">
          <i class="fa-solid fa-user"></i>
        </div>

        <div class="_name">
          <h1><?=$oUser->login?></h1>
        </div>

        <div class="_rewards">
          <?php if ($arrUser['rewards']): ?>
            <div class="block_rewards d-flex justify-content-center align-items-center">
              <?=$arrUser['rewards']?>
            </div>
          <?php endif; ?>
        </div>
      </div>

       <div class="col-12 col-xl-7 _card">
         <div class="card">
           <!-- <div class="card-header">
             <?=$oUser->login?>
           </div> -->

           <div class="card-body">
             <small>
               <?=$oLang->get('Other')?>
             </small>
             <ul class="list-group mt-1 mb-1">
               <li class="list-group-item">
                 <?php if ($oUser->role == 0): ?>
                   <span class="_icon">
                     <i class="fas fa-user-circle"></i>
                   </span>
                   <span class="_value">
                     User
                   </span>
                 <?php endif; ?>
                 <?php if ($oUser->role > 0 && $oUser->role < 500): ?>
                   <span class="_icon">
                     <i class="fas fa-check"></i>
                   </span>
                   <span class="_value">
                     PRO user
                   </span>
                 <?php endif; ?>
                 <?php if ($oUser->role >= 500): ?>
                   <span class="_icon">
                     <i class="fas fa-crown"></i>
                   </span>
                   <span class="_value">
                     Admin
                   </span>
                 <?php endif; ?>
               </li>
             </ul>
           </div>
         </div>
       </div>
     </div>
  </div>
<?php else: ?>
  <div class="container">
    <div class="row block_user">
      <i class="fa-solid fa-user-secret"></i>
    </div>
  </div>
<?php endif; ?>
