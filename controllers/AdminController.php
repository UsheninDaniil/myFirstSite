<?php

require_once(ROOT. '/models/Admin.php');
include_once ('/models/DatabaseConnect.php');

Class AdminController
{
    public function actionCabinet()
    {
        $user_role = "user";
        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $user_role = Admin::check_user_role($user_id);
        }

        if($user_role == "admin"){
            require_once ('/views/layouts/header.php');
            require_once (ROOT.'/views/admin/cabinet.php');
            require_once ('/views/layouts/footer.php');
        }
        else{
           header ("Location: /no_permission");
        }
    }

    public function actionNoPermission()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/NoPermission.php');
        require_once ('/views/layouts/footer.php');
    }

}

?>
